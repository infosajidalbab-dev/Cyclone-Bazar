<?php

namespace App\Livewire\Frontend;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Address;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    // Customer Details
    public string $name = '';
    public string $phone = '';
    public string $email = '';

    // Bangladesh Administrative Address
    public string $district = 'Dhaka';
    public string $division = 'Dhaka';
    public string $area = '';
    public string $street_address = '';
    public string $landmark = '';
    public string $delivery_note = '';

    // Delivery Zone & Charges
    public int $delivery_zone_id = 1;
    public float $delivery_charge = 60.00;

    // Payment Selection
    public string $payment_method = 'cod';

    // UI Loading state
    public bool $isSubmitting = false;

    // 64 Bangladesh Districts
    public array $districts = [
        'Dhaka' => 'Dhaka Division',
        'Gazipur' => 'Dhaka Division',
        'Narayanganj' => 'Dhaka Division',
        'Chattogram' => 'Chattogram Division',
        'Cox\'s Bazar' => 'Chattogram Division',
        'Cumilla' => 'Chattogram Division',
        'Sylhet' => 'Sylhet Division',
        'Rajshahi' => 'Rajshahi Division',
        'Bogura' => 'Rajshahi Division',
        'Khulna' => 'Khulna Division',
        'Jashore' => 'Khulna Division',
        'Barishal' => 'Barishal Division',
        'Rangpur' => 'Rangpur Division',
        'Mymensingh' => 'Mymensingh Division',
        'Tangail' => 'Dhaka Division',
        'Faridpur' => 'Dhaka Division',
        'Feni' => 'Chattogram Division',
        'Noakhali' => 'Chattogram Division',
        'Pabna' => 'Rajshahi Division',
        'Kushtia' => 'Khulna Division',
        'Dinajpur' => 'Rangpur Division',
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'phone' => ['required', 'string', 'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/'],
            'district' => 'required|string',
            'area' => 'required|string|min:2|max:100',
            'street_address' => 'required|string|min:5|max:300',
            'landmark' => 'nullable|string|max:150',
            'delivery_note' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,bkash,nagad',
        ];
    }

    protected $messages = [
        'name.required' => 'আপনার সম্পূর্ণ নাম লিখুন (Please enter your name).',
        'phone.required' => 'মোবাইল নম্বর প্রদান করুন (Please enter mobile number).',
        'phone.regex' => 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন যেমন: 017XXXXXXXX (Valid 11-digit BD phone required).',
        'district.required' => 'জেলা নির্বাচন করুন (Please select district).',
        'area.required' => 'এলাকা বা থানার নাম লিখুন (Please enter your area/thana).',
        'street_address.required' => 'বাড়ি ও রাস্তার সম্পূর্ণ ঠিকানা লিখুন (Full street address required).',
    ];

    public function mount(CartService $cartService): void
    {
        if ($cartService->isEmpty()) {
            return;
        }

        $this->updateDeliveryCharge();
    }

    /**
     * Dynamic delivery charge update on district change.
     * Inside Dhaka: ৳60 | Outside Dhaka: ৳120
     */
    public function updatedDistrict(): void
    {
        $this->updateDeliveryCharge();
    }

    public function updateDeliveryCharge(): void
    {
        if (trim(strtolower($this->district)) === 'dhaka') {
            $zone = DeliveryZone::where('code', 'DHAKA_INSIDE')->first();
            $this->delivery_charge = 60.00;
            $this->delivery_zone_id = $zone ? $zone->id : 1;
            $this->division = 'Dhaka';
        } else {
            $zone = DeliveryZone::where('code', 'OUTSIDE_DHAKA')->first();
            $this->delivery_charge = 120.00;
            $this->delivery_zone_id = $zone ? $zone->id : 3;
            $this->division = $this->districts[$this->district] ?? 'Outside Dhaka';
        }
    }

    /**
     * Atomic DB::transaction with pessimistic lockForUpdate() to process order.
     */
    public function placeOrder(CartService $cartService, PaymentGatewayInterface $paymentGateway)
    {
        $this->validate();

        $cartItems = $cartService->getItems();
        if (empty($cartItems)) {
            session()->flash('checkout_error', 'আপনার কার্ট খালি। অনুগ্রহ করে পণ্য যোগ করুন।');
            return;
        }

        $this->isSubmitting = true;
        $normalizedPhone = Customer::normalizePhone($this->phone);

        try {
            $createdOrder = DB::transaction(function () use ($cartItems, $cartService, $paymentGateway, $normalizedPhone) {
                // 1. Pessimistic Row-Locking on all cart items
                $subtotal = 0.00;
                $processedItems = [];

                foreach ($cartItems as $item) {
                    $requestedQty = (int) $item['quantity'];

                    // Lock master product row
                    /** @var Product $product */
                    $product = Product::where('id', $item['product_id'])
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $unitPrice = (float) $product->selling_price;
                    $unitCost = (float) $product->cost_price;
                    $variantTitle = null;
                    $sku = $product->sku;

                    // If variant item, lock variant row
                    if (!empty($item['variant_id'])) {
                        /** @var ProductVariant $variant */
                        $variant = ProductVariant::where('id', $item['variant_id'])
                            ->where('product_id', $product->id)
                            ->where('is_active', true)
                            ->lockForUpdate()
                            ->firstOrFail();

                        if ($variant->stock_quantity < $requestedQty) {
                            throw new Exception("দুঃখিত, '{$product->name} ({$variant->title})' পর্যাপ্ত স্টকে নেই। বর্তমান স্টক: {$variant->stock_quantity} টি।");
                        }

                        $variant->decrement('stock_quantity', $requestedQty);
                        $unitPrice = (float) $variant->selling_price;
                        $unitCost = (float) $variant->cost_price;
                        $variantTitle = $variant->title;
                        $sku = $variant->sku;
                    } else {
                        if ($product->stock_quantity < $requestedQty) {
                            throw new Exception("দুঃখিত, '{$product->name}' পর্যাপ্ত স্টকে নেই। বর্তমান স্টক: {$product->stock_quantity} টি।");
                        }
                    }

                    // Decrement master product stock
                    $product->decrement('stock_quantity', $requestedQty);

                    $itemSubtotal = $unitPrice * $requestedQty;
                    $subtotal += $itemSubtotal;

                    $processedItems[] = [
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

                // 2. Customer Registry (FirstOrCreate by BD Phone)
                $customer = Customer::firstOrCreate(
                    ['phone' => $normalizedPhone],
                    [
                        'name' => trim($this->name),
                        'email' => $this->email ?: null,
                        'status' => 'active',
                    ]
                );

                // 3. Shipping Address
                $address = Address::create([
                    'customer_id' => $customer->id,
                    'delivery_zone_id' => $this->delivery_zone_id,
                    'recipient_name' => trim($this->name),
                    'recipient_phone' => $normalizedPhone,
                    'division' => $this->division,
                    'district' => trim($this->district),
                    'upazila' => trim($this->area),
                    'area' => trim($this->area),
                    'street_address' => trim($this->street_address),
                    'landmark' => $this->landmark ?: null,
                    'is_default_shipping' => true,
                ]);

                // 4. Create Master Order
                $totalAmount = $subtotal + $this->delivery_charge;
                $orderNumber = Order::generateOrderNumber();

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_id' => $customer->id,
                    'customer_phone' => $normalizedPhone,
                    'shipping_address_id' => $address->id,
                    'delivery_zone_id' => $this->delivery_zone_id,
                    'subtotal' => $subtotal,
                    'delivery_charge' => $this->delivery_charge,
                    'discount_amount' => 0.00,
                    'total_amount' => $totalAmount,
                    'payment_method' => $this->payment_method,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'customer_notes' => $this->delivery_note ?: null,
                ]);

                // 5. Create Order Items
                foreach ($processedItems as $itemData) {
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

                // Update customer lifetime stats
                $customer->increment('total_orders');
                $customer->increment('total_spent', $totalAmount);
                $customer->update(['last_ordered_at' => now()]);

                // 7. Initialize COD Gateway
                $paymentGateway->initiatePayment($order);

                // 8. Empty the cart
                $cartService->clear();

                return $order;
            });

            // Redirect to Order Confirmation page
            return redirect()->to('/order-confirmation/' . $createdOrder->order_number);

        } catch (Exception $e) {
            $this->isSubmitting = false;
            session()->flash('checkout_error', $e->getMessage());
        }
    }

    public function render(CartService $cartService)
    {
        $items = $cartService->getItems();
        $subtotal = $cartService->getSubtotal();
        $totalAmount = $subtotal + $this->delivery_charge;

        return view('livewire.frontend.checkout', [
            'items' => $cartItems = $items,
            'subtotal' => $subtotal,
            'totalAmount' => $totalAmount,
            'itemCount' => $cartService->getItemCount(),
        ]);
    }
}
