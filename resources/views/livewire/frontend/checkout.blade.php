<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 space-y-6 pb-24 md:pb-8">
    {{-- Header --}}
    <div class="flex items-center justify-between pb-3 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">ক্যাশ অন ডেলিভারি চেকআউট (Checkout)</h1>
            <p class="text-xs text-slate-500 mt-0.5">কোনো অগ্রিম ফি লাগবে না। ডেলিভারির সময় পণ্য দেখে মূল্য পরিশোধ করুন।</p>
        </div>
        <a href="/cart" class="text-xs font-semibold text-[#0F4C81] hover:underline flex items-center gap-1 min-h-[44px]">
            ← কার্টে ফিরে যান
        </a>
    </div>

    {{-- Error Banner --}}
    @if (session()->has('checkout_error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span>{{ session('checkout_error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="placeOrder" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: Customer & Address Form --}}
        <div class="lg:col-span-7 space-y-5">
            {{-- 1. Contact Information --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F4C81] text-white text-xs font-bold flex items-center justify-center font-mono">1</span>
                    <h3 class="text-sm font-bold text-slate-900">ব্যক্তিগত তথ্য (Contact Information)</h3>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">আপনার পূর্ণ নাম (Full Name) *</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        placeholder="e.g. সাজিদ প্রামাণিক / Sajid Pramanik"
                        class="w-full px-3 py-2.5 text-xs bg-slate-50 border @error('name') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-1 focus:ring-[#0F4C81] min-h-[44px]"
                    />
                    @error('name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">মোবাইল নম্বর (11 Digits Mobile) *</label>
                    <div class="relative">
                        <input 
                            type="tel" 
                            wire:model.live.debounce.300ms="phone"
                            placeholder="01712345678"
                            class="w-full px-3 py-2.5 text-xs font-mono bg-slate-50 border @error('phone') border-rose-400 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:ring-1 focus:ring-[#0F4C81] min-h-[44px]"
                        />
                        @if (preg_match('/^(?:\+?880|0)?1[3-9]\d{8}$/', $phone))
                            <span class="absolute right-3 top-3 text-[#28A745]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </span>
                        @endif
                    </div>
                    @error('phone') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    <span class="text-[10px] text-slate-400 mt-0.5 block">অর্ডারের আপডেট ও ট্র্যাকিং এসএমএস এই নম্বরে পাঠানো হবে।</span>
                </div>
            </div>

            {{-- 2. Delivery Address with Dynamic District Zone Selector --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F4C81] text-white text-xs font-bold flex items-center justify-center font-mono">2</span>
                    <h3 class="text-sm font-bold text-slate-900">ডেলিভারি ঠিকানা (Delivery Address)</h3>
                </div>

                {{-- District Dropdown --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">জেলা (District) *</label>
                        <span class="text-[11px] font-mono font-bold {{ $district === 'Dhaka' ? 'text-[#0F4C81]' : 'text-amber-700' }}">
                            ডেলিভারি চার্জ: ৳{{ number_format($delivery_charge, 0) }}
                        </span>
                    </div>

                    <select 
                        wire:model.live="district"
                        class="w-full px-3 py-2.5 text-xs font-medium bg-slate-50 border @error('district') border-rose-400 @else border-slate-200 @enderror rounded-xl min-h-[44px]"
                    >
                        @foreach ($districts as $distName => $divName)
                            <option value="{{ $distName }}">
                                {{ $distName }} ({{ $distName === 'Dhaka' ? 'ঢাকা সিটি — ৳৬০' : 'ঢাকার বাইরে — ৳১২০' }})
                            </option>
                        @endforeach
                    </select>
                    @error('district') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Area / Thana --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">থানা / এলাকা (Area or Thana) *</label>
                    <input 
                        type="text" 
                        wire:model="area"
                        placeholder="যেমন: মিরপুর ১০ / ধানমন্ডি / উত্তরা / চকবাজার"
                        class="w-full px-3 py-2.5 text-xs bg-slate-50 border @error('area') border-rose-400 @else border-slate-200 @enderror rounded-xl min-h-[44px]"
                    />
                    @error('area') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Full Street Address --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">পূর্ণ ঠিকানা (House, Road, Block) *</label>
                    <textarea 
                        rows="2" 
                        wire:model="street_address"
                        placeholder="বাসা নং, রোড নং, ফ্ল্যাট বা ব্লকের বিবরণ..."
                        class="w-full px-3 py-2 text-xs bg-slate-50 border @error('street_address') border-rose-400 @else border-slate-200 @enderror rounded-xl"
                    ></textarea>
                    @error('street_address') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Landmark & Note --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">পরিচিত স্থান (Landmark - Optional)</label>
                        <input 
                            type="text" 
                            wire:model="landmark"
                            placeholder="যেমন: মসজিদের পাশে / স্কুলের সামনে"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl min-h-[44px]"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ডেলিভারি নোট (Delivery Note)</label>
                        <input 
                            type="text" 
                            wire:model="delivery_note"
                            placeholder="রাইডারের জন্য কোনো বিশেষ নির্দেশনা"
                            class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl min-h-[44px]"
                        />
                    </div>
                </div>
            </div>

            {{-- 3. Payment Method (COD Highlighted) --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F4C81] text-white text-xs font-bold flex items-center justify-center font-mono">3</span>
                    <h3 class="text-sm font-bold text-slate-900">পেমেন্ট মেথড (Payment Method)</h3>
                </div>

                <div class="space-y-2">
                    <label class="p-3.5 bg-blue-50/70 border-2 border-[#0F4C81] rounded-xl flex items-center justify-between cursor-pointer min-h-[50px]">
                        <div class="flex items-center gap-3">
                            <input type="radio" wire:model="payment_method" value="cod" class="w-4 h-4 text-[#0F4C81]" checked />
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
                                <span class="text-[11px] text-slate-500">পণ্য হাতে পেয়ে নগদ টাকা পরিশোধ করবেন।</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-[#0F4C81] font-mono">100% SECURE</span>
                    </label>

                    <label class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between cursor-pointer min-h-[48px] opacity-75">
                        <div class="flex items-center gap-3">
                            <input type="radio" wire:model="payment_method" value="bkash" class="w-4 h-4 text-[#0F4C81]" />
                            <div>
                                <span class="text-xs font-semibold text-slate-800 block">বিকাশ / নগদ (bKash / Nagad)</span>
                                <span class="text-[10px] text-slate-400">ডেলিভারির সময় রাইডারের মার্চেন্ট নাম্বারেও পেমেন্ট করতে পারবেন।</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Right: Order Summary --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4 sticky top-20">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
                    অর্ডার সামারি ({{ $itemCount }} টি আইটেম)
                </h3>

                {{-- Cart Items Mini Scroller --}}
                <div class="max-h-60 overflow-y-auto divide-y divide-slate-100 pr-1 text-xs">
                    @foreach ($items as $item)
                        <div class="py-2.5 flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-semibold text-slate-800 block truncate">{{ $item['product_name'] }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">
                                    {{ $item['quantity'] }} x ৳{{ number_format($item['unit_price'], 2) }}
                                    @if ($item['variant_title']) · {{ $item['variant_title'] }} @endif
                                </span>
                            </div>
                            <span class="font-mono font-bold text-slate-900 shrink-0">
                                ৳{{ number_format($item['subtotal'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="space-y-2 pt-3 border-t border-slate-100 font-mono text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>পণ্যের মোট মূল্য:</span>
                        <span>৳{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>ডেলিভারি চার্জ ({{ $district === 'Dhaka' ? 'ঢাকা সিটি' : 'ঢাকার বাইরে' }}):</span>
                        <span class="font-bold text-[#0F4C81]">৳{{ number_format($delivery_charge, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-base font-bold text-[#0F4C81] pt-2 border-t border-slate-200">
                        <span>সর্বমোট প্রদেয় (COD Due):</span>
                        <span class="text-lg">৳{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>

                {{-- Submit CTA Button --}}
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full min-h-[50px] bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold rounded-xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/10 transition-all disabled:opacity-50"
                >
                    <span wire:loading.remove>অর্ডার নিশ্চিত করুন (Confirm Order)</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>অর্ডার প্রসেস হচ্ছে...</span>
                    </span>
                </button>

                {{-- Trust Badges --}}
                <div class="text-center space-y-1 text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    <p class="flex items-center justify-center gap-1 text-emerald-700 font-semibold">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>৭ দিনের ফ্রি রিপ্লেসমেন্ট সুবিধা</span>
                    </p>
                    <p>রাইডারের সামনে পার্সেল দেখে নেওয়ার নিশ্চয়তা।</p>
                </div>
            </div>
        </div>
    </form>
</div>
