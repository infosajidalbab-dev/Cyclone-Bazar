<div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">এডমিন ড্যাশবোর্ড (Cyclone Mart Ops)</h1>
            <p class="text-xs text-slate-500 mt-0.5">রিয়েল-টাইম সেলস, ইনভেন্টরি অ্যালার্ট এবং সাম্প্রতিক অর্ডার মনিটরিং।</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-lg text-xs font-mono">
                <button wire:click="$set('chartRange', '7days')" class="px-2.5 py-1 rounded-md {{ $chartRange === '7days' ? 'bg-white font-bold text-[#0F4C81] shadow-xs' : 'text-slate-600' }}">7 Days</button>
                <button wire:click="$set('chartRange', '30days')" class="px-2.5 py-1 rounded-md {{ $chartRange === '30days' ? 'bg-white font-bold text-[#0F4C81] shadow-xs' : 'text-slate-600' }}">30 Days</button>
            </div>
            <a href="/admin/orders" class="px-3.5 py-2 text-xs font-bold text-white bg-[#0F4C81] hover:bg-[#0A355C] rounded-lg min-h-[44px] flex items-center gap-1.5 shadow-xs">
                <span>Manage Orders</span>
            </a>
        </div>
    </div>

    {{-- 6 Primary Metric Cards (Section 9.1 Spec) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">আজকের সেলস</span>
            <span class="text-xl font-extrabold font-mono text-[#0F4C81] block mt-1">৳{{ number_format($metrics['todaySales'], 0) }}</span>
            <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">Today's Revenue</span>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">আজকের অর্ডার</span>
            <span class="text-xl font-extrabold font-mono text-slate-900 block mt-1">{{ $metrics['todayOrders'] }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Today's Volume</span>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">মোট সেলস</span>
            <span class="text-xl font-extrabold font-mono text-[#28A745] block mt-1">৳{{ number_format($metrics['totalSales'], 0) }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Lifetime Revenue</span>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">মোট অর্ডার</span>
            <span class="text-xl font-extrabold font-mono text-slate-900 block mt-1">{{ $metrics['totalOrders'] }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">All Time</span>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">গ্রাহক সংখ্যা</span>
            <span class="text-xl font-extrabold font-mono text-slate-900 block mt-1">{{ $metrics['totalCustomers'] }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Registered & Guests</span>
        </div>

        <div class="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs {{ $metrics['lowStockCount'] > 0 ? 'bg-rose-50/40 border-rose-200' : '' }}">
            <span class="text-[10px] font-bold {{ $metrics['lowStockCount'] > 0 ? 'text-rose-700' : 'text-slate-400' }} uppercase tracking-wider block font-mono">লো স্টক অ্যালার্ট</span>
            <span class="text-xl font-extrabold font-mono {{ $metrics['lowStockCount'] > 0 ? 'text-rose-600' : 'text-slate-900' }} block mt-1">{{ $metrics['lowStockCount'] }}</span>
            <span class="text-[10px] text-slate-500 mt-0.5 block">Restock Needed</span>
        </div>
    </div>

    {{-- Visual Sales Trend Chart --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-sm font-bold text-slate-900">দৈনিক সেলস গ্রাফ (Daily Sales Trend - BDT)</h3>
                <span class="text-xs text-slate-500">পূর্ববর্তী {{ count($chartLabels) }} দিনের আয় এবং ক্যাশ অন ডেলিভারি প্রবাহ।</span>
            </div>
            <span class="text-xs font-mono font-semibold text-[#0F4C81] bg-blue-50 px-2 py-1 rounded">
                Avg: ৳{{ count($chartValues) > 0 ? number_format(array_sum($chartValues) / count($chartValues), 0) : 0 }} / Day
            </span>
        </div>

        {{-- SVG Responsive Bar/Sparkline Chart --}}
        <div class="h-44 sm:h-52 w-full flex items-end gap-2 pt-6 pb-2 px-2 border-b border-slate-100">
            @php
                $maxVal = max(max($chartValues), 1000);
            @endphp
            @foreach ($chartValues as $i => $val)
                @php
                    $heightPct = min(100, max(8, round(($val / $maxVal) * 100)));
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group relative h-full justify-end">
                    {{-- Tooltip hover --}}
                    <div class="absolute -top-7 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white font-mono text-[10px] py-0.5 px-1.5 rounded pointer-events-none whitespace-nowrap z-10 shadow-md">
                        ৳{{ number_format($val, 0) }}
                    </div>
                    <div 
                        class="w-full bg-[#0F4C81] group-hover:bg-[#FF6B35] rounded-t-lg transition-all"
                        style="height: {{ $heightPct }}%;"
                    ></div>
                    <span class="text-[10px] text-slate-400 font-mono truncate w-full text-center">{{ $chartLabels[$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bottom Grid: Recent Orders & Low Stock Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Recent Orders (Last 10) --}}
        <div class="lg:col-span-7 bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">সাম্প্রতিক অর্ডার (Recent 10 Orders)</h3>
                <a href="/admin/orders" class="text-xs font-semibold text-[#0F4C81] hover:underline">সবগুলো দেখুন →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-2.5 px-3">Order ID</th>
                            <th class="py-2.5 px-3">Customer</th>
                            <th class="py-2.5 px-3">Amount</th>
                            <th class="py-2.5 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentOrders as $order)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-2.5 px-3 font-mono font-bold text-slate-800">
                                    <a href="/admin/orders?search={{ $order->order_number }}" class="text-[#0F4C81] hover:underline">{{ $order->order_number }}</a>
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="font-medium text-slate-800 block">{{ $order->customer->name ?? 'Guest' }}</span>
                                    <span class="text-[10px] font-mono text-slate-400">{{ $order->customer_phone }}</span>
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-slate-900">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="py-2.5 px-3">
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-50 text-[#0F4C81]' }}">
                                        {{ strtoupper(str_replace('_', ' ', $order->order_status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock Products Alert List --}}
        <div class="lg:col-span-5 bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-rose-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>লো স্টক অ্যালার্ট (Restock List)</span>
                </h3>
                <span class="text-xs font-mono font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded">
                    {{ $metrics['lowStockCount'] }} Items
                </span>
            </div>

            <div class="space-y-2 text-xs">
                @forelse ($lowStockProducts as $prod)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between gap-3">
                        <div class="truncate">
                            <span class="font-bold text-slate-800 block truncate">{{ $prod->name }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $prod->sku }} · {{ $prod->supplier->company_name ?? 'Vendor' }}</span>
                        </div>
                        <div class="text-right shrink-0 font-mono">
                            <span class="text-xs font-extrabold text-rose-600 block">{{ $prod->stock_quantity }} Left</span>
                            <span class="text-[10px] text-slate-400">Limit: {{ $prod->low_stock_threshold }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        সবগুলো পণ্যের স্টক পর্যাপ্ত রয়েছে। কোনো ঘাটতি নেই।
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
