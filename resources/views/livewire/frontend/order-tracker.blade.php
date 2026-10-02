<div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 space-y-6 pb-24 md:pb-12">
    {{-- Top Heading --}}
    <div class="text-center space-y-1.5">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">অর্ডার ট্র্যাকিং (Order Tracker)</h1>
        <p class="text-xs sm:text-sm text-slate-500">আপনার অর্ডার আইডি এবং মোবাইল নম্বর দিয়ে রিয়েল-টাইম স্ট্যাটাস জানুন।</p>
    </div>

    {{-- Tracking Form --}}
    <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
        <form wire:submit.prevent="trackOrder" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">অর্ডার নম্বর (Order Number) *</label>
                    <input 
                        type="text" 
                        wire:model="order_number" 
                        placeholder="e.g. CM-20261002-8821" 
                        class="w-full px-3.5 py-2.5 text-xs font-mono uppercase bg-slate-50 border @error('order_number') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-1 focus:ring-[#0F4C81] min-h-[44px]"
                    />
                    @error('order_number') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">বিলিং মোবাইল নম্বর (Phone Number) *</label>
                    <input 
                        type="tel" 
                        wire:model="phone" 
                        placeholder="017XXXXXXXX (11 digits)" 
                        class="w-full px-3.5 py-2.5 text-xs font-mono bg-slate-50 border @error('phone') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-1 focus:ring-[#0F4C81] min-h-[44px]"
                    />
                    @error('phone') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="flex-1 min-h-[46px] bg-[#0F4C81] hover:bg-[#0A355C] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-xs transition-colors"
                >
                    <span wire:loading.remove>ট্র্যাক করুন (Track Order)</span>
                    <span wire:loading class="flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>অনুসন্ধান হচ্ছে...</span>
                    </span>
                </button>

                @if ($hasSearched)
                    <button 
                        type="button" 
                        wire:click="resetSearch" 
                        class="px-4 min-h-[46px] bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs transition-colors"
                    >
                        রিসেট
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Error Banner --}}
    @if (session()->has('tracker_error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ session('tracker_error') }}</span>
        </div>
    @endif

    {{-- Tracked Order Results Card --}}
    @if ($trackedOrder)
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-7 shadow-xs space-y-6">
            {{-- Header Summary --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm sm:text-base font-bold font-mono text-slate-900">{{ $trackedOrder->order_number }}</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">
                            {{ strtoupper(str_replace('_', ' ', $trackedOrder->order_status)) }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-400 font-mono">অর্ডার তারিখ: {{ $trackedOrder->created_at->format('d M Y, h:i A') }}</span>
                </div>

                <div class="text-right">
                  <span class="text-[11px] text-slate-500 block">ক্যাশ অন ডেলিভারি বকেয়া:</span>
                  <span class="text-lg font-extrabold font-mono text-[#0F4C81]">৳{{ number_format($trackedOrder->total_amount, 2) }}</span>
                </div>
            </div>

            {{-- Visual Stepper Progress Bar --}}
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">অর্ডার অগ্রগতি (Fulfillment Timeline)</h4>
                @php
                    $steps = [
                        'pending' => 'অর্ডার গৃহীত',
                        'confirmed' => 'কনফার্মড',
                        'processing' => 'প্যাকিং',
                        'handed_over_to_courier' => 'কুরিয়ারে আছে',
                        'delivered' => 'ডেলিভার্ড',
                    ];
                    $stepKeys = array_keys($steps);
                    $currentIndex = array_search($trackedOrder->order_status, $stepKeys);
                    if ($currentIndex === false) $currentIndex = 0;
                @endphp

                <div class="grid grid-cols-5 gap-1 text-center">
                    @foreach ($stepKeys as $idx => $stepKey)
                        @php
                            $isComplete = $idx <= $currentIndex;
                            $isCurrent = $idx === $currentIndex;
                        @endphp
                        <div class="space-y-1.5">
                            <div class="h-2 rounded-full transition-all {{ $isComplete ? 'bg-[#28A745]' : 'bg-slate-200' }}"></div>
                            <span class="text-[10px] sm:text-xs block truncate {{ $isCurrent ? 'font-bold text-[#0F4C81]' : ($isComplete ? 'text-slate-800 font-medium' : 'text-slate-400') }}">
                                {{ $steps[$stepKey] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Courier Details Card (Steadfast / Pathao) --}}
            @if ($trackedOrder->courier_name)
                <div class="p-4 bg-purple-50/70 border border-purple-200 rounded-2xl space-y-1.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-purple-950 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            <span>{{ $trackedOrder->courier_name }}</span>
                        </span>
                        <span class="font-mono text-purple-800 font-semibold">
                            ট্র্যাকিং আইডি: {{ $trackedOrder->courier_tracking_code }}
                        </span>
                    </div>
                    <p class="text-[11px] text-purple-900">
                        আপনার পার্সেলটি কুরিয়ারে বুকিং করা হয়েছে। রাইডার আপনার এলাকায় পৌঁছে কল করবেন।
                    </p>
                </div>
            @endif

            {{-- Shipping Address Card --}}
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-1.5 text-xs">
                <span class="font-bold text-slate-800 block">ডেলিভারি ঠিকানা (Recipient Address)</span>
                <div class="text-slate-600 space-y-0.5">
                    <p class="font-semibold text-slate-800">{{ $trackedOrder->shippingAddress?->recipient_name }} ({{ $trackedOrder->customer_phone }})</p>
                    <p>{{ $trackedOrder->shippingAddress?->street_address }}</p>
                    <p class="text-[#0F4C81]">{{ $trackedOrder->shippingAddress?->upazila }}, {{ $trackedOrder->shippingAddress?->district }}, {{ $trackedOrder->shippingAddress?->division }}</p>
                    @if ($trackedOrder->shippingAddress?->landmark)
                        <p class="text-amber-800">কাছের স্থান: {{ $trackedOrder->shippingAddress->landmark }}</p>
                    @endif
                </div>
            </div>

            {{-- Ordered Items --}}
            <div class="space-y-3">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block">অর্ডারের আইটেম সমূহ</span>
                <div class="divide-y divide-slate-100 text-xs border border-slate-200 rounded-2xl overflow-hidden">
                    @foreach ($trackedOrder->items as $item)
                        <div class="p-3.5 flex items-center justify-between bg-white">
                            <div>
                                <span class="font-semibold text-slate-800 block">{{ $item->product_name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $item->quantity }} x ৳{{ number_format($item->unit_price, 2) }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-900">৳{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                    <div class="p-3.5 bg-slate-50 flex justify-between font-mono text-xs">
                        <span class="text-slate-500">ডেলিভারি চার্জ:</span>
                        <span>৳{{ number_format($trackedOrder->delivery_charge, 2) }}</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 flex justify-between font-mono font-bold text-sm text-[#0F4C81] border-t border-slate-200">
                        <span>সর্বমোট প্রদেয়:</span>
                        <span>৳{{ number_format($trackedOrder->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Status History Logs --}}
            <div class="space-y-2.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block">অডিট ট্র্যাকিং হিস্ট্রি</span>
                <div class="space-y-2">
                    @foreach ($trackedOrder->statusHistory as $hist)
                        <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs space-y-0.5">
                            <div class="flex justify-between font-mono text-[10px] text-slate-500">
                                <span class="font-bold text-[#0F4C81]">{{ strtoupper(str_replace('_', ' ', $hist->to_status)) }}</span>
                                <span>{{ $hist->created_at->format('d M, h:i A') }}</span>
                            </div>
                            <p class="text-slate-700 text-[11px] font-sans">{{ $hist->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
