<div>
    @if ($isOpen && $product)
        {{-- Modal Backdrop --}}
        <div 
            class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 overflow-y-auto"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            {{-- Modal Dialog Container --}}
            <div 
                class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh] my-auto animate-in fade-in zoom-in-95 duration-200"
                @click.outside="$wire.closeModal()"
            >
                {{-- 1. Modal Top Bar --}}
                <div class="bg-[#0F172A] text-white px-5 py-3.5 flex items-center justify-between border-b border-slate-800 shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#DC2626] animate-pulse"></span>
                        <h3 class="text-sm sm:text-base font-extrabold tracking-tight">
                            এক্সপ্রেস দ্রুত চেকআউট (Quick Order)
                        </h3>
                    </div>

                    <button 
                        type="button" 
                        wire:click="closeModal" 
                        class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-colors min-h-[44px] min-w-[44px]"
                        aria-label="বন্ধ করুন"
                    >
                        ✕
                    </button>
                </div>

                {{-- Scrollable Content Body --}}
                <div class="p-4 sm:p-5 overflow-y-auto space-y-4 text-slate-900 text-xs sm:text-sm">
                    
                    {{-- Global Error Alert --}}
                    @error('order_error')
                        <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-[#DC2626]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    {{-- 2. Selected Product Summary Card --}}
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Product Thumbnail --}}
                            <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 overflow-hidden p-1 shrink-0 flex items-center justify-center">
                                @if ($product->primaryImage)
                                    <img src="{{ $product->primaryImage->image_path }}" alt="{{ $product->name }}" class="w-full h-full object-contain" />
                                @else
                                    <span class="text-[9px] font-mono text-slate-400 font-bold">NO IMG</span>
                                @endif
                            </div>

                            {{-- Product Title & Price --}}
                            <div class="truncate">
                                <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                    {{ $product->name }}
                                </h4>
                                <div class="flex items-center gap-2 mt-0.5 font-mono">
                                    <span class="text-sm font-extrabold text-[#DC2626]">
                                        ৳{{ number_format($unitPrice, 0) }}
                                    </span>
                                    @if ($product->compare_at_price > $unitPrice)
                                        <span class="text-[11px] text-slate-400 line-through">
                                            ৳{{ number_format($product->compare_at_price, 0) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Quantity Stepper (+/-) --}}
                        <div class="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shrink-0 shadow-2xs">
                            <button 
                                type="button" 
                                wire:click="decrementQuantity" 
                                class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-base active:bg-slate-200 transition-colors min-h-[36px]"
                                {{ $quantity <= 1 ? 'disabled' : '' }}
                            >
                                -
                            </button>
                            <span class="w-8 text-center font-mono font-bold text-slate-900 text-xs">
                                {{ $quantity }}
                            </span>
                            <button 
                                type="button" 
                                wire:click="incrementQuantity" 
                                class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-base active:bg-slate-200 transition-colors min-h-[36px]"
                                {{ $quantity >= 10 ? 'disabled' : '' }}
                            >
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Product Variant Selector (if product has multiple variants) --}}
                    @if ($product->variants && $product->variants->count() > 1)
                        <div class="space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-700">ভেরিয়েন্ট নির্বাচন করুন:</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($product->variants as $variant)
                                    <button
                                        type="button"
                                        wire:click="$set('selectedVariantId', {{ $variant->id }})"
                                        class="p-2.5 rounded-xl border text-xs font-semibold text-left transition-all {{ $selectedVariantId === $variant->id ? 'border-[#DC2626] bg-red-50/60 text-[#DC2626]' : 'border-slate-200 bg-white text-slate-700' }}"
                                    >
                                        <span class="block truncate">{{ $variant->title }}</span>
                                        <span class="font-mono text-[11px] font-bold block mt-0.5">৳{{ number_format($variant->selling_price, 0) }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Customer Information Form --}}
                    <form wire:submit.prevent="confirmOrder" class="space-y-3.5">
                        {{-- Name --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                আপনার নাম (Full Name) *
                            </label>
                            <input 
                                type="text" 
                                wire:model.defer="name" 
                                placeholder="যেমন: মোঃ সাকিব হাসান" 
                                class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0F172A] transition-all {{ $errors->has('name') ? 'border-red-400 bg-red-50/30' : 'border-slate-300' }}"
                            />
                            @error('name') 
                                <span class="text-[10px] text-red-600 mt-1 block font-medium">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                মোবাইল নম্বর (Phone Number) *
                            </label>
                            <div class="relative">
                                <input 
                                    type="tel" 
                                    wire:model.defer="phone" 
                                    placeholder="01712345678" 
                                    class="w-full p-2.5 font-mono bg-slate-50 border rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0F172A] transition-all {{ $errors->has('phone') ? 'border-red-400 bg-red-50/30' : 'border-slate-300' }}"
                                />
                                <span class="absolute right-3 top-2.5 text-[10px] font-mono text-slate-400">১১ ডিজিট</span>
                            </div>
                            @error('phone') 
                                <span class="text-[10px] text-red-600 mt-1 block font-medium">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Delivery District (Inside Dhaka ৳60 / Outside Dhaka ৳120) --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                আপনার ডেলিভারি এরিয়া (Delivery Zone) *
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="p-2.5 rounded-xl border cursor-pointer flex flex-col justify-between transition-all min-h-[44px] {{ in_array(strtolower(trim($district)), ['dhaka', 'ঢাকা']) ? 'border-[#DC2626] bg-red-50/60 shadow-xs' : 'border-slate-200 bg-slate-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-900">ঢাকার ভিতরে</span>
                                        <input type="radio" wire:model.live="district" value="Dhaka" class="text-[#DC2626] focus:ring-[#DC2626]" />
                                    </div>
                                    <span class="text-[11px] font-mono text-slate-600 mt-1 font-semibold">চার্জ: ৳৬০</span>
                                </label>

                                <label class="p-2.5 rounded-xl border cursor-pointer flex flex-col justify-between transition-all min-h-[44px] {{ !in_array(strtolower(trim($district)), ['dhaka', 'ঢাকা']) ? 'border-[#DC2626] bg-red-50/60 shadow-xs' : 'border-slate-200 bg-slate-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-xs text-slate-900">ঢাকার বাইরে</span>
                                        <input type="radio" wire:model.live="district" value="Outside Dhaka" class="text-[#DC2626] focus:ring-[#DC2626]" />
                                    </div>
                                    <span class="text-[11px] font-mono text-slate-600 mt-1 font-semibold">চার্জ: ৳১২০</span>
                                </label>
                            </div>
                        </div>

                        {{-- Full Address --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">
                                সম্পূর্ণ ঠিকানা (Full Delivery Address) *
                            </label>
                            <textarea 
                                wire:model.defer="address" 
                                rows="2" 
                                placeholder="বাসা/হোল্ডিং নম্বর, রোড, এলাকা, থানা এবং জেলা বিস্তারিত লিখুন..." 
                                class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A] transition-all {{ $errors->has('address') ? 'border-red-400 bg-red-50/30' : 'border-slate-300' }}"
                            ></textarea>
                            @error('address') 
                                <span class="text-[10px] text-red-600 mt-1 block font-medium">{{ $message }}</span> 
                            @enderror
                        </div>

                        {{-- Optional Delivery Note --}}
                        <div>
                            <label class="block text-[11px] font-medium text-slate-500 mb-1">
                                ডেলিভারি নোট (ঐচ্ছিক)
                            </label>
                            <input 
                                type="text" 
                                wire:model.defer="delivery_note" 
                                placeholder="যেমন: অফিসের সময় বিকাল ৫টার আগে ডেলিভারি করবেন" 
                                class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-slate-400"
                            />
                        </div>

                        {{-- 4. Payment Method (Cash on Delivery Default) --}}
                        <div class="p-3 bg-emerald-50/80 border border-emerald-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                                    ✓
                                </span>
                                <div>
                                    <span class="text-xs font-bold text-emerald-900 block">ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
                                    <span class="text-[10px] text-emerald-700">পণ্য হাতে পেয়ে দেখে মূল্য পরিশোধ করবেন। অগ্রিম কোনো টাকা দিতে হবে না।</span>
                                </div>
                            </div>
                        </div>

                        {{-- 5. Pricing Breakdown Receipt --}}
                        <div class="p-3.5 bg-slate-100 rounded-2xl space-y-1.5 font-mono text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>পণ্যের মূল্য ({{ $quantity }} টি):</span>
                                <span class="font-bold text-slate-900">৳{{ number_format($subtotal, 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>ডেলিভারি চার্জ:</span>
                                <span class="font-bold text-slate-900">৳{{ number_format($deliveryCharge, 0) }}</span>
                            </div>
                            <div class="border-t border-slate-300 pt-1.5 flex items-center justify-between text-sm sm:text-base font-extrabold text-[#0F172A]">
                                <span>সর্বমোট প্রদেয় বিল:</span>
                                <span class="text-[#DC2626]">৳{{ number_format($totalAmount, 0) }}</span>
                            </div>
                        </div>

                        {{-- 6. Submit Button: 'অর্ডার কনফার্ম করুন' --}}
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 px-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-2xl text-sm sm:text-base font-extrabold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 min-h-[48px] disabled:opacity-75 disabled:cursor-not-allowed"
                        >
                            <span wire:loading.remove class="flex items-center gap-2">
                                <span>অর্ডার কনফার্ম করুন (৳{{ number_format($totalAmount, 0) }})</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>অর্ডার প্রসেস হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...</span>
                            </span>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @endif
</div>
