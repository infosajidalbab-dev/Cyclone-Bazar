<!DOCTYPE html>
<html lang="bn-BD" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ $product->name }} — Cyclone Mart Bangladesh</title>
    <meta name="description" content="{{ $product->short_description ?? 'অরিজিনাল প্রোডাক্ট ক্যাশ অন ডেলিভারিতে অর্ডার করুন সারা বাংলাদেশে।' }}">
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
<body class="text-slate-900 antialiased pb-24 md:pb-12" x-data="productDetail({{ json_encode([
    'product' => $product,
    'variants' => $product->variants,
    'images' => $product->images->pluck('image_path')->toArray() ?: ['/placeholder.png'],
    'basePrice' => (float) $product->selling_price,
    'comparePrice' => (float) ($product->compare_at_price ?? $product->selling_price),
    'stock' => $product->stock_quantity,
]) }})">

    {{-- Top App Bar (Pattern 2 Mobile Top Bar) --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="/" class="flex items-center gap-1.5 text-[#0F4C81] font-bold text-base tracking-tight">
                <span>Cyclone Mart</span>
                <span class="text-[9px] bg-[#FF6B35] text-white px-1.5 py-0.5 rounded font-mono">BD</span>
            </a>

            <div class="flex items-center gap-3">
                <a href="/cart" class="relative p-2 text-slate-600 hover:text-slate-900 min-h-[44px] min-w-[44px] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="absolute top-1 right-1 w-4 h-4 bg-[#FF6B35] text-white text-[10px] rounded-full flex items-center justify-center font-mono font-bold" x-text="cartCount">1</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-4 sm:py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">
            
            {{-- Image Gallery (Mobile Touch Swipe & Desktop Thumbnails) --}}
            <div class="space-y-3">
                {{-- Main Image Stage --}}
                <div class="relative bg-white rounded-2xl border border-slate-200 aspect-square overflow-hidden shadow-xs flex items-center justify-center p-4">
                    <img 
                        :src="images[activeImageIndex] || '/placeholder.png'" 
                        :alt="product.name"
                        class="w-full h-full object-contain transition-all duration-300"
                    />

                    {{-- Discount Badge (Top Left) --}}
                    <template x-if="discountPercentage > 0">
                        <div class="absolute top-3 left-3 bg-[#FF6B35] text-white text-xs font-bold font-mono px-2.5 py-1 rounded-md shadow-sm">
                            <span x-text="'-' + discountPercentage + '% ছাড়'"></span>
                        </div>
                    </template>

                    {{-- Stock Badge (Top Right) --}}
                    <div class="absolute top-3 right-3 text-[11px] font-mono px-2.5 py-1 rounded-md bg-white/90 backdrop-blur-xs border border-slate-200">
                        <span x-show="currentStock > 0" class="text-emerald-700 font-semibold">ইন স্টক (In Stock)</span>
                        <span x-show="currentStock <= 0" class="text-rose-600 font-semibold">স্টক শেষ (Out of Stock)</span>
                    </div>

                    {{-- Left/Right Navigation Arrows --}}
                    <button 
                        @click="prevImage" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-white shadow-xs"
                        aria-label="Previous image"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button 
                        @click="nextImage" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 border border-slate-200 flex items-center justify-center text-slate-700 hover:bg-white shadow-xs"
                        aria-label="Next image"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- Thumbnail Scroller --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1">
                    <template x-for="(img, idx) in images" :key="idx">
                        <button 
                            @click="activeImageIndex = idx"
                            class="w-16 h-16 rounded-xl border-2 overflow-hidden shrink-0 transition-all bg-white"
                            :class="activeImageIndex === idx ? 'border-[#0F4C81] ring-2 ring-blue-100' : 'border-slate-200 opacity-60 hover:opacity-100'"
                        >
                            <img :src="img" class="w-full h-full object-cover" />
                        </button>
                    </template>
                </div>
            </div>

            {{-- Product Info & Purchase Controls --}}
            <div class="space-y-5">
                {{-- Category & SKU Meta --}}
                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                    <span class="text-[#0F4C81] font-semibold">{{ $product->category->name ?? 'General' }}</span>
                    <span>·</span>
                    <span class="font-mono">SKU: <span x-text="currentSku"></span></span>
                    <span>·</span>
                    <span class="text-[#28A745] font-semibold">100% Authentic</span>
                </div>

                {{-- Product Title --}}
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 leading-snug">
                    {{ $product->name }}
                </h1>

                {{-- Price Block (BDT Currency) --}}
                <div class="p-4 bg-white border border-slate-200 rounded-2xl flex items-baseline justify-between shadow-xs">
                    <div>
                        <span class="text-xs text-slate-400 block mb-0.5">অফার মূল্য (Offer Price)</span>
                        <div class="flex items-baseline gap-2.5">
                            <span class="text-2xl sm:text-3xl font-extrabold font-mono text-[#0F4C81]">
                                ৳<span x-text="currentPrice.toLocaleString()"></span>
                            </span>
                            <template x-if="comparePrice > currentPrice">
                                <span class="text-sm font-mono text-slate-400 line-through">
                                    ৳<span x-text="comparePrice.toLocaleString()"></span>
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-block px-2.5 py-1 bg-emerald-50 text-[#28A745] border border-emerald-200 rounded-lg text-xs font-semibold">
                            ক্যাশ অন ডেলিভারি
                        </span>
                    </div>
                </div>

                {{-- Variant Selector (Color / Size) --}}
                <template x-if="variants && variants.length > 0">
                    <div class="space-y-3 bg-white p-4 rounded-2xl border border-slate-200">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-800">ভ্যারিয়েন্ট সিলেক্ট করুন (Select Variant):</label>
                            <span class="text-xs text-[#0F4C81] font-semibold font-mono" x-text="selectedVariantTitle"></span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <template x-for="(variant, idx) in variants" :key="variant.id">
                                <button 
                                    type="button" 
                                    @click="selectVariant(variant)"
                                    class="p-2.5 rounded-xl border text-xs font-medium text-left transition-all min-h-[44px] flex flex-col justify-between"
                                    :class="selectedVariantId === variant.id ? 'border-[#0F4C81] bg-blue-50/60 ring-2 ring-blue-100 text-[#0F4C81]' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                                >
                                    <span class="font-semibold block truncate" x-text="variant.title"></span>
                                    <span class="font-mono text-[11px] text-slate-500" x-text="'৳' + variant.selling_price"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Quantity Stepper --}}
                <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">পরিমাণ (Quantity)</span>
                        <span class="text-[11px] text-slate-400">সর্বোচ্চ <span x-text="currentStock"></span> টি উপলব্ধ</span>
                    </div>

                    <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-slate-50">
                        <button 
                            @click="decrementQty" 
                            class="w-11 h-11 flex items-center justify-center text-slate-700 hover:bg-slate-200 text-lg font-bold min-h-[44px] min-w-[44px] transition-colors"
                            aria-label="Decrease quantity"
                        >-</button>
                        <span class="w-12 text-center font-mono font-bold text-sm text-slate-900" x-text="quantity"></span>
                        <button 
                            @click="incrementQty" 
                            class="w-11 h-11 flex items-center justify-center text-slate-700 hover:bg-slate-200 text-lg font-bold min-h-[44px] min-w-[44px] transition-colors"
                            aria-label="Increase quantity"
                        >+</button>
                    </div>
                </div>

                {{-- Desktop CTAs (Hidden on mobile where sticky bottom CTA applies) --}}
                <div class="hidden md:grid grid-cols-2 gap-3 pt-2">
                    <button 
                        @click="addToCart" 
                        class="min-h-[48px] px-6 rounded-xl border-2 border-[#0F4C81] text-[#0F4C81] font-bold text-sm hover:bg-blue-50 transition-colors flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>কার্ট-এ যোগ করুন (Add to Cart)</span>
                    </button>

                    <button 
                        @click="buyNow" 
                        class="min-h-[48px] px-6 rounded-xl bg-[#FF6B35] hover:bg-[#e85520] text-white font-bold text-sm shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2"
                    >
                        <span>এখনই কিনুন (অর্ডার করুন)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>

                {{-- Delivery Charges & Timeline Table --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-900 border-b border-slate-100 pb-2">
                        <svg class="w-4 h-4 text-[#0F4C81]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        <span>ডেলিভারি চার্জ ও সময় (Delivery Charges)</span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 font-medium">
                            <span class="text-slate-700">ঢাকা সিটির ভিতরে (Inside Dhaka)</span>
                            <div class="text-right">
                                <span class="font-mono font-bold text-[#0F4C81]">৳৬০</span>
                                <span class="text-[10px] text-slate-400 block font-sans">১-২ কর্মদিবস</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 font-medium">
                            <span class="text-slate-700">ঢাকা সাব-এরিয়া (Gazipur, Savar, N'Ganj)</span>
                            <div class="text-right">
                                <span class="font-mono font-bold text-[#0F4C81]">৳১০০</span>
                                <span class="text-[10px] text-slate-400 block font-sans">২-৩ কর্মদিবস</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 font-medium">
                            <span class="text-slate-700">ঢাকার বাইরে সারা বাংলাদেশ (Outside Dhaka)</span>
                            <div class="text-right">
                                <span class="font-mono font-bold text-[#0F4C81]">৳১৩০</span>
                                <span class="text-[10px] text-slate-400 block font-sans">২-৪ কর্মদিবস</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trust Markers & Guarantee Tabs --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex border-b border-slate-200">
                        <button 
                            @click="activePolicyTab = 'replacement'" 
                            class="flex-1 py-2 text-xs font-bold text-center border-b-2 transition-colors min-h-[44px]"
                            :class="activePolicyTab === 'replacement' ? 'border-[#0F4C81] text-[#0F4C81]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        >
                            রিপ্লেসমেন্ট গ্যারান্টি
                        </button>
                        <button 
                            @click="activePolicyTab = 'inspection'" 
                            class="flex-1 py-2 text-xs font-bold text-center border-b-2 transition-colors min-h-[44px]"
                            :class="activePolicyTab === 'inspection' ? 'border-[#0F4C81] text-[#0F4C81]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        >
                            দেখে নেওয়ার সুবিধা
                        </button>
                        <button 
                            @click="activePolicyTab = 'payment'" 
                            class="flex-1 py-2 text-xs font-bold text-center border-b-2 transition-colors min-h-[44px]"
                            :class="activePolicyTab === 'payment' ? 'border-[#0F4C81] text-[#0F4C81]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                        >
                            পেমেন্ট মাধ্যম
                        </button>
                    </div>

                    <div class="text-xs text-slate-600 leading-relaxed min-h-[60px]">
                        <div x-show="activePolicyTab === 'replacement'">
                            প্রোডাক্টে কোনো ধরণের ত্রুটি বা সমস্যা থাকলে ডেলিভারি পাওয়ার পর <strong>৭ দিনের মধ্যে</strong> সম্পূর্ণ ফ্রিতে রিপ্লেসমেন্ট সুবিধা পাবেন।
                        </div>
                        <div x-show="activePolicyTab === 'inspection'">
                            ডেলিভারি রাইডারের সামনে পার্সেল খুলে চেক করে প্রোডাক্ট ঠিক থাকলে ক্যাশ পেমেন্ট করবেন। কোনো অগ্রিম ফি প্রয়োজন নেই।
                        </div>
                        <div x-show="activePolicyTab === 'payment'">
                            ক্যাশ অন ডেলিভারি (Cash on Delivery), বিকাশ (bKash), বা নগদ (Nagad)-এর মাধ্যমে ডেলিভারি প্রতিনিধির কাছে সহজে মূল্য পরিশোধ করুন।
                        </div>
                    </div>
                </div>

                {{-- Detailed Description / Specs --}}
                <div class="bg-white p-5 rounded-2xl border border-slate-200 space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">পণ্যের বিবরণ (Description)</h3>
                    <div class="text-xs text-slate-700 leading-relaxed space-y-2">
                        <p>{{ $product->description ?: $product->short_description ?: 'অরিজিনাল ব্র্যান্ডের নতুন প্রোডাক্ট।' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Sticky Mobile CTA Bar (Layout C & 15% Viewport Cap Discipline) --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2.5 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="shrink-0 font-mono">
                <span class="text-[10px] text-slate-400 block">মোট মূল্য</span>
                <span class="text-base font-extrabold text-[#0F4C81]">৳<span x-text="(currentPrice * quantity).toLocaleString()"></span></span>
            </div>

            <button 
                @click="buyNow" 
                class="flex-1 min-h-[44px] bg-[#FF6B35] active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md transition-transform"
            >
                <span>ক্যাশ অন ডেলিভারি অর্ডার</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>

    {{-- Alpine Component Logic --}}
    <script>
        function productDetail(data) {
            return {
                product: data.product,
                variants: data.variants || [],
                images: data.images,
                activeImageIndex: 0,
                selectedVariantId: data.variants && data.variants.length > 0 ? data.variants[0].id : null,
                selectedVariantTitle: data.variants && data.variants.length > 0 ? data.variants[0].title : '',
                currentPrice: data.variants && data.variants.length > 0 ? parseFloat(data.variants[0].selling_price) : data.basePrice,
                comparePrice: data.comparePrice,
                currentStock: data.variants && data.variants.length > 0 ? data.variants[0].stock_quantity : data.stock,
                currentSku: data.variants && data.variants.length > 0 ? data.variants[0].sku : data.product.sku,
                quantity: 1,
                cartCount: 1,
                activePolicyTab: 'replacement',

                get discountPercentage() {
                    if (this.comparePrice <= this.currentPrice) return 0;
                    return Math.round(((this.comparePrice - this.currentPrice) / this.comparePrice) * 100);
                },

                selectVariant(variant) {
                    this.selectedVariantId = variant.id;
                    this.selectedVariantTitle = variant.title;
                    this.currentPrice = parseFloat(variant.selling_price);
                    this.currentStock = variant.stock_quantity;
                    this.currentSku = variant.sku;
                    if (this.quantity > this.currentStock && this.currentStock > 0) {
                        this.quantity = this.currentStock;
                    }
                },

                prevImage() {
                    this.activeImageIndex = (this.activeImageIndex - 1 + this.images.length) % this.images.length;
                },

                nextImage() {
                    this.activeImageIndex = (this.activeImageIndex + 1) % this.images.length;
                },

                incrementQty() {
                    if (this.quantity < this.currentStock) {
                        this.quantity++;
                    }
                },

                decrementQty() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },

                addToCart() {
                    this.cartCount += this.quantity;
                    alert('কার্টে যোগ করা হয়েছে! (Added to cart: ' + this.quantity + ' items)');
                },

                buyNow() {
                    // Redirect to 1-Click COD checkout flow
                    window.location.href = '/checkout?product_id=' + this.product.id + '&variant_id=' + (this.selectedVariantId || '') + '&qty=' + this.quantity;
                }
            };
        }
    </script>
</body>
</html>
