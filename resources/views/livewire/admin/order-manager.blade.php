<div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Order Management & Fulfillment</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track Bangladesh dropship orders, courier assignments, and Cash on Delivery collections.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-mono text-slate-500">Live Orders Engine</span>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Pending Verification</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-bold font-mono text-[#FF6B35]">{{ $stats['pending'] }}</span>
                <span class="text-[10px] text-slate-400">Requires Phone Confirmation</span>
            </div>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">In Fulfillment / Courier</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-bold font-mono text-[#0F4C81]">{{ $stats['processing'] }}</span>
                <span class="text-[10px] text-slate-400">At Hub or In Transit</span>
            </div>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Delivered Orders</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-2xl font-bold font-mono text-[#28A745]">{{ $stats['delivered'] }}</span>
                <span class="text-[10px] text-slate-400">Successfully Completed</span>
            </div>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Pending COD Collection</span>
            <div class="flex items-baseline justify-between mt-1">
                <span class="text-xl font-bold font-mono text-slate-900">৳{{ number_format($stats['cod_due'], 0) }}</span>
                <span class="text-[10px] text-slate-400">With Couriers</span>
            </div>
        </div>
    </div>

    {{-- Notification flash --}}
    @if (session()->has('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-[#28A745] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Filter Toolbar --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs space-y-3">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by Order ID (CM-...) or Customer Phone (01XXXXXXXXX)..." 
                    class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-1 focus:ring-[#0F4C81]"
                />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="statusFilter" class="text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]">
                    <option value="all">All Order Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="handed_over_to_courier">Handed to Courier</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="returned">Returned</option>
                </select>

                <select wire:model.live="paymentFilter" class="text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]">
                    <option value="all">All Payments</option>
                    <option value="pending">Pending Payment</option>
                    <option value="paid">Paid (COD Collected)</option>
                    <option value="refunded">Refunded</option>
                </select>

                <select wire:model.live="zoneFilter" class="text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]">
                    <option value="all">All Delivery Zones</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                    <tr>
                        <th class="py-3 px-4">Order ID & Date</th>
                        <th class="py-3 px-4">Customer Details</th>
                        <th class="py-3 px-4">Delivery Zone</th>
                        <th class="py-3 px-4">Total (BDT)</th>
                        <th class="py-3 px-4">Payment</th>
                        <th class="py-3 px-4">Order Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-slate-900 block">{{ $order->order_number }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800 block">{{ $order->customer->name ?? 'Guest User' }}</span>
                                <span class="text-[11px] font-mono text-[#0F4C81]">{{ $order->customer_phone }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-slate-700 block">{{ $order->deliveryZone->name ?? 'Inside Dhaka' }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">Charge: ৳{{ number_format($order->delivery_charge, 2) }}</span>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                ৳{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded font-medium {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ strtoupper($order->payment_method) }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'handed_over_to_courier' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'in_transit' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'delivered' => 'bg-emerald-50 text-[#28A745] border-emerald-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'returned' => 'bg-slate-100 text-slate-700 border-slate-300',
                                    ];
                                @endphp
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full border {{ $statusClasses[$order->order_status] ?? 'bg-slate-50 text-slate-700' }}">
                                    {{ strtoupper(str_replace('_', ' ', $order->order_status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button 
                                    type="button" 
                                    wire:click="selectOrder({{ $order->id }})" 
                                    class="px-3 py-1.5 min-h-[44px] text-xs font-semibold text-[#0F4C81] bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors inline-flex items-center gap-1"
                                >
                                    <span>Manage</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                No orders matching the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Order Detail Drawer / Modal --}}
    @if ($activeOrder)
        <div class="fixed inset-0 z-50 overflow-hidden bg-black/50 backdrop-blur-xs flex justify-end">
            <div class="w-full max-w-2xl bg-white h-full shadow-2xl overflow-y-auto flex flex-col">
                {{-- Drawer Header --}}
                <div class="p-5 border-b border-slate-200 flex items-center justify-between sticky top-0 bg-white z-10">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold font-mono text-slate-900">{{ $activeOrder->order_number }}</h2>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                                {{ strtoupper(str_replace('_', ' ', $activeOrder->order_status)) }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-500 font-mono">Placed on {{ $activeOrder->created_at->format('d M Y, h:i A') }}</span>
                    </div>

                    <button type="button" wire:click="closeDrawer" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg min-h-[44px] min-w-[44px] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Drawer Body --}}
                <div class="p-6 space-y-6 flex-1">
                    {{-- Status Progression Controls --}}
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                        <span class="text-xs font-semibold text-slate-800 block">Update Order Status</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach (['pending', 'confirmed', 'processing', 'handed_over_to_courier', 'delivered', 'cancelled'] as $st)
                                <button 
                                    type="button" 
                                    wire:click="updateStatus('{{ $st }}')"
                                    class="px-2.5 py-1.5 text-[11px] font-semibold rounded-lg transition-colors min-h-[36px] {{ $activeOrder->order_status === $st ? 'bg-[#0F4C81] text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Customer & Address (BD 4-tier Hierarchy) --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-2">
                        <h4 class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Customer & Delivery Address</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 text-[11px] block">Customer Name</span>
                                <span class="font-semibold text-slate-800">{{ $activeOrder->customer->name ?? 'Guest' }}</span>
                                <span class="text-[11px] font-mono text-[#0F4C81] block mt-0.5">Phone: {{ $activeOrder->customer_phone }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 text-[11px] block">Delivery Hierarchy</span>
                                <span class="font-medium text-slate-800 block">
                                    {{ $activeOrder->shippingAddress?->street_address }}
                                </span>
                                <span class="text-slate-600 text-[11px]">
                                    {{ $activeOrder->shippingAddress?->upazila }}, {{ $activeOrder->shippingAddress?->district }}, {{ $activeOrder->shippingAddress?->division }}
                                </span>
                                @if ($activeOrder->shippingAddress?->landmark)
                                    <span class="text-[11px] text-amber-700 block mt-0.5">Landmark: {{ $activeOrder->shippingAddress->landmark }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Courier Assignment (Steadfast / Pathao) --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Courier Dispatch (Steadfast / Pathao)</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Courier Partner</label>
                                <select wire:model="courierName" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg">
                                    <option value="Steadfast Courier">Steadfast Courier</option>
                                    <option value="Pathao Courier">Pathao Courier</option>
                                    <option value="RedX Logistics">RedX Logistics</option>
                                    <option value="eCourier">eCourier BD</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-600 mb-1">Consignment / Tracking ID</label>
                                <input type="text" wire:model="trackingCode" placeholder="e.g. STD-882194" class="w-full p-2 font-mono bg-slate-50 border border-slate-200 rounded-lg" />
                            </div>
                        </div>
                        <button 
                            type="button" 
                            wire:click="assignCourier" 
                            class="px-4 py-2 bg-[#0F4C81] text-white rounded-lg text-xs font-semibold hover:bg-[#0A355C] min-h-[44px]"
                        >
                            Save Courier & Notify Customer via SMS
                        </button>
                    </div>

                    {{-- Doorstep COD Cash Reconciliation --}}
                    @if ($activeOrder->payment_status !== 'paid')
                        <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl space-y-2">
                            <h4 class="text-xs font-bold text-orange-900">Reconcile Cash on Delivery (COD)</h4>
                            <p class="text-[11px] text-orange-800">Verify cash handed over by delivery rider upon doorstep parcel inspection.</p>
                            <div class="flex items-center gap-3">
                                <input type="text" wire:model="riderName" placeholder="Rider Name / Hub Code" class="flex-1 p-2 bg-white border border-orange-300 rounded text-xs" />
                                <button 
                                    type="button" 
                                    wire:click="markCodPaid" 
                                    class="px-4 py-2 bg-[#28A745] hover:bg-emerald-700 text-white rounded text-xs font-bold min-h-[44px]"
                                >
                                    Confirm ৳{{ number_format($activeOrder->total_amount, 2) }} Paid
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Ordered Line Items --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Ordered Items ({{ $activeOrder->items->count() }})</h4>
                        <div class="divide-y divide-slate-100 text-xs">
                            @foreach ($activeOrder->items as $item)
                                <div class="py-2.5 flex items-center justify-between">
                                    <div>
                                        <span class="font-semibold text-slate-800 block">{{ $item->product_name }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">SKU: {{ $item->sku }} · Supplier: {{ $item->supplier->name ?? 'Hub' }}</span>
                                    </div>
                                    <div class="text-right font-mono">
                                        <span class="text-slate-800">{{ $item->quantity }} x ৳{{ number_format($item->unit_price, 2) }}</span>
                                        <span class="font-bold text-slate-900 block">৳{{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex justify-between font-mono text-xs">
                            <span class="text-slate-500">Delivery Charge:</span>
                            <span>৳{{ number_format($activeOrder->delivery_charge, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-mono text-sm font-bold text-slate-900 pt-1">
                            <span>Total Payable (BDT):</span>
                            <span class="text-[#0F4C81]">৳{{ number_format($activeOrder->total_amount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Status History Audit Trail --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-3">
                        <h4 class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Audit Timeline (order_status_history)</h4>
                        <div class="space-y-3 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                            @foreach ($activeOrder->statusHistory as $hist)
                                <div class="relative pl-7 text-xs">
                                    <span class="absolute left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#0F4C81] border-2 border-white ring-1 ring-slate-200"></span>
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-800">{{ strtoupper($hist->to_status) }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $hist->created_at->format('d M, h:i A') }}</span>
                                    </div>
                                    <p class="text-slate-600 text-[11px] mt-0.5 bg-slate-50 p-2 rounded border border-slate-100 font-mono">
                                        {{ $hist->comment }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
