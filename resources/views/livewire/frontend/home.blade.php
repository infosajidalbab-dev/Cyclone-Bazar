<div class="space-y-6" x-data="{
    cartCount: @entangle('cartCount').live,
    activeCat: 'all'
}">
    {{-- 1. Hero Section (Category Sidebar Left + Promotional Slider Right) --}}
    <section class="grid grid-cols-12 gap-4 lg:gap-6 items-start">
        {{-- Left: Category Sidebar --}}
        <aside class="hidden lg:block lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="bg-[#0F172A] text-white px-4 py-3 flex items-center gap-2.5 font-bold text-sm">
                <svg class="w-4 h-4 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>সকল ক্যাটাগরি (Categories)</span>
            </div>

            <nav class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                @foreach ($categories as $cat)
                    <a href="/category/{{ $cat->slug }}" class="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors group">
                        <span class="flex items-center gap-2.5">
                            <span class="text-sm font-bold text-slate-400 group-hover:text-[#DC2626] font-mono">#</span>
                            <span>{{ $cat->name }}</span>
                        </span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
                <a href="#hot-deals" class="px-4 py-2.5 flex items-center justify-between bg-red-50/50 text-[#DC2626] font-bold hover:bg-red-50 transition-colors">
                    <span class="flex items-center gap-2">
                        <span>🔥</span>
                        <span>হট ডিলস ও মেগা ডিসকাউন্ট</span>
                    </span>
                    <span class="text-[10px] font-mono bg-[#DC2626] text-white px-1.5 py-0.5 rounded">HOT</span>
                </a>
            </nav>
        </aside>

        {{-- Right: Promotional Slider & USPs --}}
        <div class="col-span-12 lg:col-span-9 space-y-4">
            <div 
                class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs bg-[#0F172A] aspect-[16/8] sm:aspect-[21/9]"
                x-data="{
                    activeIdx: 0,
                    total: {{ max($heroBanners->count(), 1) }},
                    next() { this.activeIdx = (this.activeIdx + 1) % this.total }
                }"
            >
                @forelse ($heroBanners as $idx => $b)
                    <div 
                        x-show="activeIdx === {{ $idx }}"
                        class="absolute inset-0 p-5 sm:p-10 flex flex-col justify-center text-white bg-gradient-to-r from-[#0F172A] via-[#0F172A]/90 to-transparent z-10"
                    >
                        <div class="max-w-md space-y-2">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#DC2626] text-white text-[11px] font-bold font-mono">
                                <span>🔥 বিশেষ অফার</span>
                            </div>
                            <h2 class="text-xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                                {{ $b->title }}
                            </h2>
                            @if ($b->subtitle)
                                <p class="text-xs sm:text-sm text-slate-300 line-clamp-2">{{ $b->subtitle }}</p>
                            @endif
                            <div class="pt-2">
                                <a href="{{ $b->target_url ?: '#hot-deals' }}" class="px-5 py-2.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs sm:text-sm font-bold rounded-xl shadow-md inline-flex items-center gap-1.5 min-h-[44px]">
                                    <span>অর্ডার করুন</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>

                        @if ($b->image_path)
                            <img src="{{ $b->image_path }}" class="absolute right-0 top-0 bottom-0 h-full w-full sm:w-2/3 object-cover -z-10 opacity-50 mix-blend-screen" />
                        @endif
                    </div>
                @empty
                    <div class="absolute inset-0 p-6 sm:p-10 flex flex-col justify-center text-white bg-gradient-to-r from-[#0F172A] via-[#0F172A]/90 to-transparent">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#DC2626] text-white text-[11px] font-bold font-mono w-max">
                            🔥 ধামাকা অফার · ৫০% পর্যন্ত ছাড়
                        </span>
                        <h2 class="text-xl sm:text-3xl font-extrabold mt-2 tracking-tight">
                            অরিজিনাল TWS ও গ্যাজেট কালেকশন
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 mt-1">ক্যাশ অন ডেলিভারিতে সারাদেশে সুপারফাস্ট হোম ডেলিভারি।</p>
                        <div class="pt-3">
                            <a href="#hot-deals" class="px-5 py-2.5 bg-[#DC2626] text-white text-xs font-bold rounded-xl shadow-md inline-flex items-center gap-1.5 min-h-[44px]">
                                <span>অর্ডার করুন</span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- 3 Quick USP Strips --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#0F172A] flex items-center justify-center font-bold shrink-0">🚚</div>
                    <div>
                        <span class="font-bold text-slate-800 block">হোম ডেলিভারি</span>
                        <span class="text-[11px] text-slate-500">ঢাকার ভেতরে ৬০৳, বাইরে ১২০৳</span>
                    </div>
                </div>
                <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-red-50 text-[#DC2626] flex items-center justify-center font-bold shrink-0">💵</div>
                    <div>
                        <span class="font-bold text-slate-800 block">ক্যাশ অন ডেলিভারি</span>
                        <span class="text-[11px] text-slate-500">পণ্য দেখে মূল্য পরিশোধ করুন</span>
                    </div>
                </div>
                <div class="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0">🔄</div>
                    <div>
                        <span class="font-bold text-slate-800 block">৭ দিনের রিপ্লেসমেন্ট</span>
                        <span class="text-[11px] text-slate-500">সহজ ও দ্রুত রিটার্ন পলিসি</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Product Grid --}}
    <section id="hot-deals" class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-red-50 text-[#DC2626] flex items-center justify-center font-bold">🔥</div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#0F172A] flex items-center gap-2">
                        <span>হট ডিলস ও সেরা অফারসমূহ</span>
                        <span class="text-[10px] font-mono bg-[#DC2626] text-white px-2 py-0.5 rounded font-bold uppercase">HOT</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 hidden sm:block">স্টক শেষ হওয়ার আগেই অর্ডার নিশ্চিত করুন।</p>
                </div>
            </div>

            <a href="/shop" class="text-xs font-bold text-[#DC2626] hover:text-[#B91C1C] flex items-center gap-1 hover:underline">
                <span>সবগুলো দেখুন</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Responsive Grid: 2 cols on mobile, up to 6 on desktop --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
            @foreach ($featuredProducts as $p)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div class="relative p-2.5">
                        @if ($p->compare_at_price > $p->selling_price)
                            @php
                                $pct = round((($p->compare_at_price - $p->selling_price) / $p->compare_at_price) * 100);
                            @endphp
                            <div class="absolute top-3 left-3 z-10">
                                <span class="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                                    -{{ $pct }}%
                                </span>
                            </div>
                        @endif

                        <a href="/product/{{ $p->slug }}" class="block aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative">
                            @if ($p->primaryImage)
                                <img src="{{ $p->primaryImage->image_path }}" alt="{{ $p->name }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <span class="text-xs font-mono font-bold text-slate-400">NO IMAGE</span>
                            @endif
                        </a>

                        <div class="mt-2.5 space-y-1">
                            <span class="text-[10px] text-slate-400 font-mono uppercase block">{{ $p->category->name ?? 'Gadget' }}</span>
                            <a href="/product/{{ $p->slug }}" class="block">
                                <h3 class="text-xs sm:text-sm font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                                    {{ $p->name }}
                                </h3>
                            </a>
                        </div>

                        <div class="mt-2 flex items-baseline gap-2 font-mono">
                            <span class="text-sm sm:text-base font-extrabold text-[#DC2626]">
                                ৳{{ number_format($p->selling_price, 0) }}
                            </span>
                            @if ($p->compare_at_price > $p->selling_price)
                                <span class="text-[11px] sm:text-xs text-slate-400 line-through">
                                    ৳{{ number_format($p->compare_at_price, 0) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Two Prominent Buttons: কার্ট and অর্ডার করুন --}}
                    <div class="p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                        <button 
                            type="button"
                            wire:click="$dispatch('add-to-cart', { id: {{ $p->id }} })"
                            class="w-full py-2 px-1.5 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] sm:text-xs font-semibold transition-colors flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>কার্ট</span>
                        </button>
                        
                        <a 
                            href="/checkout?product_id={{ $p->id }}" 
                            class="w-full py-2 px-1.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] sm:text-xs font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[40px] sm:min-h-[42px]"
                        >
                            <span>অর্ডার করুন</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
