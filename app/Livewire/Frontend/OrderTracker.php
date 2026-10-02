<?php

namespace App\Livewire\Frontend;

use App\Models\Customer;
use App\Models\Order;
use Livewire\Component;

class OrderTracker extends Component
{
    public string $order_number = '';
    public string $phone = '';

    public ?Order $trackedOrder = null;
    public bool $hasSearched = false;

    protected function rules(): array
    {
        return [
            'order_number' => 'required|string|min:4|max:50',
            'phone' => ['required', 'string', 'regex:/^(?:\+?880|0)?1[3-9]\d{8}$/'],
        ];
    }

    protected $messages = [
        'order_number.required' => 'অর্ডার আইডি প্রদান করুন (Please enter Order ID e.g. CM-20261002-8821).',
        'phone.required' => 'বিলিং মোবাইল নম্বর প্রদান করুন (Please enter your mobile number).',
        'phone.regex' => 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (Valid 11-digit BD mobile required).',
    ];

    public function mount(): void
    {
        if (request()->has('order_number')) {
            $this->order_number = trim(request()->get('order_number'));
        }
        if (request()->has('phone')) {
            $this->phone = trim(request()->get('phone'));
            $this->trackOrder();
        }
    }

    /**
     * Fast Order Tracking using composite index: idx_orders_tracking_number_phone.
     */
    public function trackOrder(): void
    {
        $this->validate();
        $this->hasSearched = true;

        $normalizedPhone = Customer::normalizePhone($this->phone);

        $this->trackedOrder = Order::with([
            'items.product',
            'statusHistory',
            'shippingAddress',
            'deliveryZone',
            'payments'
        ])
        ->where('order_number', trim($this->order_number))
        ->where('customer_phone', $normalizedPhone)
        ->first();

        if (!$this->trackedOrder) {
            session()->flash('tracker_error', 'প্রদত্ত অর্ডার আইডি এবং মোবাইল নম্বরের সাথে কোনো অর্ডার মিল পাওয়া যায়নি। দয়া করে তথ্য যাচাই করুন।');
        }
    }

    public function resetSearch(): void
    {
        $this->trackedOrder = null;
        $this->hasSearched = false;
        $this->order_number = '';
        $this->phone = '';
    }

    public function render()
    {
        return view('livewire.frontend.order-tracker');
    }
}
