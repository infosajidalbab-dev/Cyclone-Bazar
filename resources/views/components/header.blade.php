{{--
    Cyclone Mart Main Navigation Header Component
    Tech: Laravel 11 Blade + Tailwind CSS + Alpine.js
    Theme: Dark Navy Blue (bg-slate-900) with White & Crimson Red (bg-red-600) Accents
--}}
@props([
    'cartCount' => 0,
    'cartTotal' => 0,
    'hotline' => '০১৭১২-৩৪৫৬৭৮',
    'hotlineRaw' => '01712345678',
    'categories' => [
        ['name' => 'টিডব্লিউএস ইয়ারবাডস', 'icon' => '🎧', 'url' => '/category/tws-audio'],
        ['name' => 'স্মার্ট ওয়াচ ও ঘড়ি', 'icon' => '⌚', 'url' => '/category/smart-watch'],
        ['name' => 'পাওয়ার ব্যাংক ও চার্জার', 'icon' => '🔋', 'url' => '/category/powerbank'],
        ['name' => 'ফাস্ট চার্জিং ক্যাবল', 'icon' => '🔌', 'url' => '/category/cables'],
        ['name' => 'স্মার্ট হোম গ্যাজেট', 'icon' => '💡', 'url' => '/category/smart-home'],
        ['name' => 'মোবাইল অ্যাক্সেসরিজ', 'icon' => '📱', 'url' => '/category/accessories'],
    ]
])

<div 
    x-data="{
        mobileDrawerOpen: false,
        cartCount: {{ (int) $cartCount }},
        cartTotal: {{ (float) $cartTotal }},
        searchQuery: '',
        updateCart(count, total) {
            this.cartCount = count;
            this.cartTotal = total;
        }
    }"
    @cart-updated.window="updateCart($event.detail.count, $event.detail.total)"
    class="w-full relative select-none font-sans"
>
    {{-- 1. Top Announcement Hotline Bar --}}
    <div class="bg-slate-950 text-slate-300 text-xs py-1.5 px-4 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1 text-center sm:text-left text-[11px] sm:text-xs">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-red-600 text-white text-[10px]">
                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                </span>
                <span>হটলাইন: <a href="tel:{{ $hotlineRaw }}" class="text-white font-mono font-bold hover:underline tracking-wider">{{ $hotline }}</a> (সকাল ৯টা - রাত ১১টা)</span>
            </div>

            <div class="flex items-center gap-3 text-slate-300 text-[11px]">
                <span class="hidden md:inline">🚚 সারাদেশে ক্যাশ অন ডেলিভারি</span>
                <span class="hidden md:inline text-slate-600">•</span>
                <span class="hidden sm:inline">🔄 ৭ দিনের রিপ্লেসমেন্ট পলিসি</span>
                <span class="hidden sm:inline text-slate-600">•</span>
                <a href="/track" class="text-slate-200 hover:text-white font-semibold flex items-center gap-1 hover:underline">
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>অর্ডার ট্র্যাক</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. Main Sticky Header (Dark Navy Blue bg-slate-900) --}}
    <header class="sticky top-0 z-40 bg-slate-900 text-white border-b border-slate-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-2.5 sm:py-3 flex items-center justify-between gap-3 sm:gap-6">
            
            {{-- Zone 1: Hamburger (Mobile) + High-Contrast Brand Logo --}}
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                {{-- Hamburger Menu Button (Touch Target >= 44px) --}}
                <button 
                    type="button" 
                    @click="mobileDrawerOpen = true"
                    class="lg:hidden w-11 h-11 -ml-1 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                    aria-label="ক্যাটাগরি মেনু খুলুন"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Brand Wordmark --}}
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-extrabold text-base tracking-tighter shadow-sm group-hover:scale-105 transition-transform shrink-0">
                        <span>CM</span>
                    </div>
                    <div>
                        <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-white block leading-none">
                            Cyclone<span class="text-red-500">Mart</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium tracking-wide block sm:inline">
                            অনলাইন শপিং বাংলাদেশ
                        </span>
                    </div>
                </a>
            </div>

            {{-- Zone 2: Search Bar (Desktop / Tablet Centered) --}}
            <div class="flex-1 max-w-xl hidden md:block">
                <form action="/shop" method="GET" class="relative flex items-center">
                    <input 
                        type="text" 
                        name="q"
                        x-model="searchQuery"
                        placeholder="পছন্দের গ্যাজেট বা পণ্যের নাম খুঁজুন (যেমন: TWS, Smart Watch)..." 
                        class="w-full pl-4 pr-24 py-2.5 bg-slate-800 text-white placeholder-slate-400 border border-slate-700 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                    />
                    <button 
                        type="submit" 
                        class="absolute right-1 top-1 bottom-1 px-4 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-xs"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>খুঁজুন</span>
                    </button>
                </form>
            </div>

            {{-- Zone 3: Hotline Call + Shopping Cart with Badge & Price --}}
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                {{-- Quick Hotline Direct Call (Desktop) --}}
                <a href="tel:{{ $hotlineRaw }}" class="hidden xl:flex items-center gap-2 p-2 rounded-xl text-left hover:bg-slate-800 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    </div>
                    <div class="text-xs">
                        <span class="text-slate-400 block text-[10px]">সরাসরি কল করুন</span>
                        <span class="font-mono font-bold text-white">{{ $hotline }}</span>
                    </div>
                </a>

                {{-- Shopping Cart Badge Button --}}
                <a 
                    href="/cart" 
                    class="relative p-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl flex items-center gap-2.5 transition-colors border border-slate-700 min-h-[44px]"
                    aria-label="শপিং কার্ট দেখুন"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        
                        {{-- Red Counter Badge --}}
                        <span 
                            class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] font-mono font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-slate-900 shadow-sm"
                            x-text="cartCount"
                        >
                            {{ $cartCount }}
                        </span>
                    </div>

                    {{-- Cart Price & Label --}}
                    <div class="hidden sm:block text-left leading-tight">
                        <span class="text-[10px] text-slate-400 block">আমার কার্ট</span>
                        <span class="text-xs font-mono font-bold text-red-400" x-text="'৳' + cartTotal.toLocaleString('en-US')">
                            ৳{{ number_format($cartTotal, 0) }}
                        </span>
                    </div>
                </a>
            </div>
        </div>

        {{-- Mobile Full-Width Search Bar (Appears under logo on mobile) --}}
        <div class="md:hidden px-4 pb-2.5">
            <form action="/shop" method="GET" class="relative flex items-center">
                <input 
                    type="text" 
                    name="q"
                    x-model="searchQuery"
                    placeholder="পণ্য বা গ্যাজেটের নাম খুঁজুন..." 
                    class="w-full pl-3.5 pr-20 py-2 bg-slate-800 text-white placeholder-slate-400 border border-slate-700 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500"
                />
                <button 
                    type="submit" 
                    class="absolute right-1 top-1 bottom-1 px-3 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs"
                >
                    <span>খুঁজুন</span>
                </button>
            </form>
        </div>
    </header>

    {{-- 3. Mobile Slide-Over Drawer Menu (Triggered by Hamburger) --}}
    <div 
        x-cloak 
        x-show="mobileDrawerOpen" 
        class="fixed inset-0 z-50 lg:hidden"
        role="dialog" 
        aria-modal="true"
    >
        {{-- Backdrop --}}
        <div 
            x-show="mobileDrawerOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs" 
            @click="mobileDrawerOpen = false"
        ></div>

        {{-- Sliding Panel --}}
        <div 
            x-show="mobileDrawerOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative w-4/5 max-w-xs h-full bg-slate-900 text-white flex flex-col justify-between shadow-2xl border-r border-slate-800 overflow-y-auto"
        >
            <div>
                {{-- Drawer Header --}}
                <div class="p-4 bg-slate-950 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-red-600 text-white flex items-center justify-center font-bold text-xs">
                            CM
                        </div>
                        <span class="font-extrabold text-base text-white">Cyclone<span class="text-red-500">Mart</span></span>
                    </div>
                    <button 
                        @click="mobileDrawerOpen = false" 
                        class="w-8 h-8 rounded-lg bg-slate-800 text-slate-300 hover:text-white flex items-center justify-center"
                        aria-label="বন্ধ করুন"
                    >
                        ✕
                    </button>
                </div>

                {{-- Drawer Category Navigation --}}
                <div class="p-3">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 py-2">
                        ক্যাটাগরি সমূহ
                    </div>
                    <nav class="space-y-1">
                        @foreach ($categories as $cat)
                            <a 
                                href="{{ $cat['url'] }}" 
                                @click="mobileDrawerOpen = false"
                                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-slate-200 hover:bg-slate-800 hover:text-red-400 transition-colors"
                            >
                                <span class="flex items-center gap-2.5">
                                    <span class="text-base">{{ $cat['icon'] }}</span>
                                    <span>{{ $cat['name'] }}</span>
                                </span>
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                        
                        <a 
                            href="/shop?deals=hot" 
                            @click="mobileDrawerOpen = false"
                            class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500/20 transition-colors"
                        >
                            <span class="flex items-center gap-2">
                                <span>🔥</span>
                                <span>হট ডিলস ও অফার</span>
                            </span>
                            <span class="text-[10px] font-mono bg-red-600 text-white px-1.5 py-0.5 rounded">HOT</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Drawer Footer Hotline & Info --}}
            <div class="p-4 bg-slate-950 border-t border-slate-800 space-y-3">
                <a href="tel:{{ $hotlineRaw }}" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 min-h-[44px] shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    <span>হটলাইনে কল করুন ({{ $hotline }})</span>
                </a>
                <p class="text-[10px] text-slate-500 text-center">
                    সারা বাংলাদেশে ক্যাশ অন ডেলিভারি সুবিধা
                </p>
            </div>
        </div>
    </div>

    {{-- 4. Mobile Sticky Bottom Navigation Bar (Essential Mobile UX) --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-slate-900/95 backdrop-blur-md border-t border-slate-800 py-1.5 px-4 flex items-center justify-around text-slate-400 shadow-xl">
        {{-- Home Tab --}}
        <a href="/" class="flex flex-col items-center gap-0.5 text-red-500 min-h-[44px] justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-bold">হোম</span>
        </a>

        {{-- Categories Drawer Trigger --}}
        <button 
            type="button" 
            @click="mobileDrawerOpen = true" 
            class="flex flex-col items-center gap-0.5 hover:text-white min-h-[44px] justify-center"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span class="text-[10px] font-medium">ক্যাটাগরি</span>
        </button>

        {{-- Order Tracker --}}
        <a href="/track" class="flex flex-col items-center gap-0.5 hover:text-white min-h-[44px] justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-[10px] font-medium">ট্র্যাকিং</span>
        </a>

        {{-- Cart with Live Badge --}}
        <a href="/cart" class="flex flex-col items-center gap-0.5 relative hover:text-white min-h-[44px] justify-center">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span 
                    class="absolute -top-1.5 -right-2 bg-red-600 text-white text-[9px] font-mono font-bold w-4 h-4 rounded-full flex items-center justify-center border border-slate-900"
                    x-text="cartCount"
                >
                    {{ $cartCount }}
                </span>
            </div>
            <span class="text-[10px] font-medium">কার্ট</span>
        </a>

        {{-- Direct Call --}}
        <a href="tel:{{ $hotlineRaw }}" class="flex flex-col items-center gap-0.5 text-emerald-400 hover:text-emerald-300 min-h-[44px] justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span class="text-[10px] font-bold">কল করুন</span>
        </a>
    </nav>
</div>
