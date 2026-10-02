import React, { useState } from 'react';
import { ShoppingCart, Search, Phone, ArrowRight, Check, Flame, RotateCcw, ShieldCheck, Truck, CreditCard, ChevronRight, Menu, Smartphone, Monitor } from 'lucide-react';

interface ProductItem {
  id: number;
  name: string;
  category: string;
  regularPrice: number;
  salePrice: number;
  discountPct: number;
  rating: number;
  salesCount: number;
  image: string;
}

export const StorefrontHomePreview: React.FC = () => {
  const [deviceMode, setDeviceMode] = useState<'desktop' | 'mobile'>('desktop');
  const [cartCount, setCartCount] = useState<number>(2);
  const [cartTotal, setCartTotal] = useState<number>(2140);
  const [activeCategory, setActiveCategory] = useState<string>('all');
  const [searchQuery, setSearchQuery] = useState<string>('');
  const [toastMessage, setToastMessage] = useState<string | null>(null);
  const [activeSlide, setActiveSlide] = useState<number>(0);

  const products: ProductItem[] = [
    {
      id: 1,
      name: 'Cyclone TWS Pro নয়েজ ক্যানসেলিং ইয়ারবাডস',
      category: 'TWS Audio',
      regularPrice: 1350,
      salePrice: 850,
      discountPct: 35,
      rating: 4.9,
      salesCount: 185,
      image: '/src/assets/images/tws_earbuds_pro_1790972827406.jpg'
    },
    {
      id: 2,
      name: 'Cyclone Watch Ultra AMOLED ব্লুটুথ কলিং স্মার্টওয়াচ',
      category: 'Smart Watch',
      regularPrice: 2150,
      salePrice: 1290,
      discountPct: 40,
      rating: 5.0,
      salesCount: 212,
      image: '/src/assets/images/smartwatch_ultra_black_1790972839119.jpg'
    },
    {
      id: 3,
      name: 'Cyclone 20,000mAh 22.5W ফাস্ট চার্জিং পাওয়ার ব্যাংক',
      category: 'Powerbank',
      regularPrice: 2150,
      salePrice: 1550,
      discountPct: 28,
      rating: 4.8,
      salesCount: 140,
      image: '/src/assets/images/powerbank_fast_charge_1790972850858.jpg'
    },
    {
      id: 4,
      name: 'Cyclone Audio Max ওয়্যারলেস স্টুডিও হেডফোন',
      category: 'Headphones',
      regularPrice: 2900,
      salePrice: 1950,
      discountPct: 33,
      rating: 4.9,
      salesCount: 98,
      image: '/src/assets/images/hero_gadget_slider_1790972816745.jpg'
    },
    {
      id: 5,
      name: 'Cyclone Mini পকেট সাইজ আল্ট্রা-কম্প্যাক্ট TWS',
      category: 'TWS Audio',
      regularPrice: 890,
      salePrice: 690,
      discountPct: 22,
      rating: 4.7,
      salesCount: 76,
      image: '/src/assets/images/tws_earbuds_pro_1790972827406.jpg'
    },
    {
      id: 6,
      name: 'Cyclone 65W টাইপ-সি ব্রেডেড সুপার ফাস্ট চার্জিং ক্যাবল',
      category: 'Accessories',
      regularPrice: 520,
      salePrice: 290,
      discountPct: 45,
      rating: 4.9,
      salesCount: 350,
      image: '/src/assets/images/powerbank_fast_charge_1790972850858.jpg'
    }
  ];

  // Quick Checkout Modal State
  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);
  const [selectedProduct, setSelectedProduct] = useState<ProductItem | null>(null);
  const [modalQty, setModalQty] = useState<number>(1);
  const [customerName, setCustomerName] = useState<string>('মোঃ তানভীর হোসেন');
  const [customerPhone, setCustomerPhone] = useState<string>('01712345678');
  const [district, setDistrict] = useState<'dhaka' | 'outside'>('dhaka');
  const [fullAddress, setFullAddress] = useState<string>('বাসা #৪৫, রোড #০৭, সেক্টর #১০, উত্তরা, ঢাকা');
  const [deliveryNote, setDeliveryNote] = useState<string>('');
  const [orderConfirmed, setOrderConfirmed] = useState<{ orderNumber: string; total: number } | null>(null);
  const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

  const deliveryFee = district === 'dhaka' ? 60 : 120;
  const modalSubtotal = selectedProduct ? selectedProduct.salePrice * modalQty : 0;
  const modalTotal = modalSubtotal + deliveryFee;

  const triggerToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => setToastMessage(null), 2500);
  };

  const handleAddToCart = (product: ProductItem) => {
    setCartCount(prev => prev + 1);
    setCartTotal(prev => prev + product.salePrice);
    triggerToast(`✅ ${product.name} কার্টে যোগ করা হয়েছে!`);
  };

  const handleDirectOrder = (product: ProductItem) => {
    setSelectedProduct(product);
    setModalQty(1);
    setIsModalOpen(true);
    setOrderConfirmed(null);
  };

  const handleConfirmOrder = (e: React.FormEvent) => {
    e.preventDefault();
    if (!customerName || !customerPhone || !fullAddress) {
      triggerToast('⚠️ অনুগ্রহ করে সব তথ্য পূরণ করুন।');
      return;
    }

    setIsSubmitting(true);
    setTimeout(() => {
      setIsSubmitting(false);
      const generatedOrderNum = `CM-${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}${String(new Date().getDate()).padStart(2, '0')}-${Math.floor(1000 + Math.random() * 9000)}`;
      setOrderConfirmed({
        orderNumber: generatedOrderNum,
        total: modalTotal
      });
      triggerToast(`🎉 অর্ডার সফল! অর্ডার নম্বর: ${generatedOrderNum}`);
    }, 700);
  };

  return (
    <div className="space-y-4">
      {/* Device Mode Switcher Banner */}
      <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-bold text-slate-900 block text-sm">
            সাইক্লোন মার্ট হোমপেজ লেআউট — Cyclone Mart (Bangladesh Market)
          </span>
          <span className="text-slate-500">
            ডার্ক নেভি ব্লু (#0F172A), ক্রিমসন রেড (#DC2626) অ্যাকসেন্ট, ক্যাটাগরি সাইডবার এবং ২-কলাম (মোবাইল) থেকে ৬-কলাম (ডেস্কটপ) গ্রিড।
          </span>
        </div>

        <div className="flex items-center gap-2 shrink-0">
          <div className="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
            <button
              onClick={() => setDeviceMode('desktop')}
              className={`px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-medium transition-all ${
                deviceMode === 'desktop' ? 'bg-white text-[#0F172A] shadow-xs font-bold' : 'text-slate-600'
              }`}
            >
              <Monitor className="w-3.5 h-3.5" />
              <span>Desktop View</span>
            </button>
            <button
              onClick={() => setDeviceMode('mobile')}
              className={`px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-medium transition-all ${
                deviceMode === 'mobile' ? 'bg-[#DC2626] text-white shadow-xs font-bold' : 'text-slate-600'
              }`}
            >
              <Smartphone className="w-3.5 h-3.5" />
              <span>Mobile (390px)</span>
            </button>
          </div>
        </div>
      </div>

      {/* Frame Container */}
      <div className={deviceMode === 'mobile' ? 'max-w-[420px] mx-auto transition-all' : 'w-full'}>
        <div className="bg-[#F8FAFC] border border-slate-300 rounded-3xl overflow-hidden shadow-md flex flex-col relative text-slate-900 font-sans">
          
          {/* Toast Notification */}
          {toastMessage && (
            <div className="absolute top-20 right-4 z-50 bg-[#0F172A] text-white text-xs px-4 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-2 animate-bounce">
              <span className="w-2.5 h-2.5 rounded-full bg-[#DC2626] animate-pulse"></span>
              <span>{toastMessage}</span>
            </div>
          )}

          {/* 1. TOP ANNOUNCEMENT BAR */}
          <div className="bg-[#0F172A] text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
            <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1.5 text-center sm:text-left text-[11px] sm:text-xs">
              <div className="flex items-center gap-2 font-medium">
                <span className="w-4 h-4 rounded-full bg-[#DC2626] text-white flex items-center justify-center text-[10px]">
                  <Phone className="w-2.5 h-2.5" />
                </span>
                <span>হটলাইন: <strong className="text-white font-mono">০১৭১২-৩৪৫৬৭৮</strong> (সকাল ৯টা - রাত ১১টা)</span>
              </div>
              <div className="flex items-center gap-3 text-slate-300 text-[11px]">
                <span>🚚 সারাদেশে ক্যাশ অন ডেলিভারি</span>
                <span className="hidden sm:inline text-slate-600">•</span>
                <span className="hidden sm:inline">🔄 ৭ দিনের রিটার্ন পলিসি</span>
              </div>
            </div>
          </div>

          {/* 2. STICKY HEADER */}
          <header className="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 py-3 shadow-xs">
            <div className="max-w-7xl mx-auto flex items-center justify-between gap-3">
              {/* Logo */}
              <div className="flex items-center gap-2 shrink-0">
                <div className="w-8 h-8 rounded-xl bg-[#0F172A] flex items-center justify-center text-white font-black text-sm">
                  C<span className="text-[#DC2626]">M</span>
                </div>
                <div>
                  <span className="text-lg sm:text-xl font-extrabold tracking-tight text-[#0F172A] block leading-none">
                    Cyclone<span className="text-[#DC2626]">Mart</span>
                  </span>
                  <span className="text-[9px] text-slate-400 font-medium block">অনলাইন শপিং বাংলাদেশ</span>
                </div>
              </div>

              {/* Search Bar (Desktop) */}
              {deviceMode === 'desktop' && (
                <div className="flex-1 max-w-xl hidden md:block">
                  <div className="relative flex items-center">
                    <input
                      type="text"
                      value={searchQuery}
                      onChange={(e) => setSearchQuery(e.target.value)}
                      placeholder="পছন্দের গ্যাজেট বা পণ্যের নাম খুঁজুন (যেমন: TWS, Smart Watch)..."
                      className="w-full pl-4 pr-24 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A]"
                    />
                    <button className="absolute right-1 top-1 bottom-1 px-4 bg-[#DC2626] text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs">
                      <Search className="w-3.5 h-3.5" />
                      <span>খুঁজুন</span>
                    </button>
                  </div>
                </div>
              )}

              {/* Actions: Hotline & Live Cart */}
              <div className="flex items-center gap-2 sm:gap-4 shrink-0">
                {deviceMode === 'desktop' && (
                  <div className="hidden xl:flex items-center gap-2 p-1.5 text-xs text-left">
                    <div className="w-7 h-7 rounded-full bg-red-50 text-[#DC2626] flex items-center justify-center">
                      <Phone className="w-3.5 h-3.5" />
                    </div>
                    <div>
                      <span className="text-slate-400 block text-[9px]">সরাসরি কল করুন</span>
                      <span className="font-mono font-bold text-slate-800">০১৭১২-৩৪৫৬৭৮</span>
                    </div>
                  </div>
                )}

                {/* Cart Icon */}
                <div className="relative p-2 bg-slate-100 text-[#0F172A] rounded-xl flex items-center gap-2 min-h-[40px] cursor-pointer hover:bg-[#0F172A] hover:text-white transition-colors">
                  <div className="relative">
                    <ShoppingCart className="w-5 h-5" />
                    <span className="absolute -top-2 -right-2 bg-[#DC2626] text-white text-[10px] font-mono font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                      {cartCount}
                    </span>
                  </div>
                  <div className="hidden sm:block text-left text-xs">
                    <span className="text-[9px] text-slate-400 block leading-none">কার্ট</span>
                    <span className="font-mono font-bold text-[#DC2626] leading-none">৳{cartTotal.toLocaleString()}</span>
                  </div>
                </div>
              </div>
            </div>

            {/* Mobile Search Bar */}
            {deviceMode === 'mobile' && (
              <div className="mt-2.5">
                <div className="relative flex items-center">
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    placeholder="পণ্য বা গ্যাজেট খুঁজুন..."
                    className="w-full pl-3.5 pr-20 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs"
                  />
                  <button className="absolute right-1 top-1 bottom-1 px-3 bg-[#DC2626] text-white text-xs font-bold rounded-lg shadow-xs">
                    খুঁজুন
                  </button>
                </div>
              </div>
            )}
          </header>

          {/* MAIN CONTENT AREA */}
          <main className="p-4 sm:p-6 space-y-6">
            
            {/* 3. HERO SECTION (Category Sidebar Left + Banner Right) */}
            <section className="grid grid-cols-12 gap-4 items-start">
              
              {/* Category Sidebar (Visible on Desktop) */}
              {deviceMode === 'desktop' && (
                <aside className="col-span-12 lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                  <div className="bg-[#0F172A] text-white px-4 py-3 flex items-center gap-2.5 font-bold text-xs sm:text-sm">
                    <Menu className="w-4 h-4 text-[#DC2626]" />
                    <span>সকল ক্যাটাগরি</span>
                  </div>
                  <nav className="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    {[
                      { icon: '🎧', title: 'টিডব্লিউএস ইয়ারবাডস (TWS)' },
                      { icon: '⌚', title: 'স্মার্ট ওয়াচ ও ঘড়ি' },
                      { icon: '🔋', title: 'পাওয়ার ব্যাংক ও চার্জার' },
                      { icon: '🔌', title: 'ফাস্ট চার্জিং ক্যাবল' },
                      { icon: '💡', title: 'স্মার্ট হোম গ্যাজেট' },
                      { icon: '📱', title: 'মোবাইল অ্যাক্সেসরিজ' }
                    ].map((cat, idx) => (
                      <div
                        key={idx}
                        className="px-4 py-2.5 flex items-center justify-between hover:bg-slate-50 hover:text-[#DC2626] transition-colors cursor-pointer group"
                      >
                        <span className="flex items-center gap-2.5">
                          <span className="text-base group-hover:scale-110 transition-transform">{cat.icon}</span>
                          <span>{cat.title}</span>
                        </span>
                        <ChevronRight className="w-3.5 h-3.5 text-slate-400 group-hover:text-[#DC2626]" />
                      </div>
                    ))}
                    <div className="px-4 py-2.5 flex items-center justify-between bg-red-50/50 text-[#DC2626] font-bold cursor-pointer hover:bg-red-50">
                      <span className="flex items-center gap-2">
                        <span>🔥</span>
                        <span>হট ডিলস ও মেগা ডিসকাউন্ট</span>
                      </span>
                      <span className="text-[10px] font-mono bg-[#DC2626] text-white px-1.5 py-0.5 rounded">HOT</span>
                    </div>
                  </nav>
                </aside>
              )}

              {/* Promotional Slider Right */}
              <div className={deviceMode === 'desktop' ? 'col-span-12 lg:col-span-9 space-y-3' : 'col-span-12 space-y-3'}>
                <div className="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-[#0F172A] aspect-[16/8] sm:aspect-[21/9] p-5 sm:p-10 flex flex-col justify-center text-white">
                  <div className="max-w-md space-y-2 z-10">
                    <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-bold font-mono">
                      🔥 ধামাকা অফার · ৫০% পর্যন্ত ছাড়
                    </span>
                    <h2 className="text-lg sm:text-3xl font-extrabold tracking-tight leading-tight">
                      অরিজিনাল TWS ও স্মার্ট গ্যাজেট কালেকশন
                    </h2>
                    <p className="text-xs text-slate-300 line-clamp-2">
                      ক্যাশ অন ডেলিভারিতে সারাদেশে সুপারফাস্ট হোম ডেলিভারি। কোনো অগ্রিম পেমেন্ট নেই!
                    </p>
                    <div className="pt-2 flex items-center gap-3">
                      <button
                        onClick={() => triggerToast('🔥 ধামাকা অফার সেকশনে স্ক্রোল করা হচ্ছে!')}
                        className="px-4 py-2 bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs font-bold rounded-xl shadow-md flex items-center gap-1.5 min-h-[40px]"
                      >
                        <span>অর্ডার করুন</span>
                        <ArrowRight className="w-3.5 h-3.5" />
                      </button>
                      <span className="text-[11px] text-slate-400 font-mono">🚚 ২-৩ দিনে ডেলিভারি</span>
                    </div>
                  </div>

                  <img
                    src="/src/assets/images/hero_gadget_slider_1790972816745.jpg"
                    alt="Cyclone Mart"
                    className="absolute right-0 top-0 bottom-0 h-full w-full sm:w-2/3 object-cover opacity-45 mix-blend-screen -z-0"
                  />
                </div>

                {/* 3 Quick USP Strips */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                  <div className="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div className="w-8 h-8 rounded-lg bg-blue-50 text-[#0F172A] flex items-center justify-center font-bold text-sm shrink-0">
                      🚚
                    </div>
                    <div>
                      <span className="font-bold text-slate-800 block text-xs">হোম ডেলিভারি</span>
                      <span className="text-[10px] text-slate-500">ঢাকার ভেতরে ৬০৳, বাইরে ১২০৳</span>
                    </div>
                  </div>

                  <div className="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div className="w-8 h-8 rounded-lg bg-red-50 text-[#DC2626] flex items-center justify-center font-bold text-sm shrink-0">
                      💵
                    </div>
                    <div>
                      <span className="font-bold text-slate-800 block text-xs">ক্যাশ অন ডেলিভারি</span>
                      <span className="text-[10px] text-slate-500">পণ্য দেখে মূল্য পরিশোধ</span>
                    </div>
                  </div>

                  <div className="p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2.5 shadow-2xs">
                    <div className="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                      🔄
                    </div>
                    <div>
                      <span className="font-bold text-slate-800 block text-xs">৭ দিনের রিপ্লেসমেন্ট</span>
                      <span className="text-[10px] text-slate-500">সহজ ও দ্রুত রিটার্ন পলিসি</span>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            {/* 4. PRODUCT GRID (Responsive 2-Col Mobile to 6-Col Desktop) */}
            <section className="space-y-3.5">
              <div className="bg-white border border-slate-200 rounded-2xl p-3.5 shadow-xs flex items-center justify-between gap-3">
                <div className="flex items-center gap-2">
                  <div className="w-7 h-7 rounded-lg bg-red-50 text-[#DC2626] flex items-center justify-center font-bold text-sm">
                    🔥
                  </div>
                  <div>
                    <h3 className="text-sm sm:text-base font-bold text-[#0F172A] flex items-center gap-1.5">
                      <span>হট ডিলস ও সেরা অফারসমূহ</span>
                      <span className="text-[10px] font-mono bg-[#DC2626] text-white px-1.5 py-0.5 rounded font-bold uppercase">HOT</span>
                    </h3>
                  </div>
                </div>
                <span className="text-xs font-bold text-[#DC2626] cursor-pointer hover:underline">
                  সবগুলো দেখুন →
                </span>
              </div>

              {/* Responsive Grid */}
              <div className={deviceMode === 'mobile' ? 'grid grid-cols-2 gap-2.5' : 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4'}>
                {products.map((p) => (
                  <div
                    key={p.id}
                    className="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-lg hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group"
                  >
                    <div className="relative p-2 sm:p-2.5">
                      {/* Discount Badge */}
                      <div className="absolute top-3 left-3 z-10">
                        <span className="bg-[#DC2626] text-white text-[10px] sm:text-[11px] font-mono font-extrabold px-1.5 py-0.5 rounded-md shadow-xs">
                          -{p.discountPct}%
                        </span>
                      </div>

                      {/* Product Image */}
                      <div className="aspect-square bg-slate-50 rounded-xl overflow-hidden p-2 flex items-center justify-center relative cursor-pointer">
                        <img
                          src={p.image}
                          alt={p.name}
                          className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                        />
                      </div>

                      {/* Title & Metadata */}
                      <div className="mt-2 space-y-1">
                        <span className="text-[9px] text-slate-400 font-mono uppercase block">{p.category}</span>
                        <h4 className="text-xs font-semibold text-slate-800 line-clamp-2 leading-snug group-hover:text-[#0F172A] transition-colors">
                          {p.name}
                        </h4>
                        <div className="flex items-center gap-1 text-[10px] text-amber-500 font-mono">
                          <span>★ {p.rating}</span>
                          <span className="text-slate-400">({p.salesCount} বিক্রিত)</span>
                        </div>
                      </div>

                      {/* Price Lockup */}
                      <div className="mt-2 flex items-baseline gap-1.5 font-mono">
                        <span className="text-sm font-extrabold text-[#DC2626]">
                          ৳{p.salePrice}
                        </span>
                        <span className="text-[10px] text-slate-400 line-through">
                          ৳{p.regularPrice}
                        </span>
                      </div>
                    </div>

                    {/* Dual Action Buttons: 'কার্ট' & 'অর্ডার করুন' */}
                    <div className="p-2 sm:p-2.5 pt-0 grid grid-cols-2 gap-1.5">
                      <button
                        type="button"
                        onClick={() => handleAddToCart(p)}
                        className="w-full py-1.5 px-1 bg-slate-100 hover:bg-[#0F172A] text-slate-800 hover:text-white rounded-xl text-[11px] font-semibold transition-colors flex items-center justify-center gap-1 min-h-[38px]"
                      >
                        <ShoppingCart className="w-3.5 h-3.5" />
                        <span>কার্ট</span>
                      </button>

                      <button
                        type="button"
                        onClick={() => handleDirectOrder(p)}
                        className="w-full py-1.5 px-1 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-[11px] font-bold transition-colors shadow-xs flex items-center justify-center gap-1 min-h-[38px]"
                      >
                        <span>অর্ডার করুন</span>
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </section>

            {/* 5. TRUST PILLARS SECTION */}
            <section className="bg-white border border-slate-200 rounded-2xl p-5 text-center">
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div className="p-3 bg-slate-50 rounded-xl space-y-1">
                  <span className="text-xl">💵</span>
                  <h5 className="text-xs font-bold text-[#0F172A]">ক্যাশ অন ডেলিভারি</h5>
                  <p className="text-[10px] text-slate-500">পণ্য দেখে নিয়ে মূল্য পরিশোধ</p>
                </div>
                <div className="p-3 bg-slate-50 rounded-xl space-y-1">
                  <span className="text-xl">⚡</span>
                  <h5 className="text-xs font-bold text-[#0F172A]">দ্রুত হোম ডেলিভারি</h5>
                  <p className="text-[10px] text-slate-500">২৪-৭২ ঘণ্টায় সারাদেশে পৌঁছায়</p>
                </div>
                <div className="p-3 bg-slate-50 rounded-xl space-y-1">
                  <span className="text-xl">🔄</span>
                  <h5 className="text-xs font-bold text-[#0F172A]">৭ দিনের রিটার্ন</h5>
                  <p className="text-[10px] text-slate-500">সহজ ও দ্রুত রিপ্লেসমেন্ট</p>
                </div>
                <div className="p-3 bg-slate-50 rounded-xl space-y-1">
                  <span className="text-xl">🛡️</span>
                  <h5 className="text-xs font-bold text-[#0F172A]">১০০% অথেনটিক</h5>
                  <p className="text-[10px] text-slate-500">জেনুইন কোয়ালিটি পণ্য</p>
                </div>
              </div>
            </section>

          </main>

          {/* FOOTER */}
          <footer className="bg-[#0F172A] text-slate-400 text-xs p-6 border-t border-slate-800">
            <div className="flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
              <div>
                <span className="font-bold text-white text-sm">Cyclone<span className="text-[#DC2626]">Mart</span></span>
                <p className="text-[11px] text-slate-400 mt-0.5">বাংলাদেশের বিশ্বস্ত অনলাইন ড্রপশিপিং শপ · হেল্পলাইন: ০১৭১২-৩৪৫৬৭৮</p>
              </div>
              <div className="flex items-center gap-3 text-white text-xs font-mono">
                <span className="bg-slate-800 px-2 py-1 rounded">bKash</span>
                <span className="bg-slate-800 px-2 py-1 rounded">Nagad</span>
                <span className="bg-slate-800 px-2 py-1 rounded">COD</span>
              </div>
            </div>
          </footer>

        </div>
      </div>

      {/* EXPRESS QUICK CHECKOUT MODAL */}
      {isModalOpen && selectedProduct && (
        <div className="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
          <div className="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh] my-auto animate-in fade-in zoom-in-95 duration-200">
            
            {/* Modal Header */}
            <div className="bg-[#0F172A] text-white px-5 py-3.5 flex items-center justify-between border-b border-slate-800 shrink-0">
              <div className="flex items-center gap-2">
                <span className="w-2.5 h-2.5 rounded-full bg-[#DC2626] animate-pulse"></span>
                <h3 className="text-sm sm:text-base font-extrabold tracking-tight">
                  এক্সপ্রেস দ্রুত চেকআউট (Quick Order)
                </h3>
              </div>
              <button
                type="button"
                onClick={() => setIsModalOpen(false)}
                className="w-8 h-8 rounded-full bg-slate-800 text-slate-300 hover:text-white flex items-center justify-center transition-colors min-h-[44px] min-w-[44px]"
              >
                ✕
              </button>
            </div>

            {/* Modal Body */}
            <div className="p-4 sm:p-5 overflow-y-auto space-y-4 text-slate-900 text-xs sm:text-sm">
              
              {/* If Order Confirmed State */}
              {orderConfirmed ? (
                <div className="text-center py-6 space-y-4">
                  <div className="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-sm">
                    ✓
                  </div>
                  <div>
                    <h4 className="text-lg font-extrabold text-[#0F172A]">আপনার অর্ডারটি সফল হয়েছে!</h4>
                    <p className="text-xs text-slate-500 mt-1">আমাদের কাস্টমার কেয়ার টিম শীঘ্রই আপনার সাথে ফোনে যোগাযোগ করবে।</p>
                  </div>
                  <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 font-mono space-y-1 text-left text-xs">
                    <div className="flex justify-between">
                      <span className="text-slate-500">অর্ডার নম্বর:</span>
                      <span className="font-bold text-[#0F172A]">{orderConfirmed.orderNumber}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">পেমেন্ট মেথড:</span>
                      <span className="font-bold text-emerald-600">ক্যাশ অন ডেলিভারি (COD)</span>
                    </div>
                    <div className="flex justify-between border-t border-slate-200 pt-1 text-sm">
                      <span className="text-slate-700 font-bold">সর্বমোট প্রদেয়:</span>
                      <span className="font-extrabold text-[#DC2626]">৳{orderConfirmed.total}</span>
                    </div>
                  </div>
                  <button
                    onClick={() => setIsModalOpen(false)}
                    className="w-full py-3 bg-[#0F172A] text-white rounded-xl font-bold text-xs hover:bg-slate-800 transition-colors"
                  >
                    শপিং চালিয়ে যান
                  </button>
                </div>
              ) : (
                <>
                  {/* Selected Product Card */}
                  <div className="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-3">
                    <div className="flex items-center gap-3 min-w-0">
                      <div className="w-14 h-14 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shrink-0">
                        <img src={selectedProduct.image} alt={selectedProduct.name} className="w-full h-full object-contain" />
                      </div>
                      <div className="truncate">
                        <h4 className="font-bold text-slate-900 text-xs sm:text-sm truncate">
                          {selectedProduct.name}
                        </h4>
                        <div className="flex items-center gap-2 mt-0.5 font-mono">
                          <span className="text-sm font-extrabold text-[#DC2626]">
                            ৳{selectedProduct.salePrice}
                          </span>
                          <span className="text-[10px] text-slate-400 line-through">
                            ৳{selectedProduct.regularPrice}
                          </span>
                        </div>
                      </div>
                    </div>

                    {/* Quantity Stepper */}
                    <div className="flex items-center border border-slate-300 rounded-xl bg-white overflow-hidden shrink-0 shadow-2xs">
                      <button
                        type="button"
                        onClick={() => setModalQty(Math.max(1, modalQty - 1))}
                        className="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-sm"
                        disabled={modalQty <= 1}
                      >
                        -
                      </button>
                      <span className="w-7 text-center font-mono font-bold text-slate-900 text-xs">
                        {modalQty}
                      </span>
                      <button
                        type="button"
                        onClick={() => setModalQty(modalQty + 1)}
                        className="w-7 h-7 flex items-center justify-center text-slate-600 hover:bg-slate-100 font-bold text-sm"
                      >
                        +
                      </button>
                    </div>
                  </div>

                  {/* Form */}
                  <form onSubmit={handleConfirmOrder} className="space-y-3">
                    <div>
                      <label className="block text-[11px] font-bold text-slate-700 mb-1">
                        আপনার নাম (Full Name) *
                      </label>
                      <input
                        type="text"
                        required
                        value={customerName}
                        onChange={(e) => setCustomerName(e.target.value)}
                        placeholder="যেমন: মোঃ সাকিব হাসান"
                        className="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A]"
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-bold text-slate-700 mb-1">
                        মোবাইল নম্বর (Phone Number) *
                      </label>
                      <input
                        type="tel"
                        required
                        value={customerPhone}
                        onChange={(e) => setCustomerPhone(e.target.value)}
                        placeholder="017XXXXXXXX"
                        className="w-full p-2.5 font-mono bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A]"
                      />
                    </div>

                    <div>
                      <label className="block text-[11px] font-bold text-slate-700 mb-1">
                        ডেলিভারি এরিয়া (Delivery Zone) *
                      </label>
                      <div className="grid grid-cols-2 gap-2">
                        <button
                          type="button"
                          onClick={() => setDistrict('dhaka')}
                          className={`p-2.5 rounded-xl border text-left flex flex-col justify-between min-h-[44px] transition-all ${
                            district === 'dhaka'
                              ? 'border-[#DC2626] bg-red-50/60 font-bold text-[#DC2626]'
                              : 'border-slate-200 bg-slate-50 text-slate-700'
                          }`}
                        >
                          <span className="text-xs">ঢাকার ভিতরে</span>
                          <span className="text-[10px] font-mono">চার্জ: ৳৬০</span>
                        </button>
                        <button
                          type="button"
                          onClick={() => setDistrict('outside')}
                          className={`p-2.5 rounded-xl border text-left flex flex-col justify-between min-h-[44px] transition-all ${
                            district === 'outside'
                              ? 'border-[#DC2626] bg-red-50/60 font-bold text-[#DC2626]'
                              : 'border-slate-200 bg-slate-50 text-slate-700'
                          }`}
                        >
                          <span className="text-xs">ঢাকার বাইরে</span>
                          <span className="text-[10px] font-mono">চার্জ: ৳১২০</span>
                        </button>
                      </div>
                    </div>

                    <div>
                      <label className="block text-[11px] font-bold text-slate-700 mb-1">
                        সম্পূর্ণ ডেলিভারি ঠিকানা (Address) *
                      </label>
                      <textarea
                        required
                        rows={2}
                        value={fullAddress}
                        onChange={(e) => setFullAddress(e.target.value)}
                        placeholder="বাসা/রোড নম্বর, এলাকা, থানা..."
                        className="w-full p-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0F172A]"
                      ></textarea>
                    </div>

                    {/* Cash on Delivery Notice */}
                    <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                      <span className="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                      <span className="text-xs text-emerald-900 font-semibold">ক্যাশ অন ডেলিভারি: পণ্য দেখে নিয়ে মূল্য পরিশোধ করবেন।</span>
                    </div>

                    {/* Bill Receipt */}
                    <div className="p-3 bg-slate-100 rounded-xl space-y-1 font-mono text-xs">
                      <div className="flex justify-between text-slate-600">
                        <span>পণ্য মূল্য ({modalQty} টি):</span>
                        <span className="font-bold text-slate-900">৳{modalSubtotal}</span>
                      </div>
                      <div className="flex justify-between text-slate-600">
                        <span>ডেলিভারি চার্জ:</span>
                        <span className="font-bold text-slate-900">৳{deliveryFee}</span>
                      </div>
                      <div className="border-t border-slate-300 pt-1 flex justify-between font-extrabold text-sm text-[#0F172A]">
                        <span>সর্বমোট প্রদেয় বিল:</span>
                        <span className="text-[#DC2626]">৳{modalTotal}</span>
                      </div>
                    </div>

                    {/* Submit Button */}
                    <button
                      type="submit"
                      disabled={isSubmitting}
                      className="w-full py-3.5 bg-[#DC2626] hover:bg-[#B91C1C] text-white rounded-xl text-sm font-extrabold shadow-md flex items-center justify-center gap-2 min-h-[46px] disabled:opacity-75"
                    >
                      {isSubmitting ? (
                        <span>অর্ডার সম্পন্ন হচ্ছে...</span>
                      ) : (
                        <span>অর্ডার কনফার্ম করুন (৳{modalTotal})</span>
                      )}
                    </button>
                  </form>
                </>
              )}

            </div>
          </div>
        </div>
      )}
    </div>
  );
};
