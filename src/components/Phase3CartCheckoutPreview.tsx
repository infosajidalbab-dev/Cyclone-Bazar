import React, { useState } from 'react';
import { ShoppingBag, Truck, CheckCircle2, ShieldCheck, ArrowRight, Trash2, Search, AlertCircle, RefreshCw, MapPin } from 'lucide-react';

interface CartItem {
  id: string;
  productId: number;
  name: string;
  variantTitle?: string;
  sku: string;
  price: number;
  quantity: number;
  stock: number;
}

export const Phase3CartCheckoutPreview: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'cart' | 'checkout' | 'confirmation' | 'tracker'>('checkout');

  // Cart State
  const [cartItems, setCartItems] = useState<CartItem[]>([
    {
      id: 'item_1',
      productId: 1,
      name: 'Cyclone TWS Pro Wireless Earbuds',
      variantTitle: 'Midnight Black / Standard',
      sku: 'CM-EAR-BLK-01',
      price: 1250,
      quantity: 1,
      stock: 12,
    },
    {
      id: 'item_2',
      productId: 2,
      name: 'Cyclone 65W Braided Fast Type-C Cable',
      variantTitle: '2 Meter / Metallic Grey',
      sku: 'CM-CBL-65W-2M',
      price: 350,
      quantity: 2,
      stock: 30,
    }
  ]);

  // Checkout Form State (Section 7.5 Specs)
  const [customerName, setCustomerName] = useState('Sajid Pramanik');
  const [customerPhone, setCustomerPhone] = useState('01712345678');
  const [district, setDistrict] = useState<'Dhaka' | 'Chattogram' | 'Sylhet' | 'Rajshahi' | 'Bogura' | 'Khulna'>('Dhaka');
  const [area, setArea] = useState('Mirpur 10, Section 6');
  const [streetAddress, setStreetAddress] = useState('House 14, Road 3, Block C');
  const [landmark, setLandmark] = useState('Near Metro Station Pillar 240');
  const [deliveryNote, setDeliveryNote] = useState('Please call before arriving');
  const [isProcessing, setIsProcessing] = useState(false);
  const [confirmedOrder, setConfirmedOrder] = useState<any>(null);

  // Tracker State
  const [trackOrderNumber, setTrackOrderNumber] = useState('CM-20261002-8821');
  const [trackPhone, setTrackPhone] = useState('01712345678');
  const [trackedResult, setTrackedResult] = useState<any>(null);
  const [trackError, setTrackError] = useState<string | null>(null);

  // Dynamic delivery charge logic: Inside Dhaka = ৳60 | Outside Dhaka = ৳120
  const deliveryCharge = district === 'Dhaka' ? 60 : 120;
  const subtotal = cartItems.reduce((acc, item) => acc + (item.price * item.quantity), 0);
  const totalAmount = subtotal + deliveryCharge;
  const isPhoneValid = /^(?:\+?880|0)?1[3-9]\d{8}$/.test(customerPhone);

  const updateQuantity = (id: string, delta: number) => {
    setCartItems(prev => prev.map(item => {
      if (item.id === id) {
        const newQty = Math.max(1, Math.min(item.stock, item.quantity + delta));
        return { ...item, quantity: newQty };
      }
      return item;
    }));
  };

  const removeItem = (id: string) => {
    setCartItems(prev => prev.filter(item => item.id !== id));
  };

  const handlePlaceOrder = () => {
    if (!isPhoneValid || cartItems.length === 0) return;

    setIsProcessing(true);
    setTimeout(() => {
      // Simulates atomic DB::transaction with Product::lockForUpdate()
      const newOrderNumber = `CM-20261002-${Math.floor(1000 + Math.random() * 9000)}`;
      const orderData = {
        orderNumber: newOrderNumber,
        customerName,
        phone: customerPhone,
        district,
        address: `${streetAddress}, ${area}, ${district}`,
        landmark,
        subtotal,
        deliveryCharge,
        totalAmount,
        paymentMethod: 'Cash on Delivery (COD)',
        createdAt: 'Just Now',
        items: [...cartItems],
        trackingCode: `STD-${Math.floor(100000 + Math.random() * 900000)}`
      };

      setConfirmedOrder(orderData);
      setTrackOrderNumber(newOrderNumber);
      setTrackPhone(customerPhone);
      setCartItems([]);
      setIsProcessing(false);
      setActiveTab('confirmation');
    }, 800);
  };

  const handleTrack = () => {
    setTrackError(null);
    if (!trackOrderNumber || !trackPhone) {
      setTrackError('Please enter both Order ID and Phone Number.');
      return;
    }

    if (confirmedOrder && confirmedOrder.orderNumber.toUpperCase() === trackOrderNumber.trim().toUpperCase()) {
      setTrackedResult({
        ...confirmedOrder,
        status: 'confirmed',
        courier: 'Steadfast Courier',
        timeline: [
          { title: 'Order Placed (COD)', time: 'Just Now', note: 'Customer placed order via Single-Page Checkout.' },
          { title: 'Confirmed by Operations', time: 'Pending Dispatch', note: 'Inventory verified under pessimistic lock.' }
        ]
      });
      return;
    }

    // Default mock response for initial demo tracking
    setTrackedResult({
      orderNumber: trackOrderNumber.toUpperCase(),
      customerName: 'Tanvir Hossain',
      phone: trackPhone,
      status: 'handed_over_to_courier',
      courier: 'Steadfast Courier',
      trackingCode: 'STD-884129',
      address: 'House 42, Road 7, Block C, Mirpur 10, Dhaka',
      totalAmount: 1310.00,
      paymentMethod: 'Cash on Delivery (COD)',
      items: [
        { name: 'Cyclone TWS Pro Wireless Earbuds', sku: 'CM-EAR-BLK-01', quantity: 1, price: 1250 }
      ],
      timeline: [
        { title: 'Order Placed', time: '10:15 AM', note: 'Single-page checkout processed.' },
        { title: 'Confirmed by Hub', time: '11:00 AM', note: 'Phone call verified with buyer.' },
        { title: 'Handed Over to Courier', time: '01:30 PM', note: 'Consignment STD-884129 generated.' }
      ]
    });
  };

  return (
    <div className="space-y-6">
      {/* Intro info bar */}
      <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-semibold text-slate-900 block">Phase 3: Shopping Cart, Single-Page Checkout & Tracking</span>
          <span className="text-slate-500">
            Dynamic delivery charges (Dhaka: ৳60 | Outside: ৳120), pessimistic lock <code className="text-[#0F4C81] font-mono">lockForUpdate()</code>, and guest order lookup.
          </span>
        </div>

        {/* View Switcher Pills */}
        <div className="flex items-center gap-1 bg-slate-100 p-1 rounded-lg">
          <button
            onClick={() => setActiveTab('cart')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeTab === 'cart' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Cart ({cartItems.length})
          </button>
          <button
            onClick={() => setActiveTab('checkout')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeTab === 'checkout' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Single-Page Checkout
          </button>
          <button
            onClick={() => setActiveTab('tracker')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeTab === 'tracker' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Order Tracker
          </button>
        </div>
      </div>

      {/* VIEW 1: SHOPPING CART */}
      {activeTab === 'cart' && (
        <div className="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <h2 className="text-base font-bold text-slate-900">শপিং কার্ট (Cart Items)</h2>
            <span className="text-xs text-slate-500 font-mono">Livewire/Frontend/Cart.php</span>
          </div>

          {cartItems.length === 0 ? (
            <div className="text-center py-10 space-y-3">
              <ShoppingBag className="w-12 h-12 text-slate-300 mx-auto" />
              <p className="text-xs text-slate-500">আপনার কার্ট বর্তমানে খালি।</p>
              <button
                onClick={() => setCartItems([
                  {
                    id: 'item_1',
                    productId: 1,
                    name: 'Cyclone TWS Pro Wireless Earbuds',
                    variantTitle: 'Midnight Black / Standard',
                    sku: 'CM-EAR-BLK-01',
                    price: 1250,
                    quantity: 1,
                    stock: 12,
                  }
                ])}
                className="px-4 py-2 bg-[#0F4C81] text-white text-xs font-semibold rounded-xl"
              >
                পণ্য যোগ করুন (Add Sample Product)
              </button>
            </div>
          ) : (
            <div className="space-y-4">
              <div className="divide-y divide-slate-100 border border-slate-200 rounded-2xl overflow-hidden">
                {cartItems.map((item) => (
                  <div key={item.id} className="p-4 flex items-center justify-between gap-3 text-xs bg-white">
                    <div className="flex-1 min-w-0">
                      <h4 className="font-bold text-slate-900 truncate">{item.name}</h4>
                      {item.variantTitle && (
                        <span className="text-[11px] text-[#0F4C81] font-semibold block">{item.variantTitle}</span>
                      )}
                      <span className="font-mono text-slate-500">৳{item.price} each (SKU: {item.sku})</span>
                    </div>

                    {/* Stepper */}
                    <div className="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                      <button
                        onClick={() => updateQuantity(item.id, -1)}
                        className="w-8 h-8 flex items-center justify-center font-bold hover:bg-slate-200"
                      >-</button>
                      <span className="w-8 text-center font-mono font-bold">{item.quantity}</span>
                      <button
                        onClick={() => updateQuantity(item.id, 1)}
                        className="w-8 h-8 flex items-center justify-center font-bold hover:bg-slate-200"
                      >+</button>
                    </div>

                    <div className="text-right font-mono font-bold text-[#0F4C81] w-20">
                      ৳{(item.price * item.quantity).toFixed(2)}
                    </div>

                    <button
                      onClick={() => removeItem(item.id)}
                      className="text-slate-400 hover:text-rose-600 p-1"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                ))}
              </div>

              <div className="p-4 bg-slate-50 rounded-2xl space-y-2 text-xs font-mono">
                <div className="flex justify-between text-slate-600">
                  <span>মোট পণ্য মূল্য (Subtotal):</span>
                  <span>৳{subtotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between text-slate-600">
                  <span>ডেলিভারি চার্জ ({district === 'Dhaka' ? 'ঢাকা সিটি' : 'ঢাকার বাইরে'}):</span>
                  <span>৳{deliveryCharge.toFixed(2)}</span>
                </div>
                <div className="flex justify-between text-sm font-bold text-[#0F4C81] pt-2 border-t border-slate-200">
                  <span>সর্বমোট প্রদেয়:</span>
                  <span>৳{totalAmount.toFixed(2)}</span>
                </div>
              </div>

              <button
                onClick={() => setActiveTab('checkout')}
                className="w-full min-h-[46px] bg-[#FF6B35] hover:bg-[#e85520] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md"
              >
                <span>চেকআউট-এ এগিয়ে যান (Proceed to Checkout)</span>
                <ArrowRight className="w-4 h-4" />
              </button>
            </div>
          )}
        </div>
      )}

      {/* VIEW 2: SINGLE-PAGE CHECKOUT (Section 7.5 Specs) */}
      {activeTab === 'checkout' && (
        <div className="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
              <h2 className="text-lg font-bold text-slate-900">সিঙ্গেল-পেজ চেকআউট (Section 7.5 Spec)</h2>
              <p className="text-xs text-slate-500">কোনো সাইন-আপ প্রয়োজন নেই। ক্যাশ অন ডেলিভারিতে দ্রুত অর্ডার করুন।</p>
            </div>
            <span className="text-[11px] font-mono text-[#0F4C81] bg-blue-50 px-2 py-1 rounded">
              Atomic DB::transaction
            </span>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {/* Customer & Address Form */}
            <div className="lg:col-span-7 space-y-4 text-xs">
              <div className="p-4 bg-slate-50 rounded-2xl space-y-3">
                <span className="font-bold text-slate-900 block">১. গ্রাহকের নাম ও মোবাইল নম্বর</span>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-700 mb-1">পূর্ণ নাম *</label>
                  <input
                    type="text"
                    value={customerName}
                    onChange={(e) => setCustomerName(e.target.value)}
                    className="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs"
                  />
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-700 mb-1">মোবাইল নম্বর (11 Digits) *</label>
                  <div className="relative">
                    <input
                      type="tel"
                      value={customerPhone}
                      onChange={(e) => setCustomerPhone(e.target.value)}
                      placeholder="01XXXXXXXXX"
                      className={`w-full p-2.5 font-mono bg-white border rounded-xl text-xs ${
                        customerPhone && !isPhoneValid ? 'border-rose-400' : 'border-slate-200'
                      }`}
                    />
                    {isPhoneValid && (
                      <CheckCircle2 className="w-4 h-4 text-[#28A745] absolute right-3 top-3" />
                    )}
                  </div>
                  {!isPhoneValid && customerPhone.length > 0 && (
                    <span className="text-[10px] text-rose-500 mt-1 block">সঠিক ১১ ডিজিটের বাংলাদেশি নম্বর দিন (যেমন: 01712345678)।</span>
                  )}
                </div>
              </div>

              {/* Delivery District & Address */}
              <div className="p-4 bg-slate-50 rounded-2xl space-y-3">
                <div className="flex items-center justify-between">
                  <span className="font-bold text-slate-900">২. ডেলিভারি এলাকা ও ঠিকানা</span>
                  <span className="text-[11px] font-mono font-bold text-[#0F4C81]">
                    চার্জ: ৳{deliveryCharge} ({district === 'Dhaka' ? 'Inside Dhaka' : 'Outside Dhaka'})
                  </span>
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-700 mb-1">জেলা (District) *</label>
                  <select
                    value={district}
                    onChange={(e) => setDistrict(e.target.value as any)}
                    className="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs font-medium"
                  >
                    <option value="Dhaka">ঢাকা (Dhaka) — ডেলিভারি চার্জ ৳৬০</option>
                    <option value="Chattogram">চট্টগ্রাম (Chattogram) — ডেলিভারি চার্জ ৳১২০</option>
                    <option value="Sylhet">সিলেট (Sylhet) — ডেলিভারি চার্জ ৳১২০</option>
                    <option value="Rajshahi">রাজশাহী (Rajshahi) — ডেলিভারি চার্জ ৳১২০</option>
                    <option value="Bogura">বগুড়া (Bogura) — ডেলিভারি চার্জ ৳১২০</option>
                    <option value="Khulna">খুলনা (Khulna) — ডেলিভারি চার্জ ৳১২০</option>
                  </select>
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-700 mb-1">থানা / এলাকা (Area / Thana) *</label>
                  <input
                    type="text"
                    value={area}
                    onChange={(e) => setArea(e.target.value)}
                    className="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs"
                  />
                </div>

                <div>
                  <label className="block text-[11px] font-semibold text-slate-700 mb-1">পূর্ণ ঠিকানা (House, Road, Block) *</label>
                  <input
                    type="text"
                    value={streetAddress}
                    onChange={(e) => setStreetAddress(e.target.value)}
                    className="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-xs"
                  />
                </div>

                <div className="grid grid-cols-2 gap-2">
                  <div>
                    <label className="block text-[10px] text-slate-500 mb-0.5">পরিচিত স্থান (Landmark)</label>
                    <input
                      type="text"
                      value={landmark}
                      onChange={(e) => setLandmark(e.target.value)}
                      className="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs"
                    />
                  </div>
                  <div>
                    <label className="block text-[10px] text-slate-500 mb-0.5">ডেলিভারি নোট</label>
                    <input
                      type="text"
                      value={deliveryNote}
                      onChange={(e) => setDeliveryNote(e.target.value)}
                      className="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs"
                    />
                  </div>
                </div>
              </div>

              {/* Payment Option */}
              <div className="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between">
                <div>
                  <span className="font-bold text-slate-900 block">ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
                  <span className="text-[11px] text-emerald-800">পণ্য হাতে পাওয়ার পর নগদ টাকা প্রদান করুন।</span>
                </div>
                <span className="w-3 h-3 rounded-full bg-[#28A745]" />
              </div>
            </div>

            {/* Right Order Summary & Confirm */}
            <div className="lg:col-span-5 space-y-4">
              <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 text-xs">
                <span className="font-bold text-slate-800 block">অর্ডার আইটেম ({cartItems.length})</span>

                <div className="space-y-2 divide-y divide-slate-200/60 max-h-48 overflow-y-auto pr-1">
                  {cartItems.map((item) => (
                    <div key={item.id} className="pt-2 first:pt-0 flex justify-between items-center">
                      <div className="truncate pr-2">
                        <span className="font-semibold text-slate-800 block truncate">{item.name}</span>
                        <span className="text-[10px] text-slate-500 font-mono">{item.quantity} x ৳{item.price}</span>
                      </div>
                      <span className="font-mono font-bold text-slate-900 shrink-0">
                        ৳{(item.price * item.quantity).toFixed(2)}
                      </span>
                    </div>
                  ))}
                </div>

                <div className="pt-2 border-t border-slate-200 font-mono space-y-1.5 text-xs">
                  <div className="flex justify-between text-slate-600">
                    <span>পণ্যের মূল্য:</span>
                    <span>৳{subtotal.toFixed(2)}</span>
                  </div>
                  <div className="flex justify-between text-slate-600">
                    <span>ডেলিভারি চার্জ:</span>
                    <span className="font-bold text-[#0F4C81]">৳{deliveryCharge.toFixed(2)}</span>
                  </div>
                  <div className="flex justify-between font-bold text-sm text-[#0F4C81] pt-1 border-t border-slate-200">
                    <span>সর্বমোট (COD Due):</span>
                    <span>৳{totalAmount.toFixed(2)}</span>
                  </div>
                </div>

                <button
                  disabled={!isPhoneValid || cartItems.length === 0 || isProcessing}
                  onClick={handlePlaceOrder}
                  className="w-full min-h-[48px] bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all disabled:opacity-50"
                >
                  {isProcessing ? (
                    <>
                      <RefreshCw className="w-4 h-4 animate-spin" />
                      <span>স্টক লক ও অর্ডার প্রসেস হচ্ছে...</span>
                    </>
                  ) : (
                    <>
                      <CheckCircle2 className="w-4 h-4" />
                      <span>অর্ডার নিশ্চিত করুন (Confirm ৳{totalAmount.toFixed(2)})</span>
                    </>
                  )}
                </button>

                <p className="text-[10px] text-slate-400 text-center">
                  নিশ্চিত করার পর অর্ডার কনফার্মেশন ও ট্র্যাকিং আইডি প্রদান করা হবে।
                </p>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* VIEW 3: ORDER CONFIRMATION */}
      {activeTab === 'confirmation' && confirmedOrder && (
        <div className="max-w-md mx-auto bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 text-center space-y-5 shadow-sm">
          <div className="w-16 h-16 bg-emerald-50 text-[#28A745] rounded-full flex items-center justify-center mx-auto border-4 border-emerald-100">
            <CheckCircle2 className="w-8 h-8" />
          </div>

          <div>
            <h2 className="text-xl font-bold text-slate-900">অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h2>
            <p className="text-xs text-slate-500 mt-1">ক্যাশ অন ডেলিভারিতে অর্ডারটি ডাটাবেজে কনফার্ম করা হয়েছে।</p>
          </div>

          <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200 text-left font-mono text-xs space-y-2">
            <div className="flex justify-between items-center pb-2 border-b border-slate-200">
              <span className="text-slate-500">অর্ডার ট্র্যাকিং আইডি:</span>
              <span className="font-bold text-[#0F4C81]">{confirmedOrder.orderNumber}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-slate-500">কাস্টমার ফোন:</span>
              <span className="text-slate-800">{confirmedOrder.phone}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-slate-500">ডেলিভারি চার্জ:</span>
              <span className="text-slate-800">৳{confirmedOrder.deliveryCharge}</span>
            </div>
            <div className="flex justify-between font-bold text-sm pt-1 border-t border-slate-200">
              <span className="text-slate-700">সর্বমোট প্রদেয়:</span>
              <span className="text-[#FF6B35]">৳{confirmedOrder.totalAmount.toFixed(2)}</span>
            </div>
          </div>

          <div className="space-y-2">
            <button
              onClick={() => {
                setActiveTab('tracker');
                handleTrack();
              }}
              className="w-full min-h-[44px] bg-[#0F4C81] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5"
            >
              <span>লাইভ স্ট্যাটাস ট্র্যাক করুন</span>
              <ArrowRight className="w-4 h-4" />
            </button>

            <button
              onClick={() => setActiveTab('checkout')}
              className="w-full min-h-[44px] bg-slate-100 text-slate-700 font-semibold rounded-xl text-xs hover:bg-slate-200"
            >
              নতুন অর্ডার করুন
            </button>
          </div>
        </div>
      )}

      {/* VIEW 4: GUEST ORDER TRACKER */}
      {activeTab === 'tracker' && (
        <div className="max-w-2xl mx-auto space-y-5">
          <div className="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
            <div>
              <h2 className="text-base font-bold text-slate-900">গেস্ট অর্ডার ট্র্যাকিং (Order Tracker)</h2>
              <p className="text-xs text-slate-500 mt-0.5">
                Composite index <code className="text-[#0F4C81] font-mono">['order_number', 'customer_phone']</code> দিয়ে লাইভ ট্র্যাকিং।
              </p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">অর্ডার নম্বর (Order ID) *</label>
                <input
                  type="text"
                  value={trackOrderNumber}
                  onChange={(e) => setTrackOrderNumber(e.target.value)}
                  placeholder="CM-20261002-8821"
                  className="w-full p-2.5 font-mono uppercase bg-slate-50 border border-slate-200 rounded-xl text-xs"
                />
              </div>

              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">বিলিং মোবাইল নম্বর *</label>
                <input
                  type="tel"
                  value={trackPhone}
                  onChange={(e) => setTrackPhone(e.target.value)}
                  placeholder="017XXXXXXXX"
                  className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs"
                />
              </div>
            </div>

            <button
              onClick={handleTrack}
              className="w-full min-h-[44px] bg-[#0F4C81] hover:bg-[#0A355C] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2"
            >
              <Search className="w-4 h-4" />
              <span>অর্ডার অনুসন্ধান করুন (Search Order)</span>
            </button>

            {trackError && (
              <div className="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs">
                {trackError}
              </div>
            )}
          </div>

          {trackedResult && (
            <div className="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5 text-xs">
              <div className="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                  <span className="font-mono font-bold text-sm text-slate-900">{trackedResult.orderNumber}</span>
                  <span className="text-[11px] text-slate-500 block font-sans">কাস্টমার: {trackedResult.customerName} ({trackedResult.phone})</span>
                </div>
                <div className="text-right font-mono">
                  <span className="text-[10px] text-slate-400 block">COD বকেয়া:</span>
                  <span className="font-bold text-sm text-[#0F4C81]">৳{trackedResult.totalAmount.toFixed(2)}</span>
                </div>
              </div>

              {/* Courier Box */}
              {trackedResult.courier && (
                <div className="p-3.5 bg-purple-50/70 border border-purple-200 rounded-2xl flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <Truck className="w-4 h-4 text-purple-700" />
                    <div>
                      <span className="font-bold text-purple-900 block">{trackedResult.courier}</span>
                      <span className="text-[10px] text-purple-700 font-mono">কনসাইনমেন্ট কোড: {trackedResult.trackingCode}</span>
                    </div>
                  </div>
                  <span className="text-[10px] bg-purple-200 text-purple-900 px-2 py-0.5 rounded font-mono font-bold">IN TRANSIT</span>
                </div>
              )}

              {/* Progress Steps */}
              <div className="space-y-2">
                <span className="font-bold text-slate-800 uppercase tracking-wider block text-[11px]">অর্ডার টাইমলাইন</span>
                <div className="space-y-2">
                  {trackedResult.timeline.map((t: any, i: number) => (
                    <div key={i} className="p-2.5 bg-slate-50 rounded-xl border border-slate-100 font-mono text-[11px]">
                      <div className="flex justify-between font-bold text-[#0F4C81] mb-0.5">
                        <span>{t.title}</span>
                        <span className="text-slate-400">{t.time}</span>
                      </div>
                      <span className="text-slate-600 font-sans text-xs">{t.note}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Address */}
              <div className="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <span className="font-bold text-slate-800 block text-[11px] mb-1">ডেলিভারি ঠিকানা:</span>
                <p className="text-slate-600">{trackedResult.address}</p>
              </div>
            </div>
          )}
        </div>
      )}
    </div>
  );
};
