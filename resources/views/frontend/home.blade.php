<!DOCTYPE html>
<html lang="bn-BD" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ \App\Models\Setting::get('store_name', 'Cyclone Mart') }} — বাংলাদেশের বিশ্বস্ত অনলাইন শপিং</title>
    <meta name="description" content="ক্যাশ অন ডেলিভারিতে অরিজিনাল গ্যাজেট ও ইলেকট্রনিক্স অর্ডার করুন সারা বাংলাদেশে। ৭ দিনের রিপ্লেসমেন্ট গ্যারান্টি।">
    @include('partials.seo', [
        'title' => 'Cyclone Mart — সেরা দামে অনলাইন শপিং বাংলাদেশ',
        'description' => 'ক্যাশ অন ডেলিভারিতে অর্ডার করুন জেনুইন গ্যাজেট ও পণ্য। কোনো অগ্রিম পেমেন্ট নেই।',
    ])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="text-slate-900 antialiased pb-20 md:pb-8 flex flex-col min-h-screen">
    {{-- Top App Bar --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center gap-1.5 text-[#0F4C81] font-extrabold text-base tracking-tight">
                <span>{{ \App\Models\Setting::get('store_name', 'Cyclone Mart') }}</span>
                <span class="text-[9px] bg-[#FF6B35] text-white px-1.5 py-0.5 rounded font-mono font-bold">BD</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="/track" class="text-xs font-semibold text-slate-600 hover:text-[#0F4C81] flex items-center gap-1 min-h-[44px]">
                    <svg class="w-4 h-4 text-[#0F4C81]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <span class="hidden sm:inline">অর্ডার ট্র্যাকিং</span>
                </a>

                <a href="/cart" class="relative p-2 text-slate-700 min-h-[44px] min-w-[44px] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-5 space-y-8 flex-1">
        {{-- Hero Slider (Dynamic from Banners Table: position = hero) --}}
        @php
            $heroBanners = \App\Models\Banner::active()->position('hero')->get();
        @endphp

        @if ($heroBanners->count() > 0)
            <div class="relative rounded-3xl overflow-hidden shadow-xs bg-slate-900 aspect-[16/7] sm:aspect-[21/9]" x-data="{
                activeSlide: 0,
                total: {{ $heroBanners->count() }},
                next() { this.activeSlide = (this.activeSlide + 1) % this.total },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.total) % this.total }
            }">
                @foreach ($heroBanners as $idx => $banner)
                    <div 
                        x-show="activeSlide === {{ $idx }}" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="absolute inset-0 flex items-center justify-between p-6 sm:p-12 text-white bg-gradient-to-r from-slate-950 via-slate-900 to-transparent"
                    >
                        <div class="max-w-lg space-y-2 z-10">
                            <span class="text-[10px] sm:text-xs font-mono font-bold text-[#FF6B35] uppercase tracking-wider bg-orange-500/10 px-2 py-0.5 rounded">
                                ধামাকা অফার (Exclusive)
                            </span>
                            <h2 class="text-lg sm:text-3xl font-extrabold tracking-tight leading-tight">
                                {{ $banner->title }}
                            </h2>
                            @if ($banner->subtitle)
                                <p class="text-xs sm:text-sm text-slate-300 hidden sm:block">
                                    {{ $banner->subtitle }}
                                </p>
                            @endif
                            <div class="pt-2">
                                <a href="{{ $banner->target_url ?: '/shop' }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#FF6B35] hover:bg-[#e85520] text-white text-xs font-bold rounded-xl shadow-md min-h-[44px]">
                                    <span>অর্ডার করুন (Shop Now)</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>

                        <img src="{{ $banner->image_path }}" class="absolute right-0 top-0 bottom-0 h-full w-2/3 object-cover opacity-60 mix-blend-screen" />
                    </div>
                @endforeach

                {{-- Slider Nav Dots --}}
                @if ($heroBanners->count() > 1)
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
                        @foreach ($heroBanners as $idx => $b)
                            <button 
                                @click="activeSlide = {{ $idx }}" 
                                class="w-2 h-2 rounded-full transition-all"
                                :class="activeSlide === {{ $idx }} ? 'w-5 bg-[#FF6B35]' : 'bg-white/50'"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Dynamic Category Chips --}}
        @php
            $categories = \App\Models\Category::active()->take(8)->get();
        @endphp
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">ক্যাটাগরি সমূহ (Shop By Category)</h3>
                <span class="text-xs text-slate-400 font-mono">{{ $categories->count() }} Categories</span>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-8 gap-2.5">
                @foreach ($categories as $cat)
                    <a href="/category/{{ $cat->slug }}" class="p-3 bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center gap-1.5 hover:border-[#0F4C81] hover:shadow-xs transition-all group min-h-[44px]">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0F4C81] flex items-center justify-center group-hover:scale-105 transition-transform">
                            <span class="text-xs font-bold font-mono">{{ substr($cat->name, 0, 2) }}</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-800 text-center truncate w-full">
                            {{ $cat->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Today's Featured Deals (Dynamic Products) --}}
        @php
            $featuredProducts = \App\Models\Product::with(['primaryImage', 'variants'])->featured()->take(8)->get();
        @endphp
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-1 border-b border-slate-200">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>হট ডিলস (Featured Drops)</span>
                        <span class="text-[10px] font-mono bg-[#FF6B35] text-white px-2 py-0.5 rounded font-bold">HOT</span>
                    </h3>
                    <p class="text-xs text-slate-500">সীমিত সময়ের অফার। স্টক শেষ হওয়ার আগেই অর্ডার করুন।</p>
                </div>
                <a href="/shop" class="text-xs font-semibold text-[#0F4C81] hover:underline">সব পণ্য দেখুন →</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                @foreach ($featuredProducts as $product)
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-md transition-shadow flex flex-col justify-between group">
                        <a href="/product/{{ $product->slug }}" class="block p-3">
                            {{-- Image Stage --}}
                            <div class="aspect-square bg-slate-50 rounded-xl overflow-hidden mb-2.5 flex items-center justify-center p-2 relative">
                                @if ($product->primaryImage)
                                    <img src="{{ $product->primaryImage->image_path }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform" />
                                @else
                                    <span class="text-xs font-mono font-bold text-slate-400">NO IMAGE</span>
                                @endif

                                @if ($product->compare_at_price > $product->selling_price)
                                    @php
                                        $discount = round((($product->compare_at_price - $product->selling_price) / $product->compare_at_price) * 100);
                                    @endphp
                                    <span class="absolute top-2 left-2 bg-[#FF6B35] text-white text-[10px] font-bold font-mono px-1.5 py-0.5 rounded">
                                        -{{ $discount }}%
                                    </span>
                                @endif
                            </div>

                            <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-[#0F4C81] transition-colors">
                                {{ $product->name }}
                            </h4>

                            <div class="flex items-baseline gap-2 mt-2 font-mono">
                                <span class="text-sm font-extrabold text-[#0F4C81]">
                                    ৳{{ number_format($product->selling_price, 0) }}
                                </span>
                                @if ($product->compare_at_price > $product->selling_price)
                                    <span class="text-xs text-slate-400 line-through">
                                        ৳{{ number_format($product->compare_at_price, 0) }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <div class="p-3 pt-0">
                            <a href="/product/{{ $product->slug }}" class="w-full py-2 bg-blue-50 hover:bg-[#0F4C81] hover:text-white text-[#0F4C81] text-xs font-bold rounded-xl flex items-center justify-center gap-1 transition-colors min-h-[44px]">
                                <span>অর্ডার করুন (Buy Now)</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 4 Trust Pillars (Bangladesh E-Commerce Assurance) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4">
            <div class="p-4 bg-white border border-slate-200 rounded-2xl text-center space-y-1">
                <span class="text-base">🚚</span>
                <h5 class="text-xs font-bold text-slate-900">দ্রুত ডেলিভারি</h5>
                <p class="text-[10px] text-slate-500">সারা বাংলাদেশে ২-৩ দিনে হোম ডেলিভারি</p>
            </div>
            <div class="p-4 bg-white border border-slate-200 rounded-2xl text-center space-y-1">
                <span class="text-base">💵</span>
                <h5 class="text-xs font-bold text-slate-900">ক্যাশ অন ডেলিভারি</h5>
                <p class="text-[10px] text-slate-500">পণ্য দেখে নিয়ে মূল্য পরিশোধ করুন</p>
            </div>
            <div class="p-4 bg-white border border-slate-200 rounded-2xl text-center space-y-1">
                <span class="text-base">🔄</span>
                <h5 class="text-xs font-bold text-slate-900">৭ দিনের গ্যারান্টি</h5>
                <p class="text-[10px] text-slate-500">সহজ ও দ্রুত রিপ্লেসমেন্ট পলিসি</p>
            </div>
            <div class="p-4 bg-white border border-slate-200 rounded-2xl text-center space-y-1">
                <span class="text-base">🛡️</span>
                <h5 class="text-xs font-bold text-slate-900">১০০% অথেনটিক</h5>
                <p class="text-[10px] text-slate-500">জেনুইন মানের পণ্যের নিশ্চয়তা</p>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="mt-auto bg-white border-t border-slate-200 py-8">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                <span class="font-bold text-slate-800">{{ \App\Models\Setting::get('store_name', 'Cyclone Mart') }}</span>
                <span>— হেল্পলাইন: {{ \App\Models\Setting::get('contact_phone', '01712345678') }}</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="/track" class="hover:underline">অর্ডার ট্র্যাকিং</a>
                <a href="/cart" class="hover:underline">শপিং কার্ট</a>
                <a href="/admin/dashboard" class="hover:underline text-[#0F4C81] font-semibold">এডমিন প্যানেল</a>
            </div>
        </div>
    </footer>
</body>
</html>
