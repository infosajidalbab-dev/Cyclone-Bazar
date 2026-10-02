<?php

namespace App\Livewire\Frontend;

use App\Models\Address;
use App\Models\Customer;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickCheckoutModal extends Component
{
    // Modal State
    public bool $isOpen = false;
    public bool $isSubmitting = false;

    // Selected Product & Variant
    public ?int $productId = null;
    public ?Product $product = null;
    public ?int $selectedVariantId = null;
    public int $quantity = 1;

    // Form Fields (Customer & Shipping)
    public string $name = '';
    public string $phone = '';
    public string $district = 'Dhaka'; // Default inside Dhaka
    public string $address = '';
    public string $delivery_note = '';
    public string $payment_method = 'cod';

    // Pricing Breakdown
    public float $unitPrice = 0.00;
    public float $subtotal = 0.00;
    public float $deliveryCharge = 60.00;
    public float $totalAmount = 0.00;

    /**
     * Server-side Validation Rules
     * Enforces strict 11-digit Bangladesh phone numbers (013-019)
     */
    protected function rules(): array
    {
        return [
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'name' => 'required|string|min:3|max:100',
            'phone' => [
                'required',
                'string',
                'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/',
            ],
            'district' => 'required|string|max:50',
            'address' => 'required|string|min:8|max:255',
            'delivery_note' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'name.required' => 'অনুগ্রহ করে আপনার পুরো নাম লিখুন।',
        'name.min' => 'নাম কমপক্ষে ৩ অক্ষরের হতে হবে।',
        'phone.required' => 'আপনার সচল মোবাইল নম্বরটি লিখুন।',
        'phone.regex' => 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01712345678)।',
        'district.required' => 'আপনার জেলা নির্বাচন করুন।',
        'address.required' => 'ডেলিভারির জন্য আপনার সম্পূর্ণ ঠিকানা লিখুন (বাসা/রোড/এলাকা)।',
        'address.min' => 'ঠিকানাটি বিস্তারিত লিখুন যাতে ডেলিভারিম্যান সহজেই পৌঁছাতে পারে।',
    ];

    /**
     * Listen for quick checkout event from any product card or PDP
     */
    #[On('open-quick-checkout')]
    public function openModal(int $productId, ?int $variantId = null): void
    {
        $this->resetValidation();
        $this->productId = $productId;
        $this->product = Product::with(['primaryImage', 'variants'])->findOrFail($productId);
        $this->quantity = 1;

        if ($variantId && $this->product->variants->contains('id', $variantId)) {
            $this->selectedVariantId = $variantId;
            $variant = $this->product->variants->find($variantId);
            $this->unitPrice = (float) $variant->selling_price;
        } else {
            $firstVariant = $this->product->variants->first();
            $this->selectedVariantId = $firstVariant ? $firstVariant->id : null;
            $this->unitPrice = $firstVariant ? (float) $firstVariant->selling_price : (float) $this->product->selling_price;
        }

        $this->recalculateTotals();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < 10) {
            $this->quantity++;
            $this->recalculateTotals();
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->recalculateTotals();
        }
    }

    /**
     * Dynamically update delivery charges when district changes
     * Inside Dhaka: ৳60 | Outside Dhaka: ৳120
     */
    public function updatedDistrict(): void
    {
        $isDhaka = in_array(strtolower(trim($this->district)), ['dhaka', 'ঢাকা', 'dhaka city', 'inside dhaka']);
        
        $zoneCode = $isDhaka ? 'DHAKA_INSIDE' : 'OUTSIDE_DHAKA';
        $zone = DeliveryZone::where('code', $zoneCode)->first();
        
        $this->deliveryCharge = $zone ? (float) $zone->base_charge : ($isDhaka ? 60.00 : 120.00);
        $this->recalculateTotals();
    }

    public function updatedSelectedVariantId(): void
    {
        if ($this->selectedVariantId && $this->product) {
            $variant = $this->product->variants->find($this->selectedVariantId);
            if ($variant) {
                $this->unitPrice = (float) $variant->selling_price;
                $this->recalculateTotals();
            }
        }
    }

    private function recalculateTotals(): void
    {
        $this->subtotal = $this->unitPrice * $this->quantity;
        $this->totalAmount = $this->subtotal + $this->deliveryCharge;
    }

    /**
     * Atomic Cash on Delivery Order Confirmation with Pessimistic Locking
     */
    public function confirmOrder()
    {
        $this->validate();
        $this->isSubmitting = true;

        // Clean & Normalize Phone to 11 digits: 01XXXXXXXXX
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($cleanPhone, '880')) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        try {
            $order = DB::transaction(function () use ($cleanPhone) {
                // 1. Pessimistic lock on product to prevent overselling & race conditions
                $lockedProduct = Product::where('id', $this->productId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedVariant = null;
                if ($this->selectedVariantId) {
                    $lockedVariant = ProductVariant::where('id', $this->selectedVariantId)
                        ->lockForUpdate()
                        ->first();
                }

                // Verify stock availability
                $availableStock = $lockedVariant ? $lockedVariant->stock_quantity : $lockedProduct->stock_quantity;
                if ($availableStock < $this->quantity) {
                    throw new \Exception("দুঃখিত, এই পণ্যটির পর্যাপ্ত স্টক নেই। বর্তমান স্টক: {$availableStock} টি।");
                }

                // 2. Find or create customer by phone
                $customer = Customer::firstOrCreate(
                    ['phone' => $cleanPhone],
                    [
                        'name' => trim($this->name),
                        'is_active' => true,
                    ]
                );

                // 3. Resolve Delivery Zone
                $isDhaka = in_array(strtolower(trim($this->district)), ['dhaka', 'ঢাকা', 'dhaka city']);
                $zone = DeliveryZone::where('code', $isDhaka ? 'DHAKA_INSIDE' : 'OUTSIDE_DHAKA')->first();
                $zoneId = $zone ? $zone->id : null;

                // 4. Create Shipping Address
                $shippingAddress = Address::create([
                    'customer_id' => $customer->id,
                    'division' => $isDhaka ? 'Dhaka' : 'Other',
                    'district' => trim($this->district),
                    'upazila' => trim($this->district),
                    'area' => 'Local Area',
                    'address_line_1' => trim($this->address),
                    'recipient_name' => trim($this->name),
                    'recipient_phone' => $cleanPhone,
                    'delivery_instructions' => $this->delivery_note ? trim($this->delivery_note) : null,
                ]);

                // 5. Generate Unique Order Number: CM-YYYYMMDD-XXXX
                $datePrefix = date('Ymd');
                $randomSuffix = strtoupper(Str::random(4));
                $orderNumber = "CM-{$datePrefix}-{$randomSuffix}";

                // 6. Create Order record
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'customer_id' => $customer->id,
                    'customer_phone' => $cleanPhone,
                    'shipping_address_id' => $shippingAddress->id,
                    'delivery_zone_id' => $zoneId,
                    'subtotal' => $this->subtotal,
                    'delivery_charge' => $this->deliveryCharge,
                    'discount_amount' => 0.00,
                    'total_amount' => $this->totalAmount,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'customer_note' => $this->delivery_note ? trim($this->delivery_note) : null,
                ]);

                // 7. Create Order Item
                $unitCost = $lockedVariant ? (float) $lockedVariant->cost_price : (float) $lockedProduct->cost_price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $lockedProduct->id,
                    'product_variant_id' => $lockedVariant ? $lockedVariant->id : null,
                    'product_name' => $lockedProduct->name,
                    'variant_title' => $lockedVariant ? $lockedVariant->title : null,
                    'sku' => $lockedVariant ? $lockedVariant->sku : $lockedProduct->sku,
                    'unit_price' => $this->unitPrice,
                    'unit_cost' => $unitCost,
                    'quantity' => $this->quantity,
                    'total_price' => $this->subtotal,
                    'dropship_status' => 'pending',
                ]);

                // 8. Deduct stock safely
                if ($lockedVariant) {
                    $lockedVariant->decrement('stock_quantity', $this->quantity);
                }
                $lockedProduct->decrement('stock_quantity', $this->quantity);

                // 9. Record Initial Status History
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'comment' => 'গ্রাহক এক্সপ্রেস কুইক চেকআউট (COD) এর মাধ্যমে সফলভাবে অর্ডার সাবমিট করেছেন।',
                ]);

                // 10. Create Pending Payment
                Payment::create([
                    'order_id' => $order->id,
                    'payment_gateway' => 'cod',
                    'transaction_id' => 'COD-' . strtoupper(Str::random(8)),
                    'amount' => $this->totalAmount,
                    'currency' => 'BDT',
                    'status' => 'pending',
                ]);

                return $order;
            });

            $this->isOpen = false;
            $this->isSubmitting = false;

            // Flash success & Redirect to confirmation
            session()->flash('order_success', "আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে! অর্ডার নম্বর: {$order->order_number}");
            return redirect()->route('order.confirmation', ['order_number' => $order->order_number]);

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            $this->addError('order_error', $e->getMessage() ?: 'অর্ডার প্রক্রিয়াকরণে একটি সমস্যা হয়েছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
        }
    }

    public function render()
    {
        return view('livewire.frontend.quick-checkout-modal');
    }
}
