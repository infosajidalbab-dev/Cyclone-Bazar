<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 space-y-6 pb-24 md:pb-8">
    {{-- Top Heading --}}
    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">শপিং কার্ট (Shopping Cart)</h1>
            <p class="text-xs text-slate-500 mt-0.5">ক্যাশ অন ডেলিভারিতে অর্ডার করতে চেকআউট করুন।</p>
        </div>
        <span class="text-xs font-mono font-semibold text-[#0F4C81] bg-blue-50 px-2.5 py-1 rounded-lg">
            {{ $itemCount }} টি আইটেম
        </span>
    </div>

    {{-- Flash status --}}
    @if (session()->has('cart_status'))
        <div class="p-3 bg-blue-50 border border-blue-200 text-[#0F4C81] rounded-xl text-xs flex items-center justify-between">
            <span>{{ session('cart_status') }}</span>
        </div>
    @endif

    @if (empty($items))
        {{-- Empty Cart State --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 text-center space-y-4 shadow-xs">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">আপনার কার্ট বর্তমানে খালি</h3>
                <p class="text-xs text-slate-500 mt-1">পছন্দের পণ্য খুঁজে পেতে আমাদের কালেকশন দেখুন।</p>
            </div>
            <a href="/" class="inline-flex items-center justify-center min-h-[44px] px-6 text-xs font-bold text-white bg-[#0F4C81] hover:bg-[#0A355C] rounded-xl shadow-xs transition-colors">
                কেনাকাটা চালিয়ে যান (Shop Now)
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left: Item List --}}
            <div class="lg:col-span-7 space-y-3">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs divide-y divide-slate-100 overflow-hidden">
                    @foreach ($items as $key => $item)
                        <div class="p-4 flex items-center gap-3 sm:gap-4 hover:bg-slate-50/50 transition-colors">
                            {{-- Product Image --}}
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-xl border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center p-1">
                                @if ($item['image_path'])
                                    <img src="{{ $item['image_path'] }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-contain" />
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 font-mono">NO IMG</span>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-xs sm:text-sm text-slate-900 truncate">
                                    {{ $item['product_name'] }}
                                </h3>

                                @if ($item['variant_title'])
                                    <span class="text-[11px] text-[#0F4C81] font-semibold block mt-0.5">
                                        {{ $item['variant_title'] }}
                                    </span>
                                @endif

                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-mono font-bold text-slate-900">
                                        ৳{{ number_format($item['unit_price'], 2) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">
                                        (SKU: {{ $item['sku'] }})
                                    </span>
                                </div>

                                {{-- Mobile Controls Row --}}
                                <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-50">
                                    {{-- Stepper --}}
                                    <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                        <button 
                                            type="button" 
                                            wire:click="decrementQuantity('{{ $key }}')"
                                            class="w-8 h-8 flex items-center justify-center text-slate-700 hover:bg-slate-200 font-bold min-h-[36px] min-w-[36px]"
                                            aria-label="Decrease quantity"
                                        >-</button>
                                        <span class="w-8 text-center font-mono font-bold text-xs text-slate-900">{{ $item['quantity'] }}</span>
                                        <button 
                                            type="button" 
                                            wire:click="incrementQuantity('{{ $key }}')"
                                            class="w-8 h-8 flex items-center justify-center text-slate-700 hover:bg-slate-200 font-bold min-h-[36px] min-w-[36px]"
                                            aria-label="Increase quantity"
                                        >+</button>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="font-mono font-bold text-xs text-[#0F4C81]">
                                            ৳{{ number_format($item['subtotal'], 2) }}
                                        </span>
                                        <button 
                                            type="button" 
                                            wire:click="removeItem('{{ $key }}')"
                                            class="text-rose-500 hover:text-rose-700 p-1 min-h-[36px] min-w-[36px] flex items-center justify-center"
                                            title="মুছে ফেলুন"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center px-2 text-xs">
                    <a href="/" class="text-[#0F4C81] hover:underline font-semibold flex items-center gap-1">
                        ← আরো পণ্য কিনুন (Continue Shopping)
                    </a>
                    <button type="button" wire:click="clearCart" class="text-slate-400 hover:text-rose-600">
                        কার্ট খালি করুন (Clear All)
                    </button>
                </div>
            </div>

            {{-- Right: Order Summary & Zone Estimator --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
                        অর্ডার বিবরণী (Order Summary)
                    </h3>

                    {{-- Delivery Zone Selector Preview --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ডেলিভারি এলাকা নির্বাচন করুন (Delivery Zone):
                        </label>
                        <select wire:model.live="selectedZoneId" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium min-h-[44px]">
                            @foreach ($zones as $zone)
                                <option value="{{ $zone->id }}">
                                    {{ $zone->name }} — ৳{{ number_format($zone->base_charge, 0) }} ({{ $zone->estimated_days_min }}-{{ $zone->estimated_days_max }} দিন)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pricing Breakdown --}}
                    <div class="space-y-2 pt-2 border-t border-slate-100 font-mono text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>পণ্যের মূল্য (Subtotal):</span>
                            <span>৳{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-slate-600">
                            <span>ডেলিভারি চার্জ ({{ $selectedZone->name ?? 'Zone' }}):</span>
                            <span>৳{{ number_format($deliveryCharge, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-base font-bold text-[#0F4C81] pt-2 border-t border-slate-200">
                            <span>সর্বমোট প্রদেয় (Total Due):</span>
                            <span>৳{{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Cash on Delivery Trust Box --}}
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4 text-[#28A745] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>১০০% ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
                        </div>
                        <p class="text-[11px] text-emerald-800">
                            ডেলিভারি রাইডারের কাছ থেকে পণ্য দেখে ও বুঝে নিয়ে মূল্য পরিশোধ করুন।
                        </p>
                    </div>

                    {{-- Primary Checkout CTA --}}
                    <a 
                        href="/checkout" 
                        class="w-full min-h-[48px] bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold rounded-xl text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md transition-all"
                    >
                        <span>অর্ডার সম্পন্ন করতে এগিয়ে যান</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
