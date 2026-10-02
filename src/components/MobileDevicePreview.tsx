import React, { useState } from 'react';
import { BANGLADESH_DIVISIONS, DELIVERY_ZONES } from '../data/bangladeshLocations';
import { ShoppingBag, MapPin, Phone, Truck, ShieldCheck, CheckCircle2, Search, ArrowRight, RefreshCw, AlertCircle } from 'lucide-react';

export const MobileDevicePreview: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'checkout' | 'track' | 'concurrency'>('checkout');

  // Customer State
  const [customerName, setCustomerName] = useState('Sajid Pramanik');
  const [customerPhone, setCustomerPhone] = useState('01712345678');
  
  // Bangladesh Address Cascading State (Division -> District -> Upazila -> Area)
  const [selectedDivisionId, setSelectedDivisionId] = useState('dhaka');
  const [selectedDistrictId, setSelectedDistrictId] = useState('dhaka_city');
  const [selectedUpazila, setSelectedUpazila] = useState('Mirpur');
  const [area, setArea] = useState('Mirpur 10, Block C');
  const [streetAddress, setStreetAddress] = useState('House 42, Road 7');
  const [landmark, setLandmark] = useState('Near Metro Rail Pillar 240');

  // Delivery Zone
  const [deliveryZoneId, setDeliveryZoneId] = useState<number>(1);
  const selectedZone = DELIVERY_ZONES.find(z => z.id === deliveryZoneId) || DELIVERY_ZONES[0];

  // Cart Item & Inventory
  const [productStock, setProductStock] = useState<number>(8);
  const [orderQuantity, setOrderQuantity] = useState<number>(1);
  const unitPrice = 1250;
  const deliveryCharge = selectedZone.baseCharge;
  const totalAmount = (unitPrice * orderQuantity) + deliveryCharge;

  // Order Submission State
  const [isProcessing, setIsProcessing] = useState(false);
  const [placedOrder, setPlacedOrder] = useState<{
    orderNumber: string;
    phone: string;
    total: number;
    transactionId: string;
    deliveryDays: string;
  } | null>(null);

  // Tracking State
  const [trackOrderNumber, setTrackOrderNumber] = useState('CM-20261002-8821');
  const [trackPhone, setTrackPhone] = useState('01712345678');
  const [trackResult, setTrackResult] = useState<any>(null);

  // Concurrency Simulation State
  const [concurrencyLogs, setConcurrencyLogs] = useState<string[]>([]);
  const [isSimulatingRace, setIsSimulatingRace] = useState(false);

  // BD Phone validation: 11 digits starting with 01[3-9]
  const isPhoneValid = /^(?:\+?880|0)?1[3-9]\d{8}$/.test(customerPhone);

  const currentDivision = BANGLADESH_DIVISIONS.find(d => d.id === selectedDivisionId) || BANGLADESH_DIVISIONS[0];
  const currentDistrict = currentDivision.districts.find(d => d.id === selectedDistrictId) || currentDivision.districts[0];

  const handleDivisionChange = (divId: string) => {
    setSelectedDivisionId(divId);
    const div = BANGLADESH_DIVISIONS.find(d => d.id === divId);
    if (div && div.districts.length > 0) {
      setSelectedDistrictId(div.districts[0].id);
      setSelectedUpazila(div.districts[0].upazilas[0] || '');
      // Auto update delivery zone
      const zoneCode = div.districts[0].defaultZoneCode;
      const zone = DELIVERY_ZONES.find(z => z.code === zoneCode);
      if (zone) setDeliveryZoneId(zone.id);
    }
  };

  const handleDistrictChange = (distId: string) => {
    setSelectedDistrictId(distId);
    const dist = currentDivision.districts.find(d => d.id === distId);
    if (dist) {
      setSelectedUpazila(dist.upazilas[0] || '');
      const zone = DELIVERY_ZONES.find(z => z.code === dist.defaultZoneCode);
      if (zone) setDeliveryZoneId(zone.id);
    }
  };

  const handlePlaceOrder = () => {
    if (!isPhoneValid) return;
    if (productStock < orderQuantity) return;

    setIsProcessing(true);
    setTimeout(() => {
      // Simulating OrderService::placeOrder() inside DB::transaction with lockForUpdate()
      const newOrderNum = `CM-20261002-${Math.floor(1000 + Math.random() * 9000)}`;
      setProductStock(prev => Math.max(0, prev - orderQuantity));
      setPlacedOrder({
        orderNumber: newOrderNum,
        phone: customerPhone,
        total: totalAmount,
        transactionId: `COD-${newOrderNum}-BD`,
        deliveryDays: `${selectedZone.estimatedDaysMin}-${selectedZone.estimatedDaysMax} Days`
      });
      setIsProcessing(false);
    }, 600);
  };

  const handleTrackOrder = () => {
    // Simulating Order::track($order_number, $phone) using composite index
    if (!trackOrderNumber || !trackPhone) return;

    setTrackResult({
      orderNumber: trackOrderNumber.toUpperCase(),
      phone: trackPhone,
      status: 'confirmed',
      paymentMethod: 'Cash on Delivery (COD)',
      paymentStatus: 'pending_collection',
      estimatedDelivery: 'Tomorrow by 6:00 PM',
      courier: 'Steadfast Courier (Tracking: STD-849120)',
      deliveryAddress: 'Mirpur 10, Block C, Dhaka',
      amountDue: '৳1,310.00',
      compositeIndexUsed: 'idx_orders_tracking_number_phone'
    });
  };

  const simulateRaceCondition = () => {
    setIsSimulatingRace(true);
    setConcurrencyLogs([
      'Simulating 3 concurrent buyers attempting to buy the last 2 units...',
      'Buyer A: Initiates checkout for 1 item',
      'Buyer B: Initiates checkout for 1 item',
      'Buyer C: Initiates checkout for 1 item',
      '🔒 DB::transaction started: Product::where("id", 1)->lockForUpdate()',
      'Buyer A acquired pessimistic row lock. Stock check: 2 >= 1. Deducted stock to 1. Transaction committed.',
      'Buyer B acquired pessimistic row lock. Stock check: 1 >= 1. Deducted stock to 0. Transaction committed.',
      '❌ Buyer C acquired pessimistic row lock. Stock check: 0 < 1. Exception thrown: "Product out of stock". Rolled back.',
      '✅ 0 overselling occurred. Atomic row-locking succeeded.'
    ]);
    setTimeout(() => setIsSimulatingRace(false), 800);
  };

  return (
    <div className="flex flex-col items-center justify-center p-2 sm:p-6 bg-slate-100 rounded-2xl border border-slate-200">
      {/* Mobile Shell (390px viewport target) */}
      <div className="w-full max-w-[400px] bg-white rounded-3xl shadow-xl border-4 border-slate-900 overflow-hidden flex flex-col min-h-[720px] relative">
        {/* Top Speaker / Notch */}
        <div className="bg-slate-900 h-6 flex items-center justify-center relative">
          <div className="w-20 h-3.5 bg-black rounded-b-xl flex items-center justify-center">
            <div className="w-8 h-1 bg-slate-800 rounded-full" />
          </div>
        </div>

        {/* Mobile Header (Strict Zone Contract: Brand - Subtitle - Cart) */}
        <div className="bg-[#0F4C81] text-white px-4 py-3 flex items-center justify-between">
          <div>
            <h1 className="font-bold text-base tracking-tight text-white flex items-center gap-1.5">
              <span>Cyclone Mart</span>
              <span className="text-[10px] bg-[#FF6B35] px-1.5 py-0.5 rounded font-mono font-medium">BD</span>
            </h1>
            <span className="text-[10px] text-blue-100 block">Mobile-First Dropshipping</span>
          </div>
          
          <div className="flex items-center gap-2">
            <span className="text-[11px] font-mono bg-white/10 px-2 py-0.5 rounded text-white">
              Stock: {productStock}
            </span>
          </div>
        </div>

        {/* Sub Navigation Segmented Tabs */}
        <div className="bg-slate-100 p-1 flex border-b border-slate-200">
          <button
            onClick={() => setActiveTab('checkout')}
            className={`flex-1 py-1.5 text-xs font-medium rounded-md transition-colors ${
              activeTab === 'checkout'
                ? 'bg-white text-[#0F4C81] shadow-xs'
                : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            COD Checkout
          </button>
          <button
            onClick={() => setActiveTab('track')}
            className={`flex-1 py-1.5 text-xs font-medium rounded-md transition-colors ${
              activeTab === 'track'
                ? 'bg-white text-[#0F4C81] shadow-xs'
                : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Track Order
          </button>
          <button
            onClick={() => setActiveTab('concurrency')}
            className={`flex-1 py-1.5 text-xs font-medium rounded-md transition-colors ${
              activeTab === 'concurrency'
                ? 'bg-white text-[#0F4C81] shadow-xs'
                : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Race Lock
          </button>
        </div>

        {/* Content Body */}
        <div className="flex-1 overflow-y-auto p-4 space-y-4 text-xs">
          {/* TAB 1: COD CHECKOUT FLOW */}
          {activeTab === 'checkout' && (
            <>
              {placedOrder ? (
                <div className="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-center space-y-3">
                  <div className="w-12 h-12 bg-[#28A745] text-white rounded-full flex items-center justify-center mx-auto shadow-md">
                    <CheckCircle2 className="w-6 h-6" />
                  </div>
                  <div>
                    <h3 className="font-bold text-slate-900 text-sm">Order Placed Successfully!</h3>
                    <p className="text-slate-600 text-[11px] mt-0.5">
                      No advance payment needed. Pay cash upon delivery.
                    </p>
                  </div>

                  <div className="bg-white p-3 rounded-xl border border-emerald-100 text-left space-y-1.5 font-mono text-[11px]">
                    <div className="flex justify-between">
                      <span className="text-slate-500">Order No:</span>
                      <span className="font-bold text-[#0F4C81]">{placedOrder.orderNumber}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">Tracking Phone:</span>
                      <span className="text-slate-800">{placedOrder.phone}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">Total Due (COD):</span>
                      <span className="font-bold text-[#FF6B35]">৳{placedOrder.total.toFixed(2)}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">Est. Arrival:</span>
                      <span className="text-slate-800">{placedOrder.deliveryDays}</span>
                    </div>
                  </div>

                  <button
                    onClick={() => setPlacedOrder(null)}
                    className="w-full py-2.5 min-h-[44px] bg-[#0F4C81] text-white rounded-xl font-medium"
                  >
                    Place Another Order
                  </button>
                </div>
              ) : (
                <div className="space-y-4">
                  {/* Product Mini Card */}
                  <div className="bg-slate-50 p-3 rounded-xl border border-slate-200 flex items-center gap-3">
                    <div className="w-14 h-14 bg-blue-100/60 rounded-lg flex items-center justify-center text-[#0F4C81] font-bold text-xs shrink-0">
                      CM-EAR
                    </div>
                    <div className="flex-1 min-w-0">
                      <h4 className="font-semibold text-slate-900 truncate">Cyclone TWS Pro Earbuds</h4>
                      <p className="text-[11px] text-slate-500 font-mono">SKU: PROD-TWS-01</p>
                      <div className="flex items-center justify-between mt-1">
                        <span className="font-bold font-mono text-[#0F4C81]">৳{unitPrice}</span>
                        <div className="flex items-center gap-2">
                          <span className="text-slate-500 text-[11px]">Qty:</span>
                          <select
                            value={orderQuantity}
                            onChange={(e) => setOrderQuantity(parseInt(e.target.value))}
                            className="bg-white border border-slate-300 rounded px-1.5 py-0.5 text-xs font-mono"
                          >
                            {[1, 2, 3, 4].map(n => (
                              <option key={n} value={n}>{n}</option>
                            ))}
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Customer Information (01XXXXXXXXX) */}
                  <div className="space-y-2">
                    <span className="font-semibold text-slate-900 block">1. Customer Mobile & Name</span>
                    <input
                      type="text"
                      value={customerName}
                      onChange={(e) => setCustomerName(e.target.value)}
                      placeholder="Your Full Name"
                      className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    />
                    <div className="relative">
                      <input
                        type="tel"
                        value={customerPhone}
                        onChange={(e) => setCustomerPhone(e.target.value)}
                        placeholder="01XXXXXXXXX (11 digits)"
                        className={`w-full p-2.5 bg-slate-50 border rounded-lg text-xs font-mono ${
                          customerPhone && !isPhoneValid ? 'border-rose-400 focus:ring-rose-200' : 'border-slate-200'
                        }`}
                      />
                      {isPhoneValid && (
                        <CheckCircle2 className="w-4 h-4 text-emerald-600 absolute right-2.5 top-2.5" />
                      )}
                    </div>
                    {!isPhoneValid && customerPhone.length > 0 && (
                      <p className="text-[10px] text-rose-600">Must be a valid 11-digit BD number (e.g. 01712345678).</p>
                    )}
                  </div>

                  {/* 4-Tier Bangladesh Address Hierarchy */}
                  <div className="space-y-2.5 pt-2 border-t border-slate-100">
                    <div className="flex items-center justify-between">
                      <span className="font-semibold text-slate-900">2. Delivery Address (BD Hierarchy)</span>
                      <span className="text-[10px] text-slate-500 font-mono">Division ➔ District ➔ Upazila</span>
                    </div>

                    <div className="grid grid-cols-2 gap-2">
                      <div>
                        <label className="text-[10px] text-slate-500 block mb-1">Division</label>
                        <select
                          value={selectedDivisionId}
                          onChange={(e) => handleDivisionChange(e.target.value)}
                          className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium"
                        >
                          {BANGLADESH_DIVISIONS.map(div => (
                            <option key={div.id} value={div.id}>{div.name}</option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label className="text-[10px] text-slate-500 block mb-1">District</label>
                        <select
                          value={selectedDistrictId}
                          onChange={(e) => handleDistrictChange(e.target.value)}
                          className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium"
                        >
                          {currentDivision.districts.map(dist => (
                            <option key={dist.id} value={dist.id}>{dist.name}</option>
                          ))}
                        </select>
                      </div>
                    </div>

                    <div className="grid grid-cols-2 gap-2">
                      <div>
                        <label className="text-[10px] text-slate-500 block mb-1">Upazila / Thana</label>
                        <select
                          value={selectedUpazila}
                          onChange={(e) => setSelectedUpazila(e.target.value)}
                          className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium"
                        >
                          {currentDistrict.upazilas.map(upz => (
                            <option key={upz} value={upz}>{upz}</option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label className="text-[10px] text-slate-500 block mb-1">Area / Ward</label>
                        <input
                          type="text"
                          value={area}
                          onChange={(e) => setArea(e.target.value)}
                          placeholder="e.g. Block C / Section 6"
                          className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                        />
                      </div>
                    </div>

                    <input
                      type="text"
                      value={streetAddress}
                      onChange={(e) => setStreetAddress(e.target.value)}
                      placeholder="House, Road, Apartment info"
                      className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    />

                    <input
                      type="text"
                      value={landmark}
                      onChange={(e) => setLandmark(e.target.value)}
                      placeholder="Nearby Landmark (Helps Rider find you)"
                      className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                    />
                  </div>

                  {/* Delivery Zone & Charges */}
                  <div className="p-3 bg-blue-50/70 border border-blue-200/80 rounded-xl space-y-1">
                    <div className="flex items-center justify-between text-xs font-medium text-[#0F4C81]">
                      <span>{selectedZone.name} Delivery</span>
                      <span>৳{selectedZone.baseCharge}</span>
                    </div>
                    <p className="text-[11px] text-slate-600">
                      Estimated Delivery: {selectedZone.estimatedDaysMin} to {selectedZone.estimatedDaysMax} Days
                    </p>
                  </div>

                  {/* Payment Method Badge */}
                  <div className="p-3 bg-orange-50/80 border border-orange-200 rounded-xl flex items-center justify-between">
                    <div>
                      <span className="font-semibold text-slate-900 block">Cash on Delivery (ক্যাশ অন ডেলিভারি)</span>
                      <span className="text-[10px] text-slate-500">Pay ৳{totalAmount} in cash when rider hands over parcel</span>
                    </div>
                    <span className="w-2.5 h-2.5 rounded-full bg-[#FF6B35]" />
                  </div>

                  {/* Summary & Submit Button */}
                  <div className="pt-2">
                    <div className="flex items-center justify-between font-mono mb-2">
                      <span className="text-slate-600">Total Payable:</span>
                      <span className="text-base font-bold text-[#0F4C81]">৳{totalAmount}</span>
                    </div>

                    <button
                      disabled={!isPhoneValid || productStock < orderQuantity || isProcessing}
                      onClick={handlePlaceOrder}
                      className="w-full min-h-[48px] bg-[#FF6B35] hover:bg-[#e85520] active:scale-[0.98] text-white font-bold rounded-xl text-sm transition-all shadow-md flex items-center justify-center gap-2 disabled:bg-slate-300 disabled:cursor-not-allowed"
                    >
                      {isProcessing ? (
                        <>
                          <RefreshCw className="w-4 h-4 animate-spin" />
                          <span>Locking Inventory & Placing...</span>
                        </>
                      ) : (
                        <>
                          <ShoppingBag className="w-4 h-4" />
                          <span>Confirm Order (COD)</span>
                        </>
                      )}
                    </button>
                  </div>
                </div>
              )}
            </>
          )}

          {/* TAB 2: ORDER TRACKING (Order Number + Phone) */}
          {activeTab === 'track' && (
            <div className="space-y-4">
              <div className="bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2">
                <span className="font-semibold text-slate-900 block">Fast Guest & Customer Tracking</span>
                <p className="text-[11px] text-slate-600">
                  Backed by composite index <code className="bg-slate-200/80 px-1 py-0.5 rounded font-mono text-[10px]">['order_number', 'customer_phone']</code> for instant lookup.
                </p>

                <input
                  type="text"
                  value={trackOrderNumber}
                  onChange={(e) => setTrackOrderNumber(e.target.value)}
                  placeholder="Order ID (e.g. CM-20261002-8821)"
                  className="w-full p-2.5 bg-white border border-slate-300 rounded-lg text-xs font-mono"
                />

                <input
                  type="tel"
                  value={trackPhone}
                  onChange={(e) => setTrackPhone(e.target.value)}
                  placeholder="Billing Phone (01XXXXXXXXX)"
                  className="w-full p-2.5 bg-white border border-slate-300 rounded-lg text-xs font-mono"
                />

                <button
                  onClick={handleTrackOrder}
                  className="w-full min-h-[44px] bg-[#0F4C81] text-white font-medium rounded-lg text-xs flex items-center justify-center gap-2 hover:bg-[#0A355C] transition-colors"
                >
                  <Search className="w-4 h-4" />
                  <span>Query Order Status</span>
                </button>
              </div>

              {trackResult && (
                <div className="bg-white border border-slate-200 rounded-xl p-3.5 space-y-2.5 shadow-xs">
                  <div className="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span className="font-mono font-bold text-slate-900">{trackResult.orderNumber}</span>
                    <span className="text-[10px] font-mono bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-medium">
                      CONFIRMED
                    </span>
                  </div>

                  <div className="space-y-1.5 text-[11px]">
                    <div className="flex justify-between">
                      <span className="text-slate-500">Courier Partner:</span>
                      <span className="font-medium text-slate-800">{trackResult.courier}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">Destination:</span>
                      <span className="text-slate-800">{trackResult.deliveryAddress}</span>
                    </div>
                    <div className="flex justify-between">
                      <span className="text-slate-500">COD Cash to Handover:</span>
                      <span className="font-bold text-[#FF6B35] font-mono">{trackResult.amountDue}</span>
                    </div>
                  </div>

                  <div className="p-2 bg-blue-50 text-[#0F4C81] rounded text-[10px] font-mono flex items-center gap-1.5">
                    <ShieldCheck className="w-3.5 h-3.5 shrink-0" />
                    <span>Matched via {trackResult.compositeIndexUsed}</span>
                  </div>
                </div>
              )}
            </div>
          )}

          {/* TAB 3: CONCURRENCY RACE CONDITION TEST */}
          {activeTab === 'concurrency' && (
            <div className="space-y-3">
              <div className="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                <span className="font-semibold text-slate-900 block">Pessimistic Locking Verification</span>
                <p className="text-[11px] text-slate-600 leading-relaxed">
                  Demonstrates how <code className="bg-slate-200 px-1 rounded font-mono text-[10px]">Product::lockForUpdate()</code> inside <code className="bg-slate-200 px-1 rounded font-mono text-[10px]">DB::transaction()</code> guarantees zero inventory over-selling during flash drops.
                </p>

                <button
                  disabled={isSimulatingRace}
                  onClick={simulateRaceCondition}
                  className="w-full min-h-[44px] bg-[#0F4C81] text-white font-medium rounded-lg text-xs flex items-center justify-center gap-2 hover:bg-[#0A355C]"
                >
                  <RefreshCw className={`w-4 h-4 ${isSimulatingRace ? 'animate-spin' : ''}`} />
                  <span>Simulate 3 Concurrent Buyers</span>
                </button>
              </div>

              {concurrencyLogs.length > 0 && (
                <div className="bg-slate-900 text-slate-100 p-3 rounded-xl font-mono text-[10px] space-y-1 max-h-[260px] overflow-y-auto">
                  {concurrencyLogs.map((log, i) => (
                    <div
                      key={i}
                      className={
                        log.includes('✅')
                          ? 'text-emerald-400 font-bold'
                          : log.includes('❌')
                          ? 'text-rose-400'
                          : log.includes('🔒')
                          ? 'text-amber-300'
                          : 'text-slate-300'
                      }
                    >
                      {log}
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}
        </div>

        {/* Bottom Tab Bar (Pattern 1 Mobile Design Anchor) */}
        <div className="bg-white border-t border-slate-200 px-6 py-2.5 flex items-center justify-around text-slate-500">
          <button
            onClick={() => setActiveTab('checkout')}
            className={`flex flex-col items-center gap-0.5 ${activeTab === 'checkout' ? 'text-[#0F4C81]' : ''}`}
          >
            <ShoppingBag className="w-5 h-5" />
            <span className="text-[10px] font-medium">Order</span>
          </button>
          <button
            onClick={() => setActiveTab('track')}
            className={`flex flex-col items-center gap-0.5 ${activeTab === 'track' ? 'text-[#0F4C81]' : ''}`}
          >
            <Truck className="w-5 h-5" />
            <span className="text-[10px] font-medium">Track</span>
          </button>
          <button
            onClick={() => setActiveTab('concurrency')}
            className={`flex flex-col items-center gap-0.5 ${activeTab === 'concurrency' ? 'text-[#0F4C81]' : ''}`}
          >
            <ShieldCheck className="w-5 h-5" />
            <span className="text-[10px] font-medium">Atomic</span>
          </button>
        </div>
      </div>
    </div>
  );
};
