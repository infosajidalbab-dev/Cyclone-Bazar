<?php

namespace App\Services\Order;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Address;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Payment\CodGateway;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway
    ) {}

    /**
     * Create an order with atomic row-locking to prevent inventory race conditions.
     *
     * @param array{
     *     customer: array{name: string, phone: string, email?: string|null},
     *     shipping: array{
     *         recipient_name: string,
     *         recipient_phone: string,
     *         delivery_zone_id: int,
     *         division: string,
     *         district: string,
     *         upazila: string,
     *         area?: string|null,
     *         street_address: string,
     *         landmark?: string|null,
     *         postal_code?: string|null
     *     },
     *     items: array<int, array{product_id: int, variant_id?: int|null, quantity: int}>,
     *     payment_method: string,
     *     customer_notes?: string|null
     * } $data
     * @return array{order: Order, payment: array}
     * @throws ValidationException|Exception
     */
    public function placeOrder(array $data): array
    {
        // 1. Strict Server-Side Validation (Never trust client input)
        $this->validateOrderPayload($data);

        // 2. Normalize BD Phone Number (01XXXXXXXXX)
        $phone = Customer::normalizePhone($data['customer']['phone']);

        return DB::transaction(function () use ($data, $phone) {
            // Find or create Customer record
            $customer = Customer::firstOrCreate(
                ['phone' => $phone],
                [
                    'name' => trim($data['customer']['name']),
                    'email' => $data['customer']['email'] ?? null,
                    'status' => 'active',
                ]
            );

            // Verify Delivery Zone
            $deliveryZone = DeliveryZone::where('id', $data['shipping']['delivery_zone_id'])
                ->where('is_active', true)
                ->firstOrFail();

            // Save Shipping Address
            $address = Address::create([
                'customer_id' => $customer->id,
                'delivery_zone_id' => $deliveryZone->id,
                'recipient_name' => trim($data['shipping']['recipient_name']),
                'recipient_phone' => Customer::normalizePhone($data['shipping']['recipient_phone']),
                'division' => trim($data['shipping']['division']),
                'district' => trim($data['shipping']['district']),
                'upazila' => trim($data['shipping']['upazila']),
                'area' => $data['shipping']['area'] ?? null,
                'street_address' => trim($data['shipping']['street_address']),
                'landmark' => $data['shipping']['landmark'] ?? null,
                'postal_code' => $data['shipping']['postal_code'] ?? null,
                'is_default_shipping' => true,
            ]);

            // 3. Process Cart Items with atomic inventory lock (lockForUpdate)
            $subtotal = 0.00;
            $orderItemsData = [];

            foreach ($data['items'] as $item) {
                $requestedQty = (int) $item['quantity'];

                if ($requestedQty < 1) {
                    throw new Exception("Quantity must be at least 1.");
                }

                // Acquire pessimistic write lock on the product row
                /** @var Product $product */
                $product = Product::where('id', $item['product_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->firstOrFail();

                $unitPrice = (float) $product->selling_price;
                $unitCost = (float) $product->cost_price;
                $variantTitle = null;
                $sku = $product->sku;

                // Handle Variant if specified
                if (!empty($item['variant_id'])) {
                    /** @var ProductVariant $variant */
                    $variant = ProductVariant::where('id', $item['variant_id'])
                        ->where('product_id', $product->id)
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($variant->stock_quantity < $requestedQty) {
                        throw new Exception("Variant '{$variant->title}' for '{$product->name}' is out of stock (Available: {$variant->stock_quantity}).");
                    }

                    // Decrement variant stock
                    $variant->decrement('stock_quantity', $requestedQty);
                    $unitPrice = (float) $variant->selling_price;
                    $unitCost = (float) $variant->cost_price;
                    $variantTitle = $variant->title;
                    $sku = $variant->sku;
                } else {
                    // Check base product stock
                    if ($product->stock_quantity < $requestedQty) {
                        throw new Exception("Product '{$product->name}' is out of stock (Available: {$product->stock_quantity}).");
                    }
                }

                // Decrement master product stock
                $product->decrement('stock_quantity', $requestedQty);

                $itemSubtotal = $unitPrice * $requestedQty;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'supplier_id' => $product->supplier_id,
                    'product_name' => $product->name,
                    'variant_title' => $variantTitle,
                    'sku' => $sku,
                    'unit_cost' => $unitCost,
                    'unit_price' => $unitPrice,
                    'quantity' => $requestedQty,
                    'subtotal' => $itemSubtotal,
                    'dropship_status' => 'pending_supplier_dispatch',
                ];
            }

            $deliveryCharge = (float) $deliveryZone->base_charge;
            $discount = 0.00;
            $totalAmount = ($subtotal + $deliveryCharge) - $discount;

            // 4. Create Master Order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $customer->id,
                'customer_phone' => $phone,
                'shipping_address_id' => $address->id,
                'delivery_zone_id' => $deliveryZone->id,
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'discount_amount' => $discount,
                'total_amount' => $totalAmount,
                'payment_method' => $data['payment_method'] ?? 'cod',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'customer_notes' => $data['customer_notes'] ?? null,
            ]);

            // 5. Create Order Items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // 6. Record Status History Timeline
            $order->statusHistory()->create([
                'from_status' => null,
                'to_status' => 'pending',
                'comment' => 'Order placed online via Cash on Delivery.',
                'notified_customer' => true,
                'notification_channel' => 'sms',
            ]);

            // Update customer metrics
            $customer->increment('total_orders');
            $customer->increment('total_spent', $totalAmount);
            $customer->update(['last_ordered_at' => now()]);

            // 7. Initiate Payment through Gateway Abstraction
            $paymentResult = $this->paymentGateway->initiatePayment($order);

            return [
                'order' => $order->load(['items', 'shippingAddress', 'deliveryZone']),
                'payment' => $paymentResult,
            ];
        });
    }

    /**
     * Server-side input validation for Bangladesh phone, delivery zone, and address fields.
     */
    protected function validateOrderPayload(array $data): void
    {
        $validator = Validator::make($data, [
            'customer.name' => ['required', 'string', 'min:2', 'max:100'],
            'customer.phone' => ['required', 'string', 'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/'],
            'customer.email' => ['nullable', 'email', 'max:150'],
            
            'shipping.recipient_name' => ['required', 'string', 'min:2', 'max:100'],
            'shipping.recipient_phone' => ['required', 'string', 'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/'],
            'shipping.delivery_zone_id' => ['required', 'integer', 'exists:delivery_zones,id'],
            'shipping.division' => ['required', 'string', 'in:Dhaka,Chattogram,Rajshahi,Khulna,Barishal,Sylhet,Rangpur,Mymensingh'],
            'shipping.district' => ['required', 'string', 'max:80'],
            'shipping.upazila' => ['required', 'string', 'max:80'],
            'shipping.area' => ['nullable', 'string', 'max:100'],
            'shipping.street_address' => ['required', 'string', 'min:5', 'max:300'],
            'shipping.landmark' => ['nullable', 'string', 'max:150'],
            'shipping.postal_code' => ['nullable', 'string', 'max:10'],
            
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
            
            'payment_method' => ['required', 'string', 'in:cod,bkash,nagad'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer.phone.regex' => 'The phone number must be a valid 11-digit Bangladeshi mobile number (e.g., 01712345678).',
            'shipping.recipient_phone.regex' => 'Recipient phone must be a valid 11-digit Bangladeshi mobile number.',
            'shipping.division.in' => 'Please select a valid Bangladesh administrative division.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
