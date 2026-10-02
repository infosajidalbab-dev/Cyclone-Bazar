<?php

namespace App\Livewire\Admin;

use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Services\Payment\CodGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public string $statusFilter = 'all';
    public string $paymentFilter = 'all';
    public string $zoneFilter = 'all';

    // Selected Order for Modal / Drawer
    public ?int $selectedOrderId = null;
    public ?Order $activeOrder = null;

    // Status update modal state
    public string $newStatus = '';
    public string $statusComment = '';
    public bool $notifyCustomer = true;

    // Courier Assignment state
    public string $courierName = 'Steadfast Courier';
    public string $trackingCode = '';
    public string $consignmentId = '';

    // Payment collection state
    public float $collectedAmount = 0.00;
    public string $riderName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'paymentFilter' => ['except' => 'all'],
        'zoneFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function selectOrder(int $orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->activeOrder = Order::with([
            'customer',
            'shippingAddress',
            'deliveryZone',
            'items.product',
            'items.supplier',
            'statusHistory.user',
            'payments'
        ])->findOrFail($orderId);

        $this->newStatus = $this->activeOrder->order_status;
        $this->courierName = $this->activeOrder->courier_name ?? 'Steadfast Courier';
        $this->trackingCode = $this->activeOrder->courier_tracking_code ?? '';
        $this->consignmentId = $this->activeOrder->courier_consignment_id ?? '';
        $this->collectedAmount = (float) $this->activeOrder->total_amount;
    }

    public function closeDrawer(): void
    {
        $this->selectedOrderId = null;
        $this->activeOrder = null;
    }

    /**
     * Update order status with audit trail and customer notification.
     */
    public function updateStatus(string $targetStatus): void
    {
        if (!$this->selectedOrderId) return;

        DB::transaction(function () use ($targetStatus) {
            $order = Order::findOrFail($this->selectedOrderId);
            $userId = Auth::id() ?? 1; // Current admin ID

            $order->addStatusHistory(
                toStatus: $targetStatus,
                comment: $this->statusComment ?: "Status updated to " . strtoupper($targetStatus) . " by admin.",
                userId: $userId,
                notified: $this->notifyCustomer,
                channel: 'sms'
            );

            if ($targetStatus === 'delivered') {
                $order->update(['delivered_at' => now()]);
            } elseif ($targetStatus === 'cancelled') {
                $order->update(['cancelled_at' => now()]);
            }

            $this->statusComment = '';
            $this->selectOrder($this->selectedOrderId);
            session()->flash('status', "Order #{$order->order_number} status updated to " . strtoupper($targetStatus));
        });
    }

    /**
     * Assign courier consignment details.
     */
    public function assignCourier(): void
    {
        if (!$this->selectedOrderId) return;

        $this->validate([
            'courierName' => 'required|string',
            'trackingCode' => 'required|string|min:4',
        ]);

        $order = Order::findOrFail($this->selectedOrderId);
        $order->update([
            'courier_name' => $this->courierName,
            'courier_tracking_code' => trim($this->trackingCode),
            'courier_consignment_id' => trim($this->consignmentId) ?: trim($this->trackingCode),
            'order_status' => 'handed_over_to_courier',
            'dispatched_at' => now(),
        ]);

        $order->addStatusHistory(
            toStatus: 'handed_over_to_courier',
            comment: "Handed over to {$this->courierName}. Tracking: {$this->trackingCode}",
            userId: Auth::id() ?? 1,
            notified: true,
            channel: 'sms'
        );

        $this->selectOrder($this->selectedOrderId);
        session()->flash('status', "Courier tracking saved and customer notified via SMS.");
    }

    /**
     * Verify doorstep Cash on Delivery collection via CodGateway.
     */
    public function markCodPaid(): void
    {
        if (!$this->selectedOrderId) return;

        $order = Order::findOrFail($this->selectedOrderId);
        $codGateway = app(CodGateway::class);

        $result = $codGateway->verifyPayment($order, [
            'collected_amount' => $this->collectedAmount,
            'rider_name' => $this->riderName ?: 'Courier Agent',
            'consignment_id' => $this->trackingCode,
        ]);

        if ($result['success']) {
            $this->selectOrder($this->selectedOrderId);
            session()->flash('status', 'COD Payment verified: ৳' . number_format($this->collectedAmount, 2) . ' cash reconciled.');
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function render()
    {
        $query = Order::with(['customer', 'deliveryZone', 'items'])
            ->latest('created_at');

        // Search by order_number or phone using index
        if (!empty($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('order_status', $this->statusFilter);
        }

        if ($this->paymentFilter !== 'all') {
            $query->where('payment_status', $this->paymentFilter);
        }

        if ($this->zoneFilter !== 'all') {
            $query->where('delivery_zone_id', $this->zoneFilter);
        }

        $orders = $query->paginate(15);
        $zones = DeliveryZone::where('is_active', true)->get();

        // High-level statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::whereIn('order_status', ['confirmed', 'processing', 'handed_over_to_courier'])->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cod_due' => Order::where('payment_method', 'cod')->where('payment_status', 'pending')->sum('total_amount'),
        ];

        return view('livewire.admin.order-manager', [
            'orders' => $orders,
            'zones' => $zones,
            'stats' => $stats,
        ]);
    }
}
