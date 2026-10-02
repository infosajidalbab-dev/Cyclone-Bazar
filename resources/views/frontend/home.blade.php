<!DOCTYPE html>
<html lang="bn" class="h-full bg-[#F8FAFC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Cyclone Mart — বাংলাদেশের বিশ্বস্ত অনলাইন গ্যাজেট ও ইলেকট্রনিক্স শপ</title>
    <meta name="description" content="ক্যাশ অন ডেলিভারিতে অরিজিনাল গ্যাজেট ও ইলেকট্রনিক্স অর্ডার করুন সারা বাংলাদেশে। কোনো অগ্রিম টাকা ছাড়া দ্রুত হোম ডেলিভারি ও ৭ দিনের রিটার্ন গ্যারান্টি।">
    
    {{-- Open Graph & SEO Tags --}}
    <meta property="og:title" content="Cyclone Mart — সেরা দামে অনলাইন শপিং বাংলাদেশ">
    <meta property="og:description" content="ক্যাশ অন ডেলিভারিতে অর্ডার করুন জেনুইন গ্যাজেট ও পণ্য। ২-৩ দিনে সারাদেশে হোম ডেলিভারি।">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="bn_BD">
    
    {{-- Google Fonts (Plus Jakarta Sans & Hind Siliguri for Bengali) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@600&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS & Alpine.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            DEFAULT: '#0F172A',
                            800: '#1E293B',
                            900: '#0F172A',
                            950: '#020617',
                        },
                        crimson: {
                            DEFAULT: '#DC2626',
                            hover: '#B91C1C',
                            light: '#FEF2F2',
                        },
                    },
                    fontFamily: {
                        sans: ['Hind Siliguri', 'Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Hind Siliguri', 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body 
    class="text-slate-900 bg-[#F8FAFC] antialiased flex flex-col min-h-screen pb-16 lg:pb-0" 
    x-data="{
        cartCount: {{ session('cart_count', 0) }},
        cartTotal: {{ session('cart_total', 0) }},
        searchQuery: '',
        activeCategory: 'all',
        mobileMenuOpen: false,
        toastMessage: '',
        toastVisible: false,
        showToast(msg) {
            this.toastMessage = msg;
            this.toastVisible = true;
            setTimeout(() => { this.toastVisible = false }, 2500);
        },
        addToCart(id, name, price) {
            this.cartCount++;
            this.cartTotal += price;
            this.showToast('✅ ' + name + ' কার্টে যোগ করা হয়েছে!');
        }
    }"
>

    {{-- Notification Toast --}}
    <div 
        x-cloak 
        x-show="toastVisible" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-20 lg:bottom-6 right-4 lg:right-6 z-50 bg-[#0F172A] text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700 text-xs sm:text-sm font-medium"
    >
        <span class="w-2.5 h-2.5 rounded-full bg-[#DC2626] animate-pulse"></span>
        <span x-text="toastMessage"></span>
    </div>

    {{-- 1. Top Announcement Bar with Hotline --}}
    <div class="bg-[#0F172A] text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1.5 sm:gap-4 text-center sm:text-left">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#DC2626] text-white text-[11px]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </span>
                <span>হটলাইন: <strong class="text-white font-mono tracking-wider">০১৭১২-৩৪৫৬৭৮</strong> (সকাল ৯টা - রাত ১১টা)</span>
            </div>

            <div class="flex items-center gap-4 text-[11px] text-slate-300">
                <span class="hidden md:inline">🚚 সারাদেশে ক্যাশ অন ডেলিভারি</span>
                <span class="hidden md:inline text-slate-600">•</span>
                <span class="hidden sm:inline">🔄 ৭ দিনের রিপ্লেসমেন্ট পলিসি</span>
                <span class="hidden sm:inline text-slate-600">•</span>
                <a href="/track" class="text-slate-200 hover:text-white font-semibold flex items-center gap-1 hover:underline">
                    <svg class="w-3.5 h-3.5 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>অর্ডার ট্র্যাক করুন</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 2. Sticky Header with Logo, Search Bar, and Live Cart --}}
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 py-3 sm:py-3.5 flex items-center justify-between gap-3 sm:gap-6">
            
            {{-- Brand Logo --}}
            <div class="flex items-center gap-2 shrink-0">
                {{-- Mobile menu button --}}
                <button 
                    type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="lg:hidden p-2 -ml-2 text-slate-700 hover:text-[#0F172A] rounded-lg"
                    aria-label="Toggle Category Menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 rounded-xl bg-[#0F172A] flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                        <span class="font-extrabold text-base tracking-tighter text-white">C<span class="text-[#DC2626]">M</span></span>
                    </div>
                    <div>
                        <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-[#0F172A] block leading-none">
                            Cyclone<span class="text-[#DC2626]">Mart</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium tracking-wide block">বাংলাদেশের বিশ্বস্ত অনলাইন শপ</span>
                    </div>
                </a>
            </div>

            {{-- Centered Responsive Search Bar --}}
            <div class="flex-1 max-w-2xl hidden md:block">
                <form action="/shop" method="GET" class="relative flex items-center">
                    <input 
                        type="text" 
                        name="q"
                        x-model="searchQuery"
                        placeholder="পছন্দের গ্যাজেট বা পণ্যের নাম খুঁজুন (যেমন: TWS, Smart Watch, Powerbank)..." 
                        class="w-full pl-4 pr-24 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#0F172A] focus:border-transparent transition-all placeholder:text-slate-400"
                    />
                    <button 
                        type="submit" 
                        class="absolute right-1 top-1 bottom-1 px-4 bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs sm:text-sm font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-xs"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>খুঁজুন</span>
                    </button>
                </form>
            </div>

            {{-- Header Actions: Phone Support & Live Cart Icon --}}
            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                {{-- Quick Phone Support on Desktop --}}
                <a href="tel:01712345678" class="hidden xl:flex items-center gap-2 p-2 rounded-xl text-left hover:bg-slate-50 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-red-50 text-[#DC2626] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    </div>
                    <div class="text-xs">
                        <span class="text-slate-400 block text-[10px]">সরাসরি কল করুন</span>
                        <span class="font-mono font-bold text-slate-800">০১৭১২-৩৪৫৬৭৮</span>
                    </div>
                </a>

                {{-- Live Cart Icon with Badge --}}
                <a 
                    href="/cart" 
                    class="relative p-2.5 bg-slate-100 hover:bg-[#0F172A] hover:text-white text-[#0F172A] rounded-xl flex items-center gap-2 transition-all min-h-[44px]"
                    aria-label="View Shopping Cart"
                >
                    <div class="relative">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span 
                            class="absolute -top-2 -right-2 bg-[#DC2626] text-white text-[10px] font-mono font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-xs"
                            x-text="cartCount"
                        ></span>
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-[10px] text-slate-400 block leading-tight">আমার কার্ট</span>
                        <span class="text-xs font-mono font-bold text-[#DC2626] leading-tight" x-text="'৳' + cartTotal.toLocaleString('en-US')"></span>
                    </div>
                </a>
            </div>
        </div>

        {{-- Mobile Search Bar Form --}}
        <div class="md:hidden px-4 pb-2.5">
            <form action="/shop" method="GET" class="relative flex items-center">
                <input 
                    type="text" 
                    name="q"
                    placeholder="পছন্দের গ্যাজেট বা পণ্য খুঁজুন..." 
                    class="w-full pl-3.5 pr-20 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A] focus:border-transparent placeholder:text-slate-400"
                />
                <button 
                    type="submit" 
                    class="absolute right-1 top-1 bottom-1 px-3 bg-[#DC2626] text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs"
                >
                    <span>খুঁজুন</span>
                </button>
            </form>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-7xl mx-auto px-4 py-4 sm:py-6 space-y-8 flex-1 w-full">
        
        {{-- 3. Hero Section (2-Column: Category Sidebar Left + Banner Slider Right) --}}
        <section class="grid grid-cols-12 gap-4 lg:gap-6 items-start">
            
            {{-- Left Column: Category Sidebar (Desktop) --}}
            <aside class="hidden lg:block lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="bg-[#0F172A] text-white px-4 py-3 flex items-center gap-2.5 font-bold text-sm">
                    <svg class="w-4 h-4 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <span>সকল ক্যাটাগরি (Categories)</span>
                </div>

                <nav class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    <a href="#tws" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">🎧</span>
                            <span>টিডব্লিউএস ইয়ারবাডস (TWS)</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#smartwatch" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">⌚</span>
                            <span>স্মার্ট ওয়াচ ও ঘড়ি (Smart Watch)</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#powerbank" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">🔋</span>
                            <span>পাওয়ার ব্যাংক ও চার্জার</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#cables" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">🔌</span>
                            <span>ফাস্ট চার্জিং ক্যাবল ও অ্যাডাপ্টার</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#lifestyle" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">💡</span>
                            <span>স্মার্ট হোম ও লাইফস্টাইল গ্যাজেট</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#accessories" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">📱</span>
                            <span>মোবাইল হোল্ডার ও অ্যাক্সেসরিজ</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="#hot-deals" class="px-4 py-2.5 flex items-center justify-between bg-red-50/50 text-[#DC2626] font-bold hover:bg-red-50 transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-base group-hover:scale-110 transition-transform">🔥</span>
                            <span>হট ডিলস ও মেগা ডিসকাউন্ট</span>
                        </span>
                        <span class="text-[10px] font-mono bg-[#DC2626] text-white px-1.5 py-0.5 rounded">HOT</span>
                    </a>
                </nav>
            </aside>

            {{-- Right Column: Promotional Hero Slider & Quick USP Bar --}}
            <div class="col-span-12 lg:col-span-9 space-y-4">
                
                {{-- Interactive Alpine.js Hero Slider --}}
                <div 
                    class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs bg-[#0F172A] aspect-[16/8] sm:aspect-[21/9]"
                    x-data="{
                        currentSlide: 0,
                        slidesCount: 2,
                        next() { this.currentSlide = (this.currentSlide + 1) % this.slidesCount },
                        prev() { this.currentSlide = (this.currentSlide - 1 + this.slidesCount) % this.slidesCount },
                        init() {
                            setInterval(() => this.next(), 6000);
                        }
                    }"
                >
                    {{-- Slide 1: Exclusive Gadget Offer --}}
                    <div 
                        x-show="currentSlide === 0"
                        x-transition:enter="transition ease-out duration-400"
                        x-transition:enter-start="opacity-0 scale-98"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0 p-5 sm:p-10 flex flex-col justify-center text-white bg-gradient-to-r from-[#0F172A] via-[#0F172A]/90 to-transparent z-10"
                    >
                        <div class="max-w-md space-y-2">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#DC2626] text-white text-[11px] font-bold font-mono tracking-wide">
                                <span>🔥 ধামাকা অফার</span>
                                <span>·</span>
                                <span>৫০% পর্যন্ত ছাড়</span>
                            </div>
                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                                অরিজিনাল TWS ও স্মার্ট গ্যাজেট কালেকশন
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 line-clamp-2">
                                ক্যাশ অন ডেলিভারিতে সারাদেশে সুপারফাস্ট হোম ডেলিভারি। কোনো অগ্রিম পেমেন্ট নেই!
                            </p>
                            <div class="pt-2 flex items-center gap-3">
                                <a href="#hot-deals" class="px-5 py-2.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs sm:text-sm font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 min-h-[44px]">
                                    <span>অর্ডার করুন</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                                <span class="text-xs text-slate-400 font-mono">🚚 ২-৩ দিনে ডেলিভারি</span>
                            </div>
                        </div>

                        {{-- Slide Background Photography --}}
                        <img 
                            src="/src/assets/images/hero_gadget_slider_1790972816745.jpg" 
                            alt="Cyclone Mart Electronics Deals" 
                            class="absolute right-0 top-0 bottom-0 h-full w-full sm:w-2/3 object-cover -z-10 opacity-40 sm:opacity-70 mix-blend-screen"
                        />
                    </div>

                    {{-- Slide 2: Smartwatch Special --}}
                    <div 
                        x-show="currentSlide === 1"
                        x-transition:enter="transition ease-out duration-400"
                        x-transition:enter-start="opacity-0 scale-98"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0 p-5 sm:p-10 flex flex-col justify-center text-white bg-gradient-to-r from-[#0F172A] via-[#0F172A]/90 to-transparent z-10"
                    >
                        <div class="max-w-md space-y-2">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#DC2626] text-white text-[11px] font-bold font-mono tracking-wide">
                                <span>⚡ লিমিটেড স্টক</span>
                                <span>·</span>
                                <span>৭ দিনের রিপ্লেসমেন্ট</span>
                            </div>
                            <h2 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                                প্রিমিয়াম AMOLED স্মার্টওয়াচ মাত্র ৳১,২৯০
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 line-clamp-2">
                                ব্লুটুথ কলিং, হেলথ ট্র্যাকার ও ওয়াটারপ্রুফ বডি। সীমিত সময়ের জন্য বিশেষ ছাড়।
                            </p>
                            <div class="pt-2 flex items-center gap-3">
                                <a href="#hot-deals" class="px-5 py-2.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs sm:text-sm font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 min-h-[44px]">
                                    <span>এখনই কিনুন</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>

                        <img 
                            src="/src/assets/images/smartwatch_ultra_black_1790972839119.jpg" 
                            alt="Smartwatch Sale Bangladesh" 
                            class="absolute right-0 top-0 bottom-0 h-full w-full sm:w-2/3 object-cover -z-10 opacity-35 sm:opacity-65 mix-blend-screen"
                        />
                    </div>

                    {{-- Slider Dots --}}
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
                        <button @click="currentSlide = 0" class="h-2 rounded-full transition-all" :class="currentSlide === 0 ? 'w-6 bg-[#DC2626]' : 'w-2 bg-white/40'"></button>
                        <button @click="currentSlide = 1" class="h-2 rounded-full transition-all" :class="currentSlide === 1 ? 'w-6 bg-[#DC2626]' : 'w-2 bg-white/40'"></button>
                    </div>

                    {{-- Slider Nav Arrows --}}
                    <button @click="prev()" class="hidden sm:flex absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white items-center justify-center z-20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="next()" class="hidden sm:flex absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/40 hover:bg-black/70 text-white items-center justify-center z-20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- 3 Quick USP Strips Below Hero --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                    <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0F172A] flex items-center justify-center font-bold text-sm shrink-0">
                            🚚
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">হোম ডেলিভারি</span>
                            <span class="text-[11px] text-slate-500">ঢাকার ভেতরে ৬০৳, বাইরে ১২০৳</span>
                        </div>
                    </div>

                    <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-[#DC2626] flex items-center justify-center font-bold text-sm shrink-0">
                            💵
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">ক্যাশ অন ডেলিভারি</span>
                            <span class="text-[11px] text-slate-500">পণ্য দেখে মূল্য পরিশোধের সুবিধা</span>
                        </div>
                    </div>

                    <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                            🔄
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">৭ দিনের রিপ্লেসমেন্ট</span>
                            <span class="text-[11px] text-slate-500">কোনো সমস্যা হলে দ্রুত পরিবর্তন</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- 4. Product Grid (Signature Style) --}}
        <section id="hot-deals" class="space-y-4">
            
            {{-- Section Bar Header --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-50 text-[#DC2626] flex items-center justify-center font-bold">
                        🔥
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <span>হট ডিলস ও সেরা অফারসমূহ</span>
                            <span class="text-[10px] font-mono bg-[#DC2626] text-white px-2 py-0.5 rounded font-bold uppercase">HOT</span>
                        </h2>
                        <p class="text-[11px] text-slate-500 hidden sm:block">স্টক শেষ হওয়ার আগেই অর্ডার নিশ্চিত করুন। সীমিত সময়ের অফার।</p>
                    </div>
                </div>

                <a href="/shop" class="text-xs font-bold text-[#DC2626] hover:text-[#B91C1C] flex items-center gap-1 hover:underline shrink-0">
                    <span>সবগুলো দেখুন</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- 
                Responsive Product Grid: 
                - 2 columns on mobile (320px-430px)
                - 3 to 4 columns on tablet
                - 5 to 6 columns on large desktop
            --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">

                {{-- Product Card 1 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        {{-- Discount Badge Top-Left --}}
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -৩৫%
                            </span>
                        </div>

                        {{-- Product Image Container --}}
                        <a href="/product/cyclone-tws-pro" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/tws_earbuds_pro_1790972827406.jpg" 
                                alt="Cyclone TWS Pro Wireless Earbuds" 
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        {{-- Title & Rating --}}
                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">TWS Audio</span>
                            <a href="/product/cyclone-tws-pro" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone TWS Pro নয়েজ ক্যানসেলিং ইয়ারবাডস
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 4.9</span>
                                <span class="text-[10px] text-slate-400">(১৮৫ বিক্রিত)</span>
                            </div>
                        </div>

                        {{-- Price Lockup (BDT) --}}
                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳৮৫০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳১,৩৫০
                            </span>
                        </div>
                    </div>

                    {{-- Two Prominent Action Buttons --}}
                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(1, 'Cyclone TWS Pro', 850)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                            title="Add to Cart"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=1" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                            title="Order Now Directly"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

                {{-- Product Card 2 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -৪০%
                            </span>
                        </div>

                        <a href="/product/cyclone-watch-ultra" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/smartwatch_ultra_black_1790972839119.jpg" 
                                alt="Cyclone Ultra AMOLED Smartwatch" 
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">Smartwatch</span>
                            <a href="/product/cyclone-watch-ultra" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone Watch Ultra AMOLED ব্লুটুথ কলিং
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 5.0</span>
                                <span class="text-[10px] text-slate-400">(২১২ বিক্রিত)</span>
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳১,২৯০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳২,১৫০
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(2, 'Cyclone Watch Ultra', 1290)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=2" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

                {{-- Product Card 3 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -২৮%
                            </span>
                        </div>

                        <a href="/product/cyclone-powerbank-20k" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/powerbank_fast_charge_1790972850858.jpg" 
                                alt="Cyclone 20000mAh Powerbank" 
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">Powerbank</span>
                            <a href="/product/cyclone-powerbank-20k" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone 20,000mAh 22.5W ফাস্ট চার্জিং পাওয়ার ব্যাংক
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 4.8</span>
                                <span class="text-[10px] text-slate-400">(১৪০ বিক্রিত)</span>
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳১,৫৫০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳২,১৫০
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(3, 'Cyclone 20K Powerbank', 1550)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=3" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

                {{-- Product Card 4 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -৩৩%
                            </span>
                        </div>

                        <a href="/product/cyclone-audio-max" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/hero_gadget_slider_1790972816745.jpg" 
                                alt="Cyclone Audio Max Headphones" 
                                class="w-full h-full object-cover rounded-lg group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">Headphones</span>
                            <a href="/product/cyclone-audio-max" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone Audio Max ওয়্যারলেস হেডফোন
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 4.9</span>
                                <span class="text-[10px] text-slate-400">(৯৮ বিক্রিত)</span>
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳১,৯৫০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳২,৯০০
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(4, 'Cyclone Audio Max', 1950)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=4" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

                {{-- Product Card 5 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -২২%
                            </span>
                        </div>

                        <a href="/product/cyclone-tws-mini" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/tws_earbuds_pro_1790972827406.jpg" 
                                alt="Cyclone Mini TWS" 
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">Compact TWS</span>
                            <a href="/product/cyclone-tws-mini" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone Mini পকেট সাইজ TWS ইয়ারবাডস
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 4.7</span>
                                <span class="text-[10px] text-slate-400">(৭৬ বিক্রিত)</span>
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳৬৯০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳৮৯০
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(5, 'Cyclone Mini TWS', 690)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=5" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

                {{-- Product Card 6 --}}
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        <div class="absolute top-3 left-3 z-10">
                            <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                -৪৫%
                            </span>
                        </div>

                        <a href="/product/cyclone-cable-65w" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            <img 
                                src="/src/assets/images/powerbank_fast_charge_1790972850858.jpg" 
                                alt="Cyclone 65W Fast Cable" 
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            />
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">Accessories</span>
                            <a href="/product/cyclone-cable-65w" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    Cyclone 65W টাইপ-সি ব্রেডেড ফাস্ট চার্জিং ক্যাবল
                                </h3>
                            </a>
                            <div class="flex items-center gap-1 text-[11px] text-amber-500 font-mono">
                                <span>★ 4.9</span>
                                <span class="text-[10px] text-slate-400">(৩৫০ বিক্রিত)</span>
                            </div>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳২৯০
                            </span>
                            <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                ৳৫২০
                            </span>
                        </div>
                    </div>

                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            @click="addToCart(6, 'Cyclone 65W Cable', 290)"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id=6" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        {{-- 5. Customer Trust Section (Bangladesh Dropshipping Assurance) --}}
        <section class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="text-center max-w-xl mx-auto space-y-1.5 mb-6">
                <span class="text-xs font-bold text-[#DC2626] uppercase font-mono tracking-wider">আমাদের প্রতিশ্রুতি</span>
                <h3 class="text-lg sm:text-xl font-bold text-[#0F172A]">কেন আপনি সাইক্লোন মার্ট থেকে অর্ডার করবেন?</h3>
                <p class="text-xs text-slate-500">আমরা গ্রাহকের শতভাগ সন্তুষ্টি ও আসল পণ্যের নিশ্চয়তা প্রদান করি।</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
                <div class="p-4 bg-slate-50 rounded-2xl space-y-2 border border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-red-50 text-[#DC2626] inline-flex items-center justify-center text-xl">
                        💵
                    </span>
                    <h4 class="text-xs sm:text-sm font-bold text-[#0F172A]">ক্যাশ অন ডেলিভারি</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">কোনো অগ্রিম পেমেন্ট নেই। পণ্য হাতে পেয়ে চেক করে টাকা দিন।</p>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl space-y-2 border border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-blue-50 text-[#0F172A] inline-flex items-center justify-center text-xl">
                        ⚡
                    </span>
                    <h4 class="text-xs sm:text-sm font-bold text-[#0F172A]">দ্রুত হোম ডেলিভারি</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">ঢাকার ভেতরে ২৪-৪৮ ঘণ্টা, ঢাকার বাইরে ২-৩ দিনে পৌঁছে যাবে।</p>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl space-y-2 border border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 inline-flex items-center justify-center text-xl">
                        🔄
                    </span>
                    <h4 class="text-xs sm:text-sm font-bold text-[#0F172A]">৭ দিনের রিটার্ন গ্যারান্টি</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">পণ্য পছন্দ না হলে বা ডিফেক্ট থাকলে সাথে সাথে রিপ্লেসমেন্ট।</p>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl space-y-2 border border-slate-100">
                    <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 inline-flex items-center justify-center text-xl">
                        🛡️
                    </span>
                    <h4 class="text-xs sm:text-sm font-bold text-[#0F172A]">১০০% অথেনটিক পণ্য</h4>
                    <p class="text-[11px] text-slate-500 leading-relaxed">প্রতিটি গ্যাজেট কঠোর কোয়ালিটি চেকের পর গ্রাহকের কাছে পৌঁছানো হয়।</p>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="bg-[#0F172A] text-slate-400 text-xs mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#DC2626] flex items-center justify-center text-white font-extrabold text-sm">
                        CM
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Cyclone<span class="text-[#DC2626]">Mart</span></span>
                </div>
                <p class="text-slate-400 text-xs leading-relaxed">
                    বাংলাদেশের দ্রুত বর্ধনশীল ড্রপশিপিং ও গ্যাজেট শপ। ক্যাশ অন ডেলিভারিতে সারাদেশে আসল পণ্যের নিশ্চয়তা।
                </p>
                <div class="text-slate-300 font-mono text-xs">
                    <span>হটলাইন: </span>
                    <a href="tel:01712345678" class="text-white font-bold hover:underline">০১৭১২-৩৪৫৬৭৮</a>
                </div>
            </div>

            <div>
                <h5 class="text-white font-bold mb-3 text-sm">গুরুত্বপূর্ণ লিঙ্ক</h5>
                <ul class="space-y-2">
                    <li><a href="/shop" class="hover:text-white transition-colors">সকল পণ্যসমূহ</a></li>
                    <li><a href="/track" class="hover:text-white transition-colors">অর্ডার ট্র্যাকিং</a></li>
                    <li><a href="/cart" class="hover:text-white transition-colors">শপিং কার্ট</a></li>
                    <li><a href="/checkout" class="hover:text-white transition-colors">চেকআউট</a></li>
                </ul>
            </div>

            <div>
                <h5 class="text-white font-bold mb-3 text-sm">গ্রাহক সেবা ও পলিসি</h5>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">ক্যাশ অন ডেলিভারি পলিসি</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">৭ দিনের রিটার্ন ও রিফান্ড</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">শিপিং ও ডেলিভারি চার্জ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">প্রাইভেসি পলিসি</a></li>
                </ul>
            </div>

            <div>
                <h5 class="text-white font-bold mb-3 text-sm">অফিস ও যোগাযোগ</h5>
                <p class="text-slate-400 text-xs leading-relaxed mb-3">
                    হাউজ #১২, রোড #০৪, সেক্টর #১১, উত্তরা, ঢাকা-১২৩০, বাংলাদেশ।
                </p>
                <div class="flex items-center gap-2 text-white text-xs">
                    <span class="bg-slate-800 p-2 rounded-lg font-mono">bKash</span>
                    <span class="bg-slate-800 p-2 rounded-lg font-mono">Nagad</span>
                    <span class="bg-slate-800 p-2 rounded-lg font-mono">COD</span>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800 py-4 text-center text-[11px] text-slate-500">
            © ২০২৬ Cyclone Mart Bangladesh. সর্বস্বত্ব সংরক্ষিত।
        </div>
    </footer>

    {{-- Mobile Floating Bottom Navigation Bar (Essential Mobile User Experience) --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 py-1.5 px-4 flex items-center justify-around text-slate-600 shadow-lg">
        <a href="/" class="flex flex-col items-center gap-0.5 text-[#DC2626]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-bold">হোম</span>
        </a>

        <a href="#hot-deals" class="flex flex-col items-center gap-0.5 hover:text-[#0F172A]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            <span class="text-[10px] font-medium">ক্যাটাগরি</span>
        </a>

        <a href="/track" class="flex flex-col items-center gap-0.5 hover:text-[#0F172A]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span class="text-[10px] font-medium">ট্র্যাকিং</span>
        </a>

        <a href="/cart" class="flex flex-col items-center gap-0.5 relative hover:text-[#0F172A]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span 
                class="absolute -top-1 right-2 bg-[#DC2626] text-white text-[9px] font-mono font-bold w-4 h-4 rounded-full flex items-center justify-center"
                x-text="cartCount"
            ></span>
            <span class="text-[10px] font-medium">কার্ট</span>
        </a>

        <a href="tel:01712345678" class="flex flex-col items-center gap-0.5 text-emerald-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span class="text-[10px] font-bold">কল করুন</span>
        </a>
    </nav>

</body>
</html>
