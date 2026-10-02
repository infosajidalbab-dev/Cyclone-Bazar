import React, { useState } from 'react';
import { DollarSign, ShoppingBag, Users, AlertTriangle, TrendingUp, Image, Settings, Sparkles, CheckCircle2, ChevronRight, Save, Plus, Trash2 } from 'lucide-react';

interface MockBanner {
  id: number;
  title: string;
  subtitle: string;
  position: 'hero' | 'middle' | 'footer';
  displayOrder: number;
  isActive: boolean;
  bgGradient: string;
}

export const Phase4AdminDashboardPreview: React.FC = () => {
  const [activeSection, setActiveSection] = useState<'dashboard' | 'banners' | 'settings' | 'home'>('dashboard');
  const [chartRange, setChartRange] = useState<'7days' | '30days'>('7days');

  // Settings State
  const [storeName, setStoreName] = useState('Cyclone Mart');
  const [orderPrefix, setOrderPrefix] = useState('CM');
  const [contactPhone, setContactPhone] = useState('01712345678');
  const [supportEmail, setSupportEmail] = useState('support@cyclonemart.com');
  const [dhakaCharge, setDhakaCharge] = useState(60);
  const [outsideCharge, setOutsideCharge] = useState(120);
  const [lowStockLimit, setLowStockLimit] = useState(5);
  const [codEnabled, setCodEnabled] = useState(true);
  const [settingsFlash, setSettingsFlash] = useState<string | null>(null);

  // Banners State
  const [banners, setBanners] = useState<MockBanner[]>([
    {
      id: 1,
      title: 'ধামাকা অফার: সকল গ্যাজেটে ৪০% পর্যন্ত ছাড়!',
      subtitle: 'ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে দ্রুত ডেলিভারি',
      position: 'hero',
      displayOrder: 1,
      isActive: true,
      bgGradient: 'from-slate-950 via-slate-900 to-blue-950'
    },
    {
      id: 2,
      title: 'প্রিমিয়াম অরিজিনাল TWS ইয়ারবাডস কালেকশন',
      subtitle: '৭ দিনের রিপ্লেসমেন্ট গ্যারান্টি সহ অর্ডার করুন',
      position: 'hero',
      displayOrder: 2,
      isActive: true,
      bgGradient: 'from-blue-950 via-indigo-950 to-slate-900'
    },
    {
      id: 3,
      title: 'ফ্রি হোম ডেলিভারি: ৩০০০ টাকার অর্ডারে',
      subtitle: 'শুধুমাত্র এই সপ্তাহের জন্য সীমিত অফার',
      position: 'middle',
      displayOrder: 1,
      isActive: true,
      bgGradient: 'from-orange-950 via-slate-900 to-slate-950'
    }
  ]);

  // Chart values (7 days or 30 days)
  const chart7Days = [
    { day: '26 Sep', amount: 18450 },
    { day: '27 Sep', amount: 24200 },
    { day: '28 Sep', amount: 21900 },
    { day: '29 Sep', amount: 31250 },
    { day: '30 Sep', amount: 28400 },
    { day: '01 Oct', amount: 36700 },
    { day: '02 Oct', amount: 42150 }
  ];

  const maxVal = 45000;

  const handleSaveSettings = () => {
    setSettingsFlash('Site Settings successfully updated in settings table & cached forever!');
    setTimeout(() => setSettingsFlash(null), 3000);
  };

  const toggleBanner = (id: number) => {
    setBanners(prev => prev.map(b => b.id === id ? { ...b, isActive: !b.isActive } : b));
  };

  const deleteBanner = (id: number) => {
    setBanners(prev => prev.filter(b => b.id !== id));
  };

  return (
    <div className="space-y-6">
      {/* Top Phase 4 Navigation Bar */}
      <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-semibold text-slate-900 block">Phase 4: Admin Dashboard, Banners, Settings & Final Launch</span>
          <span className="text-slate-500">
            Section 9.1 spec dashboard, banner management, site settings, routes organization, and SEO meta tags.
          </span>
        </div>

        {/* Sub-Tabs */}
        <div className="flex items-center gap-1 bg-slate-100 p-1 rounded-lg">
          <button
            onClick={() => setActiveSection('dashboard')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeSection === 'dashboard' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Dashboard
          </button>
          <button
            onClick={() => setActiveSection('banners')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeSection === 'banners' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Banners ({banners.length})
          </button>
          <button
            onClick={() => setActiveSection('settings')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeSection === 'settings' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Store Settings
          </button>
          <button
            onClick={() => setActiveSection('home')}
            className={`px-3 py-1.5 rounded-md font-medium text-xs transition-colors ${
              activeSection === 'home' ? 'bg-white text-[#0F4C81] shadow-xs' : 'text-slate-600 hover:text-slate-900'
            }`}
          >
            Storefront Home
          </button>
        </div>
      </div>

      {/* SECTION 1: ADMIN DASHBOARD */}
      {activeSection === 'dashboard' && (
        <div className="space-y-6">
          {/* Metric Cards (Section 9.1 Spec) */}
          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
            <div className="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
              <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Today's Sales</span>
              <span className="text-xl font-extrabold font-mono text-[#0F4C81] block mt-1">৳42,150</span>
              <span className="text-[10px] text-emerald-600 font-semibold mt-0.5 block">+18% vs yesterday</span>
            </div>

            <div className="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
              <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Today's Orders</span>
              <span className="text-xl font-extrabold font-mono text-slate-900 block mt-1">29</span>
              <span className="text-[10px] text-slate-500 mt-0.5 block">Avg: ৳1,453 / order</span>
            </div>

            <div className="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
              <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Sales</span>
              <span className="text-xl font-extrabold font-mono text-[#28A745] block mt-1">৳849,200</span>
              <span className="text-[10px] text-slate-500 mt-0.5 block">Lifetime Revenue</span>
            </div>

            <div className="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
              <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Orders</span>
              <span className="text-xl font-extrabold font-mono text-slate-900 block mt-1">642</span>
              <span className="text-[10px] text-slate-500 mt-0.5 block">Completed / COD</span>
            </div>

            <div className="p-4 bg-white border border-slate-200 rounded-2xl shadow-xs">
              <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block font-mono">Total Customers</span>
              <span className="text-xl font-extrabold font-mono text-slate-900 block mt-1">518</span>
              <span className="text-[10px] text-slate-500 mt-0.5 block">64 BD Districts</span>
            </div>

            <div className="p-4 bg-white border border-rose-200 rounded-2xl shadow-xs bg-rose-50/30">
              <span className="text-[10px] font-bold text-rose-700 uppercase tracking-wider block font-mono">Low Stock Alert</span>
              <span className="text-xl font-extrabold font-mono text-rose-600 block mt-1">4</span>
              <span className="text-[10px] text-rose-700 font-semibold mt-0.5 block">Urgently Restock</span>
            </div>
          </div>

          {/* Visual Sales Chart */}
          <div className="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-slate-100">
              <div>
                <h3 className="text-sm font-bold text-slate-900">দৈনিক সেলস ট্রেন্ড (Daily Revenue Trend)</h3>
                <span className="text-xs text-slate-500">বিকাশ এবং ক্যাশ অন ডেলিভারি (COD) সংগৃহীত রাজস্ব।</span>
              </div>
              <div className="flex items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs font-mono">
                <button
                  onClick={() => setChartRange('7days')}
                  className={`px-2.5 py-1 rounded-md ${chartRange === '7days' ? 'bg-white font-bold text-[#0F4C81] shadow-xs' : 'text-slate-600'}`}
                >
                  7 Days
                </button>
                <button
                  onClick={() => setChartRange('30days')}
                  className={`px-2.5 py-1 rounded-md ${chartRange === '30days' ? 'bg-white font-bold text-[#0F4C81] shadow-xs' : 'text-slate-600'}`}
                >
                  30 Days
                </button>
              </div>
            </div>

            <div className="h-48 w-full flex items-end gap-3 pt-6 pb-2 px-2 border-b border-slate-100">
              {chart7Days.map((item, idx) => {
                const heightPct = Math.round((item.amount / maxVal) * 100);
                return (
                  <div key={idx} className="flex-1 flex flex-col items-center gap-2 group relative h-full justify-end">
                    <div className="opacity-0 group-hover:opacity-100 transition-opacity absolute -top-7 bg-slate-900 text-white font-mono text-[10px] py-0.5 px-2 rounded whitespace-nowrap shadow-md pointer-events-none">
                      ৳{item.amount.toLocaleString()}
                    </div>
                    <div
                      className="w-full bg-[#0F4C81] group-hover:bg-[#FF6B35] rounded-t-lg transition-all"
                      style={{ height: `${heightPct}%` }}
                    />
                    <span className="text-[10px] text-slate-400 font-mono truncate">{item.day}</span>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Bottom Grid: Recent Orders & Low Stock */}
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div className="lg:col-span-7 bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-4">
              <h3 className="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Recent 10 Orders</h3>
              <div className="space-y-2 text-xs">
                {[
                  { id: 'CM-20261002-8821', customer: 'Tanvir Hossain', phone: '01712345678', amount: 1310, status: 'Confirmed' },
                  { id: 'CM-20261002-9402', customer: 'Nasrin Akter', phone: '01899123456', amount: 2630, status: 'Processing' },
                  { id: 'CM-20261001-7110', customer: 'Farhan Kabir', phone: '01911987654', amount: 1550, status: 'Delivered' },
                  { id: 'CM-20261001-6541', customer: 'Mehedi Hasan', phone: '01622334455', amount: 3200, status: 'Handed to Courier' },
                ].map((o, i) => (
                  <div key={i} className="p-2.5 bg-slate-50 rounded-xl flex items-center justify-between">
                    <div>
                      <span className="font-mono font-bold text-[#0F4C81] block">{o.id}</span>
                      <span className="text-[11px] text-slate-500">{o.customer} ({o.phone})</span>
                    </div>
                    <div className="text-right">
                      <span className="font-mono font-bold text-slate-900 block">৳{o.amount.toLocaleString()}</span>
                      <span className="text-[10px] text-emerald-700 font-semibold">{o.status}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            <div className="lg:col-span-5 bg-white border border-slate-200 rounded-3xl p-5 shadow-xs space-y-4">
              <h3 className="text-sm font-bold text-rose-700 border-b border-slate-100 pb-2">Low Stock Restock Alerts</h3>
              <div className="space-y-2 text-xs">
                {[
                  { name: 'Cyclone MagPulse 20000mAh Powerbank', sku: 'CM-PWR-20K-BLK', stock: 2, limit: 5, supplier: 'Chittagong Importers' },
                  { name: 'Cyclone TWS Pro (Glacier White)', sku: 'CM-EAR-WHT-02', stock: 3, limit: 5, supplier: 'Dhaka Hub' },
                  { name: 'Magnetic Desk Stand (Black)', sku: 'CM-STD-MAG', stock: 1, limit: 5, supplier: 'Gazipur Hub' },
                ].map((p, i) => (
                  <div key={i} className="p-2.5 bg-rose-50/50 border border-rose-100 rounded-xl flex justify-between items-center">
                    <div className="truncate pr-2">
                      <span className="font-semibold text-slate-800 block truncate">{p.name}</span>
                      <span className="text-[10px] text-slate-400 font-mono">SKU: {p.sku}</span>
                    </div>
                    <span className="font-mono font-bold text-rose-600 shrink-0">{p.stock} left</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* SECTION 2: PROMOTIONAL BANNERS */}
      {activeSection === 'banners' && (
        <div className="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
              <h2 className="text-base font-bold text-slate-900">হোমপেজ ব্যানার ম্যানেজমেন্ট (Promotional Banners)</h2>
              <p className="text-xs text-slate-500">Livewire/Admin/BannerManager.php CRUD</p>
            </div>
            <button
              onClick={() => {
                const newId = banners.length + 1;
                setBanners([
                  ...banners,
                  {
                    id: Date.now(),
                    title: `নতুন ব্যানার প্রমোশন #${newId}`,
                    subtitle: 'সারা দেশে দ্রুত ক্যাশ অন ডেলিভারি',
                    position: 'hero',
                    displayOrder: newId,
                    isActive: true,
                    bgGradient: 'from-blue-950 via-slate-900 to-indigo-950'
                  }
                ]);
              }}
              className="px-3.5 py-2 bg-[#0F4C81] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs"
            >
              <Plus className="w-4 h-4" />
              <span>নতুন ব্যানার যোগ করুন</span>
            </button>
          </div>

          <div className="space-y-3">
            {banners.map((b) => (
              <div key={b.id} className="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div className="flex items-center gap-3">
                  <div className={`w-24 h-12 rounded-xl bg-gradient-to-r ${b.bgGradient} flex items-center justify-center text-white font-mono text-[10px] shrink-0 font-bold p-1 text-center`}>
                    {b.position.toUpperCase()}
                  </div>
                  <div>
                    <h4 className="font-bold text-slate-900">{b.title}</h4>
                    <p className="text-slate-500 text-[11px]">{b.subtitle}</p>
                    <span className="text-[10px] font-mono text-[#0F4C81]">Position: {b.position} · Order: {b.displayOrder}</span>
                  </div>
                </div>

                <div className="flex items-center gap-2 self-end sm:self-center">
                  <button
                    onClick={() => toggleBanner(b.id)}
                    className={`px-3 py-1 rounded-full text-[10px] font-mono font-bold ${b.isActive ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600'}`}
                  >
                    {b.isActive ? 'ACTIVE' : 'DISABLED'}
                  </button>
                  <button
                    onClick={() => deleteBanner(b.id)}
                    className="p-1.5 text-slate-400 hover:text-rose-600"
                  >
                    <Trash2 className="w-4 h-4" />
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* SECTION 3: STORE SETTINGS */}
      {activeSection === 'settings' && (
        <div className="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
              <h2 className="text-base font-bold text-slate-900">স্টোর সেটিংস (Livewire/Admin/SettingsManager.php)</h2>
              <p className="text-xs text-slate-500">জেনারেল ব্র্যান্ডিং, ডেলিভারি চার্জ এবং পেমেন্ট সুইচ।</p>
            </div>
            <button
              onClick={handleSaveSettings}
              className="px-4 py-2 bg-[#0F4C81] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs"
            >
              <Save className="w-4 h-4" />
              <span>Save Settings</span>
            </button>
          </div>

          {settingsFlash && (
            <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
              <CheckCircle2 className="w-4 h-4 text-[#28A745] shrink-0" />
              <span>{settingsFlash}</span>
            </div>
          )}

          <div className="space-y-4 text-xs">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">স্টোর নাম</label>
                <input
                  type="text"
                  value={storeName}
                  onChange={(e) => setStoreName(e.target.value)}
                  className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold"
                />
              </div>

              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">অর্ডার প্রিফিক্স (Prefix)</label>
                <input
                  type="text"
                  value={orderPrefix}
                  onChange={(e) => setOrderPrefix(e.target.value.toUpperCase())}
                  className="w-full p-2.5 font-mono uppercase bg-slate-50 border border-slate-200 rounded-xl text-xs"
                />
              </div>

              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">হেল্পলাইন ফোন</label>
                <input
                  type="tel"
                  value={contactPhone}
                  onChange={(e) => setContactPhone(e.target.value)}
                  className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs"
                />
              </div>

              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">সাপোর্ট ইমেইল</label>
                <input
                  type="email"
                  value={supportEmail}
                  onChange={(e) => setSupportEmail(e.target.value)}
                  className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs"
                />
              </div>
            </div>

            <div className="p-4 bg-slate-50 rounded-2xl space-y-3">
              <span className="font-bold text-slate-800 block">বাংলাদেশ ডেলিভারি ফি কনফিগারেশন</span>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-[10px] text-slate-500 mb-1">ঢাকা সিটির ভিতরে (BDT)</label>
                  <input
                    type="number"
                    value={dhakaCharge}
                    onChange={(e) => setDhakaCharge(parseInt(e.target.value) || 0)}
                    className="w-full p-2 font-mono bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>
                <div>
                  <label className="block text-[10px] text-slate-500 mb-1">ঢাকার বাইরে সারা বাংলাদেশ (BDT)</label>
                  <input
                    type="number"
                    value={outsideCharge}
                    onChange={(e) => setOutsideCharge(parseInt(e.target.value) || 0)}
                    className="w-full p-2 font-mono bg-white border border-slate-200 rounded-lg text-xs"
                  />
                </div>
              </div>
            </div>

            <div className="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
              <div>
                <span className="font-bold text-slate-800 block">ক্যাশ অন ডেলিভারি (COD) পেমেন্ট চালু রাখুন</span>
                <span className="text-[11px] text-slate-500">সারা বাংলাদেশে পণ্য দেখে পেমেন্টের সুযোগ দিন।</span>
              </div>
              <input
                type="checkbox"
                checked={codEnabled}
                onChange={(e) => setCodEnabled(e.target.checked)}
                className="w-5 h-5 text-[#0F4C81] rounded border-slate-300"
              />
            </div>
          </div>
        </div>
      )}

      {/* SECTION 4: STOREFRONT HOME PREVIEW */}
      {activeSection === 'home' && (
        <div className="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
              <h2 className="text-base font-bold text-slate-900">হোমপেজ লাইভ প্রিভিউ (resources/views/frontend/home.blade.php)</h2>
              <p className="text-xs text-slate-500">ডায়নামিক হিরো স্লাইডার, ক্যাটাগরি গ্রিড এবং ট্রাস্ট সেকশন।</p>
            </div>
            <span className="text-xs font-mono font-bold text-[#FF6B35]">Storefront Live</span>
          </div>

          {/* Dynamic Hero Slide Preview */}
          <div className="relative rounded-2xl overflow-hidden aspect-[16/7] bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 p-6 sm:p-10 text-white flex items-center justify-between">
            <div className="max-w-md space-y-2 z-10">
              <span className="text-[10px] font-mono uppercase bg-orange-500/20 text-[#FF6B35] px-2 py-0.5 rounded font-bold">
                এক্সক্লুসিভ অফার
              </span>
              <h3 className="text-lg sm:text-2xl font-extrabold tracking-tight">
                {banners[0]?.title || 'ধামাকা অফার: সকল গ্যাজেটে ৪০% পর্যন্ত ছাড়!'}
              </h3>
              <p className="text-xs text-slate-300">
                {banners[0]?.subtitle || 'ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে দ্রুত ডেলিভারি'}
              </p>
              <div className="pt-2">
                <button className="px-4 py-2 bg-[#FF6B35] text-white text-xs font-bold rounded-xl shadow-md">
                  এখনই কিনুন (Shop Now)
                </button>
              </div>
            </div>
            <Sparkles className="w-20 h-20 text-blue-300 opacity-20" />
          </div>

          {/* Category Chips */}
          <div className="space-y-2">
            <span className="text-xs font-bold text-slate-800">পপুলার ক্যাটাগরি</span>
            <div className="grid grid-cols-4 sm:grid-cols-6 gap-2">
              {['TWS Earbuds', 'Powerbanks', 'Smart Watches', 'Cables & Adapters', 'Mobile Stands', 'Accessories'].map((c, i) => (
                <div key={i} className="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center text-xs font-semibold text-slate-800 hover:border-[#0F4C81] transition-colors cursor-pointer">
                  {c}
                </div>
              ))}
            </div>
          </div>

          {/* 4 Trust Pillars */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
            <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center space-y-1">
              <span className="text-base">🚚</span>
              <h5 className="text-xs font-bold text-slate-900">দ্রুত ডেলিভারি</h5>
              <p className="text-[10px] text-slate-500">২-৩ দিনে হোম ডেলিভারি</p>
            </div>
            <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center space-y-1">
              <span className="text-base">💵</span>
              <h5 className="text-xs font-bold text-slate-900">ক্যাশ অন ডেলিভারি</h5>
              <p className="text-[10px] text-slate-500">পণ্য দেখে নিয়ে মূল্য পরিশোধ</p>
            </div>
            <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center space-y-1">
              <span className="text-base">🔄</span>
              <h5 className="text-xs font-bold text-slate-900">৭ দিনের গ্যারান্টি</h5>
              <p className="text-[10px] text-slate-500">সহজ ফ্রি রিপ্লেসমেন্ট</p>
            </div>
            <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl text-center space-y-1">
              <span className="text-base">🛡️</span>
              <h5 className="text-xs font-bold text-slate-900">১০০% অথেনটিক</h5>
              <p className="text-[10px] text-slate-500">জেনুইন মানের নিশ্চয়তা</p>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
