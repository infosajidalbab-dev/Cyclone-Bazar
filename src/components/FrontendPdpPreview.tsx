import React, { useState } from 'react';
import { ShoppingBag, Truck, ShieldCheck, CheckCircle2, RotateCcw, ChevronLeft, ChevronRight, Eye, Sparkles } from 'lucide-react';

interface Variant {
  id: number;
  title: string;
  color: string;
  size: string;
  costPrice: number;
  sellingPrice: number;
  compareAtPrice: number;
  stock: number;
  sku: string;
}

const SAMPLE_VARIANTS: Variant[] = [
  {
    id: 1,
    title: 'Midnight Black / Standard',
    color: '#1A1A1A',
    size: 'Standard',
    costPrice: 750,
    sellingPrice: 1250,
    compareAtPrice: 1650,
    stock: 14,
    sku: 'CM-EAR-BLK-01'
  },
  {
    id: 2,
    title: 'Glacier White / Standard',
    color: '#F3F4F6',
    size: 'Standard',
    costPrice: 750,
    sellingPrice: 1250,
    compareAtPrice: 1650,
    stock: 8,
    sku: 'CM-EAR-WHT-02'
  },
  {
    id: 3,
    title: 'Navy Blue / Pro Edition',
    color: '#0F4C81',
    size: 'Pro',
    costPrice: 900,
    sellingPrice: 1450,
    compareAtPrice: 1950,
    stock: 5,
    sku: 'CM-EAR-BLU-PRO'
  }
];

const SAMPLE_IMAGES = [
  {
    label: 'Main Showcase',
    url: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
    fallbackBg: 'from-slate-900 to-blue-950',
    title: 'Cyclone TWS Pro Earbuds'
  },
  {
    label: 'Charging Case',
    url: 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=800&auto=format&fit=crop&q=80',
    fallbackBg: 'from-blue-900 to-indigo-950',
    title: 'Magnetic USB-C Charging Case'
  },
  {
    label: 'In-Ear Ergonomics',
    url: 'https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=800&auto=format&fit=crop&q=80',
    fallbackBg: 'from-slate-800 to-slate-950',
    title: 'Ergonomic Silicone Ear Tips'
  }
];

export const FrontendPdpPreview: React.FC = () => {
  const [selectedVariant, setSelectedVariant] = useState<Variant>(SAMPLE_VARIANTS[0]);
  const [activeImageIndex, setActiveImageIndex] = useState<number>(0);
  const [quantity, setQuantity] = useState<number>(1);
  const [activePolicyTab, setActivePolicyTab] = useState<'replacement' | 'inspection' | 'payment'>('replacement');
  const [cartCount, setCartCount] = useState<number>(0);
  const [isBuyModalOpen, setIsBuyModalOpen] = useState<boolean>(false);
  const [orderSuccess, setOrderSuccess] = useState<boolean>(false);

  // Quick 1-Click Checkout Form state
  const [buyerName, setBuyerName] = useState('Sajid Pramanik');
  const [buyerPhone, setBuyerPhone] = useState('01712345678');
  const [buyerAddress, setBuyerAddress] = useState('House 42, Road 7, Block C, Mirpur 10, Dhaka');
  const [deliveryCharge, setDeliveryCharge] = useState<number>(60);

  const discountPercent = Math.round(
    ((selectedVariant.compareAtPrice - selectedVariant.sellingPrice) / selectedVariant.compareAtPrice) * 100
  );

  const totalOrderAmount = (selectedVariant.sellingPrice * quantity) + deliveryCharge;

  const handleAddToCart = () => {
    setCartCount(prev => prev + quantity);
  };

  const handleQuickCheckout = () => {
    setOrderSuccess(true);
    setTimeout(() => {
      setOrderSuccess(false);
      setIsBuyModalOpen(false);
    }, 2500);
  };

  return (
    <div className="space-y-6">
      {/* Introduction Banner */}
      <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-semibold text-slate-900 block">Section 7.3 Frontend PDP Specification</span>
          <span className="text-slate-500">
            Swipeable image gallery, dynamic variant sync, BDT price badges, replacement policy tabs, and sticky 15% mobile CTA.
          </span>
        </div>
        <div className="flex items-center gap-2">
          <span className="text-slate-600 font-mono text-[11px] bg-slate-100 px-2 py-1 rounded">
            resources/views/frontend/product-detail.blade.php
          </span>
        </div>
      </div>

      {/* PDP Container (Centered Mobile-First Frame or Expandable) */}
      <div className="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden relative">
        {/* Mock Top Mobile Bar */}
        <div className="bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-20">
          <div className="flex items-center gap-2">
            <span className="font-extrabold text-base tracking-tight text-[#0F4C81]">Cyclone Mart</span>
            <span className="text-[9px] bg-[#FF6B35] text-white px-1.5 py-0.5 rounded font-mono font-bold">BD</span>
          </div>

          <div className="flex items-center gap-3">
            <div className="relative cursor-pointer p-1">
              <ShoppingBag className="w-5 h-5 text-slate-700" />
              {cartCount > 0 && (
                <span className="absolute -top-1 -right-1 w-4 h-4 bg-[#FF6B35] text-white text-[10px] rounded-full flex items-center justify-center font-mono font-bold">
                  {cartCount}
                </span>
              )}
            </div>
          </div>
        </div>

        {/* PDP Main Content */}
        <div className="p-4 sm:p-6 pb-24 md:pb-8">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            {/* 1. Image Gallery */}
            <div className="space-y-3">
              {/* Main Stage */}
              <div className="relative aspect-square rounded-2xl border border-slate-200 bg-slate-900 overflow-hidden flex items-center justify-center">
                <div className={`w-full h-full bg-gradient-to-br ${SAMPLE_IMAGES[activeImageIndex].fallbackBg} flex flex-col items-center justify-center text-white p-6 text-center`}>
                  <Sparkles className="w-12 h-12 text-[#FF6B35] mb-2 opacity-80" />
                  <span className="text-lg font-bold">{SAMPLE_IMAGES[activeImageIndex].title}</span>
                  <span className="text-xs text-slate-300 mt-1">{selectedVariant.title}</span>
                </div>

                {/* Discount % Badge */}
                <div className="absolute top-3 left-3 bg-[#FF6B35] text-white text-xs font-bold font-mono px-2.5 py-1 rounded-md shadow-sm">
                  -{discountPercent}% OFF (ছাড়)
                </div>

                {/* Stock Indicator */}
                <div className="absolute top-3 right-3 bg-white/90 backdrop-blur-xs text-[11px] font-mono px-2.5 py-1 rounded-md border border-slate-200">
                  <span className="text-emerald-700 font-semibold">ইন স্টক ({selectedVariant.stock} left)</span>
                </div>

                {/* Carousel Controls */}
                <button
                  onClick={() => setActiveImageIndex((activeImageIndex - 1 + SAMPLE_IMAGES.length) % SAMPLE_IMAGES.length)}
                  className="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 border border-slate-200 flex items-center justify-center text-slate-800 hover:bg-white shadow-xs"
                >
                  <ChevronLeft className="w-4 h-4" />
                </button>
                <button
                  onClick={() => setActiveImageIndex((activeImageIndex + 1) % SAMPLE_IMAGES.length)}
                  className="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 border border-slate-200 flex items-center justify-center text-slate-800 hover:bg-white shadow-xs"
                >
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>

              {/* Thumbnails */}
              <div className="flex items-center gap-2 overflow-x-auto pb-1">
                {SAMPLE_IMAGES.map((img, idx) => (
                  <button
                    key={idx}
                    onClick={() => setActiveImageIndex(idx)}
                    className={`w-16 h-16 rounded-xl border-2 overflow-hidden shrink-0 transition-all p-1 flex items-center justify-center text-[10px] font-medium text-center ${
                      activeImageIndex === idx
                        ? 'border-[#0F4C81] bg-blue-50 text-[#0F4C81]'
                        : 'border-slate-200 text-slate-500 hover:border-slate-300'
                    }`}
                  >
                    <span>{img.label}</span>
                  </button>
                ))}
              </div>
            </div>

            {/* 2. Product Information & Buy Actions */}
            <div className="space-y-4">
              <div>
                <div className="flex items-center gap-2 text-xs text-slate-500 font-medium mb-1">
                  <span className="text-[#0F4C81] font-semibold">Audio & Electronics</span>
                  <span>·</span>
                  <span className="font-mono">SKU: {selectedVariant.sku}</span>
                  <span>·</span>
                  <span className="text-[#28A745] font-semibold">Genuine Warranty</span>
                </div>
                <h1 className="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 leading-snug">
                  Cyclone TWS Pro Wireless Earbuds with ENC Noise Cancellation
                </h1>
              </div>

              {/* Price Badges Block */}
              <div className="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-baseline justify-between shadow-xs">
                <div>
                  <span className="text-[11px] text-slate-400 block mb-0.5">অফার মূল্য (Offer Price)</span>
                  <div className="flex items-baseline gap-2.5">
                    <span className="text-2xl sm:text-3xl font-extrabold font-mono text-[#0F4C81]">
                      ৳{selectedVariant.sellingPrice.toLocaleString()}
                    </span>
                    <span className="text-sm font-mono text-slate-400 line-through">
                      ৳{selectedVariant.compareAtPrice.toLocaleString()}
                    </span>
                  </div>
                </div>

                <div className="text-right">
                  <span className="inline-block px-2.5 py-1 bg-emerald-50 text-[#28A745] border border-emerald-200 rounded-lg text-xs font-semibold">
                    ক্যাশ অন ডেলিভারি
                  </span>
                </div>
              </div>

              {/* Variant Selector (Colors / Editions) */}
              <div className="space-y-2.5 bg-white p-3.5 rounded-2xl border border-slate-200">
                <div className="flex items-center justify-between text-xs">
                  <span className="font-bold text-slate-800">কালার ও এডিশন (Select Edition):</span>
                  <span className="font-mono text-[#0F4C81] font-semibold">{selectedVariant.title}</span>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-3 gap-2">
                  {SAMPLE_VARIANTS.map((v) => {
                    const isSelected = selectedVariant.id === v.id;
                    return (
                      <button
                        key={v.id}
                        onClick={() => setSelectedVariant(v)}
                        className={`p-2.5 rounded-xl border text-left text-xs transition-all flex items-center gap-2.5 min-h-[44px] ${
                          isSelected
                            ? 'border-[#0F4C81] bg-blue-50/70 ring-1 ring-blue-200 text-[#0F4C81]'
                            : 'border-slate-200 hover:border-slate-300 text-slate-700'
                        }`}
                      >
                        <span
                          className="w-4 h-4 rounded-full border border-slate-300 shrink-0 shadow-xs"
                          style={{ backgroundColor: v.color }}
                        />
                        <div className="truncate">
                          <span className="font-semibold block truncate">{v.size}</span>
                          <span className="font-mono text-[10px] text-slate-500">৳{v.sellingPrice}</span>
                        </div>
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Quantity Controls (+/-) */}
              <div className="flex items-center justify-between bg-white p-3.5 rounded-2xl border border-slate-200">
                <div>
                  <span className="text-xs font-bold text-slate-800 block">পরিমাণ (Quantity)</span>
                  <span className="text-[11px] text-slate-400">সর্বোচ্চ {selectedVariant.stock} টি উপলব্ধ</span>
                </div>

                <div className="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-slate-50">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="w-11 h-11 flex items-center justify-center text-slate-700 hover:bg-slate-200 text-lg font-bold min-h-[44px] min-w-[44px] transition-colors"
                  >
                    -
                  </button>
                  <span className="w-12 text-center font-mono font-bold text-sm text-slate-900">{quantity}</span>
                  <button
                    onClick={() => setQuantity(Math.min(selectedVariant.stock, quantity + 1))}
                    className="w-11 h-11 flex items-center justify-center text-slate-700 hover:bg-slate-200 text-lg font-bold min-h-[44px] min-w-[44px] transition-colors"
                  >
                    +
                  </button>
                </div>
              </div>

              {/* Desktop CTAs */}
              <div className="hidden md:grid grid-cols-2 gap-3 pt-1">
                <button
                  onClick={handleAddToCart}
                  className="min-h-[48px] px-4 rounded-xl border-2 border-[#0F4C81] text-[#0F4C81] font-bold text-xs hover:bg-blue-50 transition-colors flex items-center justify-center gap-2"
                >
                  <ShoppingBag className="w-4 h-4" />
                  <span>কার্ট-এ যোগ করুন</span>
                </button>

                <button
                  onClick={() => setIsBuyModalOpen(true)}
                  className="min-h-[48px] px-4 rounded-xl bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2"
                >
                  <span>এখনই অর্ডার করুন (COD)</span>
                </button>
              </div>

              {/* Delivery Charge Estimates */}
              <div className="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2.5">
                <div className="flex items-center gap-2 text-xs font-bold text-slate-900 border-b border-slate-200 pb-2">
                  <Truck className="w-4 h-4 text-[#0F4C81]" />
                  <span>ডেলিভারি চার্জ ও সময় (Delivery Charges)</span>
                </div>

                <div className="space-y-1.5 text-xs">
                  <div className="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100">
                    <span className="text-slate-700">ঢাকা সিটির ভিতরে (Inside Dhaka)</span>
                    <span className="font-mono font-bold text-[#0F4C81]">৳৬০ (১-২ দিন)</span>
                  </div>
                  <div className="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100">
                    <span className="text-slate-700">ঢাকা সাব-এরিয়া (Gazipur, Savar, N'Ganj)</span>
                    <span className="font-mono font-bold text-[#0F4C81]">৳১০০ (২-৩ দিন)</span>
                  </div>
                  <div className="flex items-center justify-between p-2 rounded-lg bg-white border border-slate-100">
                    <span className="text-slate-700">ঢাকার বাইরে সারা বাংলাদেশ (Outside Dhaka)</span>
                    <span className="font-mono font-bold text-[#0F4C81]">৳১৩০ (২-৪ দিন)</span>
                  </div>
                </div>
              </div>

              {/* Policy & Guarantee Tabs */}
              <div className="bg-white p-4 rounded-2xl border border-slate-200 space-y-3">
                <div className="flex border-b border-slate-200 text-xs font-bold">
                  <button
                    onClick={() => setActivePolicyTab('replacement')}
                    className={`flex-1 py-2 text-center border-b-2 transition-colors min-h-[40px] ${
                      activePolicyTab === 'replacement'
                        ? 'border-[#0F4C81] text-[#0F4C81]'
                        : 'border-transparent text-slate-500 hover:text-slate-800'
                    }`}
                  >
                    রিপ্লেসমেন্ট সুবিধা
                  </button>
                  <button
                    onClick={() => setActivePolicyTab('inspection')}
                    className={`flex-1 py-2 text-center border-b-2 transition-colors min-h-[40px] ${
                      activePolicyTab === 'inspection'
                        ? 'border-[#0F4C81] text-[#0F4C81]'
                        : 'border-transparent text-slate-500 hover:text-slate-800'
                    }`}
                  >
                    দেখে নেওয়ার সুবিধা
                  </button>
                  <button
                    onClick={() => setActivePolicyTab('payment')}
                    className={`flex-1 py-2 text-center border-b-2 transition-colors min-h-[40px] ${
                      activePolicyTab === 'payment'
                        ? 'border-[#0F4C81] text-[#0F4C81]'
                        : 'border-transparent text-slate-500 hover:text-slate-800'
                    }`}
                  >
                    পেমেন্ট গ্যারান্টি
                  </button>
                </div>

                <div className="text-xs text-slate-600 leading-relaxed min-h-[50px]">
                  {activePolicyTab === 'replacement' && (
                    <p>
                      প্রোডাক্টে কোনো ধরণের ত্রুটি বা সমস্যা থাকলে ডেলিভারি পাওয়ার পর <strong>৭ দিনের মধ্যে</strong> সম্পূর্ণ ফ্রিতে রিপ্লেসমেন্ট পাবেন।
                    </p>
                  )}
                  {activePolicyTab === 'inspection' && (
                    <p>
                      ডেলিভারি রাইডারের সামনে পার্সেল খুলে চেক করে প্রোডাক্ট ঠিক থাকলে ক্যাশ পেমেন্ট করবেন। কোনো অগ্রিম ফি প্রয়োজন নেই।
                    </p>
                  )}
                  {activePolicyTab === 'payment' && (
                    <p>
                      ক্যাশ অন ডেলিভারি (COD) বা বিকাশ/নগদের মাধ্যমে ডেলিভারি প্রতিনিধির কাছে সহজে মূল্য পরিশোধ করতে পারবেন।
                    </p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Sticky Mobile CTA Bar (Layout C, <15% viewport height) */}
        <div className="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2.5 shadow-lg flex items-center justify-between gap-3">
          <div className="shrink-0 font-mono">
            <span className="text-[10px] text-slate-400 block">মোট মূল্য</span>
            <span className="text-base font-extrabold text-[#0F4C81]">৳{(selectedVariant.sellingPrice * quantity).toLocaleString()}</span>
          </div>

          <button
            onClick={() => setIsBuyModalOpen(true)}
            className="flex-1 min-h-[44px] bg-[#FF6B35] active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md transition-transform"
          >
            <span>ক্যাশ অন ডেলিভারি অর্ডার</span>
          </button>
        </div>

        {/* 1-Click COD Quick Order Modal */}
        {isBuyModalOpen && (
          <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div className="w-full max-w-lg bg-white rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
              <div className="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                  <h3 className="text-sm font-bold text-slate-900">দ্রুত অর্ডার করুন (1-Click Cash on Delivery)</h3>
                  <span className="text-[11px] text-slate-500">কোনো অগ্রিম পেমেন্ট নেই · ডেলিভারির সময় মূল্য দিন</span>
                </div>
                <button onClick={() => setIsBuyModalOpen(false)} className="text-slate-400 hover:text-slate-600 text-xs font-mono p-1">
                  ✕ Close
                </button>
              </div>

              {orderSuccess ? (
                <div className="p-6 bg-emerald-50 border border-emerald-200 rounded-2xl text-center space-y-3">
                  <CheckCircle2 className="w-12 h-12 text-[#28A745] mx-auto" />
                  <h4 className="font-bold text-slate-900 text-sm">অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h4>
                  <p className="text-xs text-slate-600">
                    আমাদের প্রতিনিধি শীঘ্রই <span className="font-mono font-bold text-slate-800">{buyerPhone}</span> নম্বরে কল করে অর্ডার নিশ্চিত করবেন।
                  </p>
                </div>
              ) : (
                <div className="space-y-3 text-xs">
                  {/* Selected Item Summary */}
                  <div className="p-3 bg-slate-50 rounded-xl border border-slate-200 flex justify-between items-center">
                    <div>
                      <span className="font-semibold text-slate-800 block">Cyclone TWS Pro ({selectedVariant.size})</span>
                      <span className="text-[11px] text-slate-500 font-mono">Qty: {quantity} x ৳{selectedVariant.sellingPrice}</span>
                    </div>
                    <span className="font-mono font-bold text-[#0F4C81]">৳{(selectedVariant.sellingPrice * quantity).toLocaleString()}</span>
                  </div>

                  <div>
                    <label className="block text-[11px] font-semibold text-slate-700 mb-1">আপনার নাম (Your Name) *</label>
                    <input
                      type="text"
                      value={buyerName}
                      onChange={(e) => setBuyerName(e.target.value)}
                      className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    />
                  </div>

                  <div>
                    <label className="block text-[11px] font-semibold text-slate-700 mb-1">মোবাইল নম্বর (11 Digits Mobile) *</label>
                    <input
                      type="tel"
                      value={buyerPhone}
                      onChange={(e) => setBuyerPhone(e.target.value)}
                      className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-lg text-xs"
                      placeholder="01XXXXXXXXX"
                    />
                  </div>

                  <div>
                    <label className="block text-[11px] font-semibold text-slate-700 mb-1">সম্পূর্ণ ডেলিভারি ঠিকানা (Full Address) *</label>
                    <textarea
                      rows={2}
                      value={buyerAddress}
                      onChange={(e) => setBuyerAddress(e.target.value)}
                      className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    />
                  </div>

                  <div>
                    <label className="block text-[11px] font-semibold text-slate-700 mb-1">ডেলিভারি এরিয়া (Delivery Zone)</label>
                    <select
                      value={deliveryCharge}
                      onChange={(e) => setDeliveryCharge(parseInt(e.target.value))}
                      className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium"
                    >
                      <option value={60}>ঢাকা সিটির ভিতরে (Inside Dhaka) — ৳৬০</option>
                      <option value={100}>ঢাকা সাব-এরিয়া (Gazipur/Savar/N'ganj) — ৳১০০</option>
                      <option value={130}>ঢাকার বাইরে সারা বাংলাদেশ (Outside Dhaka) — ৳১৩০</option>
                    </select>
                  </div>

                  <div className="p-3 bg-blue-50/70 border border-blue-200 rounded-xl space-y-1 font-mono">
                    <div className="flex justify-between text-slate-600">
                      <span>সাব-টোটাল:</span>
                      <span>৳{(selectedVariant.sellingPrice * quantity).toLocaleString()}</span>
                    </div>
                    <div className="flex justify-between text-slate-600">
                      <span>ডেলিভারি চার্জ:</span>
                      <span>৳{deliveryCharge}</span>
                    </div>
                    <div className="flex justify-between font-bold text-sm text-[#0F4C81] pt-1 border-t border-blue-200">
                      <span>সর্বমোট প্রদেয় (COD Due):</span>
                      <span>৳{totalOrderAmount.toLocaleString()}</span>
                    </div>
                  </div>

                  <button
                    onClick={handleQuickCheckout}
                    className="w-full min-h-[48px] bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all"
                  >
                    <CheckCircle2 className="w-4 h-4" />
                    <span>অর্ডার নিশ্চিত করুন (Confirm ৳{totalOrderAmount})</span>
                  </button>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
