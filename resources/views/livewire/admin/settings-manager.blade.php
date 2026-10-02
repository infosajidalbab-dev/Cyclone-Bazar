<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">স্টোর কনফিগারেশন ও সেটিংস (Site Settings)</h1>
            <p class="text-xs text-slate-500 mt-0.5">ডেলিভারি চার্জ, অর্ডার প্রিফিক্স এবং ক্যাশ অন ডেলিভারি পেমেন্ট পলিসি নিয়ন্ত্রণ করুন।</p>
        </div>

        <button 
            type="button" 
            wire:click="save" 
            wire:loading.attr="disabled"
            class="px-5 py-2 text-xs font-bold text-white bg-[#0F4C81] hover:bg-[#0A355C] rounded-xl shadow-xs min-h-[44px] flex items-center gap-1.5 transition-colors"
        >
            <span wire:loading.remove>সেটিংস সংরক্ষণ করুন (Save Settings)</span>
            <span wire:loading class="flex items-center gap-1.5">
                <svg class="animate-spin h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span>সংরক্ষণ হচ্ছে...</span>
            </span>
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('settings_status'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-[#28A745] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('settings_status') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Section 1: Store Brand & Identity --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">স্টোর ব্র্যান্ড ও কন্টাক্ট ইনফরমেশন</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">স্টোরের নাম (Store Name) *</label>
                    <input type="text" wire:model="store_name" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold" />
                    @error('store_name') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">অর্ডার ট্র্যাকিং প্রিফিক্স (Order Prefix) *</label>
                    <input type="text" wire:model="order_prefix" placeholder="CM" class="w-full p-2.5 font-mono uppercase bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    <span class="text-[10px] text-slate-400">উদাহরণ: CM-20261002-XXXX</span>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">কাস্টমার কেয়ার হেল্পলাইন (Contact Phone) *</label>
                    <input type="tel" wire:model="contact_phone" placeholder="017XXXXXXXX" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    @error('contact_phone') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">সাপোর্ট ইমেইল (Support Email) *</label>
                    <input type="email" wire:model="support_email" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    @error('support_email') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Section 2: Logistics & Bangladesh Delivery Fees --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">ডেলিভারি চার্জ ও ইনভেন্টরি থ্রেশহোল্ড</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">ঢাকা সিটির ভিতরে চার্জ (৳) *</label>
                    <input type="number" step="1" wire:model="delivery_charge_dhaka" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-[#0F4C81]" />
                    <span class="text-[10px] text-slate-400">Default: ৳60</span>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">ঢাকার বাইরে সারা বাংলাদেশ চার্জ (৳) *</label>
                    <input type="number" step="1" wire:model="delivery_charge_outside" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-[#0F4C81]" />
                    <span class="text-[10px] text-slate-400">Default: ৳120</span>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">লো স্টক সতর্কতা লিমিট (Threshold) *</label>
                    <input type="number" step="1" wire:model="low_stock_threshold" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    <span class="text-[10px] text-slate-400">Default: 5 units</span>
                </div>
            </div>
        </div>

        {{-- Section 3: Payment Policy Toggles --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">পেমেন্ট গেটওয়ে পলিসি (Payment Settings)</h3>

            <div class="space-y-3">
                <label class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between cursor-pointer min-h-[44px]">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">ক্যাশ অন ডেলিভারি (Cash on Delivery) সক্রিয় রাখুন</span>
                        <span class="text-[11px] text-slate-500">গ্রাহক পণ্য হাতে পেয়ে মূল্য পরিশোধের সুবিধা পাবেন।</span>
                    </div>
                    <input type="checkbox" wire:model="cod_enabled" class="w-5 h-5 text-[#0F4C81] rounded border-slate-300" />
                </label>

                <label class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between cursor-pointer min-h-[44px]">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">বিকাশ / নগদ মার্চেন্ট পেমেন্ট অপশন</span>
                        <span class="text-[11px] text-slate-500">ডেলিভারির সময় মোবাইল ওয়ালেটে পেমেন্টের অপশন দেখান।</span>
                    </div>
                    <input type="checkbox" wire:model="bkash_enabled" class="w-5 h-5 text-[#0F4C81] rounded border-slate-300" />
                </label>
            </div>
        </div>
    </form>
</div>
