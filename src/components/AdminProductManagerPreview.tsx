import React, { useState } from 'react';
import { Package, Plus, Trash2, CheckCircle2, DollarSign, Layers, Save, RefreshCw, Eye } from 'lucide-react';

interface MockVariant {
  id: number;
  sku: string;
  title: string;
  attrName: string;
  attrValue: string;
  costPrice: number;
  sellingPrice: number;
  stock: number;
}

export const AdminProductManagerPreview: React.FC = () => {
  const [name, setName] = useState('Cyclone MagPulse Powerbank 20000mAh');
  const [sku, setSku] = useState('CM-PWR-20K-01');
  const [supplierId, setSupplierId] = useState('1');
  const [categoryId, setCategoryId] = useState('2');
  const [costPrice, setCostPrice] = useState<number>(1450);
  const [sellingPrice, setSellingPrice] = useState<number>(2250);
  const [comparePrice, setComparePrice] = useState<number>(2750);
  const [stockQuantity, setStockQuantity] = useState<number>(25);
  const [hasVariants, setHasVariants] = useState<boolean>(true);
  const [isActive, setIsActive] = useState<boolean>(true);
  const [isFeatured, setIsFeatured] = useState<boolean>(true);
  const [flashMsg, setFlashMsg] = useState<string | null>(null);

  const [variants, setVariants] = useState<MockVariant[]>([
    {
      id: 1,
      sku: 'CM-PWR-20K-BLK',
      title: 'Graphite Black (22.5W Fast Charge)',
      attrName: 'Color',
      attrValue: 'Graphite Black',
      costPrice: 1450,
      sellingPrice: 2250,
      stock: 15,
    },
    {
      id: 2,
      sku: 'CM-PWR-20K-WHT',
      title: 'Alpine White (22.5W Fast Charge)',
      attrName: 'Color',
      attrValue: 'Alpine White',
      costPrice: 1450,
      sellingPrice: 2250,
      stock: 10,
    }
  ]);

  const netMargin = sellingPrice - costPrice;
  const marginPercentage = sellingPrice > 0 ? Math.round((netMargin / sellingPrice) * 100) : 0;

  const addVariant = () => {
    setHasVariants(true);
    const newId = variants.length + 1;
    setVariants([
      ...variants,
      {
        id: Date.now(),
        sku: `${sku}-V${newId}`,
        title: `Edition ${newId}`,
        attrName: 'Edition',
        attrValue: `Option ${newId}`,
        costPrice: costPrice,
        sellingPrice: sellingPrice,
        stock: 10,
      }
    ]);
  };

  const removeVariant = (id: number) => {
    const updated = variants.filter(v => v.id !== id);
    setVariants(updated);
    if (updated.length === 0) setHasVariants(false);
  };

  const handleSave = () => {
    setFlashMsg('Product and all variant inventory successfully committed into MySQL with DB::transaction()!');
    setTimeout(() => setFlashMsg(null), 3500);
  };

  return (
    <div className="space-y-6">
      {/* Intro info bar */}
      <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-semibold text-slate-900 block">Livewire 3 Admin Product Management</span>
          <span className="text-slate-500">
            Interactive testbed for <code className="text-[#0F4C81] font-mono">ProductForm.php</code> and <code className="text-[#0F4C81] font-mono">product-form.blade.php</code>.
          </span>
        </div>
        <button
          onClick={handleSave}
          className="px-4 py-2 bg-[#0F4C81] hover:bg-[#0A355C] text-white font-bold rounded-lg text-xs flex items-center gap-1.5 shadow-sm min-h-[44px]"
        >
          <Save className="w-4 h-4" />
          <span>Save Product Form</span>
        </button>
      </div>

      {flashMsg && (
        <div className="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
          <CheckCircle2 className="w-4 h-4 text-[#28A745] shrink-0" />
          <span>{flashMsg}</span>
        </div>
      )}

      {/* Main Admin Form Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Left Column (Product Details & Variants) */}
        <div className="lg:col-span-8 space-y-5">
          <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <h3 className="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
              Catalog Master Details
            </h3>

            <div>
              <label className="block text-xs font-semibold text-slate-700 mb-1">Product Title *</label>
              <input
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">Master SKU *</label>
                <input
                  type="text"
                  value={sku}
                  onChange={(e) => setSku(e.target.value)}
                  className="w-full p-2.5 font-mono uppercase bg-slate-50 border border-slate-200 rounded-lg"
                />
              </div>

              <div>
                <label className="block text-[11px] font-semibold text-slate-700 mb-1">Assigned Dropship Supplier</label>
                <select
                  value={supplierId}
                  onChange={(e) => setSupplierId(e.target.value)}
                  className="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                >
                  <option value="1">Dhaka Electronics Hub (Rahman Traders)</option>
                  <option value="2">Chittagong Smart Importers Ltd.</option>
                  <option value="3">Gazipur Direct Gadget Warehouse</option>
                </select>
              </div>
            </div>
          </div>

          {/* Variants Section */}
          <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <div className="flex items-center justify-between border-b border-slate-100 pb-2">
              <div>
                <h3 className="text-sm font-bold text-slate-900">Multi-Variant Configuration</h3>
                <span className="text-[11px] text-slate-500">Each variant tracks independent SKU, BDT margins, and inventory.</span>
              </div>
              <button
                onClick={addVariant}
                className="px-3 py-1.5 text-xs font-semibold text-[#0F4C81] bg-blue-50 hover:bg-blue-100 rounded-lg min-h-[44px] flex items-center gap-1.5 transition-colors"
              >
                <Plus className="w-3.5 h-3.5" />
                <span>Add Variant</span>
              </button>
            </div>

            <div className="space-y-3">
              {variants.map((v, i) => (
                <div key={v.id} className="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                  <div className="flex items-center justify-between">
                    <span className="text-xs font-mono font-bold text-slate-800">Variant #{i + 1} ({v.sku})</span>
                    <button onClick={() => removeVariant(v.id)} className="text-xs text-rose-600 hover:text-rose-800">
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
                    <div>
                      <label className="block text-[11px] text-slate-500 mb-0.5">Title / Option Name</label>
                      <input
                        type="text"
                        value={v.title}
                        onChange={(e) => {
                          const copy = [...variants];
                          copy[i].title = e.target.value;
                          setVariants(copy);
                        }}
                        className="w-full p-2 bg-white border border-slate-200 rounded text-xs"
                      />
                    </div>
                    <div>
                      <label className="block text-[11px] text-slate-500 mb-0.5">Wholesale Cost (৳)</label>
                      <input
                        type="number"
                        value={v.costPrice}
                        onChange={(e) => {
                          const copy = [...variants];
                          copy[i].costPrice = parseFloat(e.target.value) || 0;
                          setVariants(copy);
                        }}
                        className="w-full p-2 font-mono bg-white border border-slate-200 rounded text-xs"
                      />
                    </div>
                    <div>
                      <label className="block text-[11px] text-slate-500 mb-0.5">Selling Price (৳)</label>
                      <input
                        type="number"
                        value={v.sellingPrice}
                        onChange={(e) => {
                          const copy = [...variants];
                          copy[i].sellingPrice = parseFloat(e.target.value) || 0;
                          setVariants(copy);
                        }}
                        className="w-full p-2 font-mono bg-white border border-slate-200 rounded text-xs"
                      />
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Right Column (Margins & Organization) */}
        <div className="lg:col-span-4 space-y-5">
          {/* Live Dropshipping Profit Margin Tracker */}
          <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
            <h3 className="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
              Profit & Margins (BDT)
            </h3>

            <div>
              <label className="block text-xs font-semibold text-slate-700 mb-1">Wholesale Cost (৳)</label>
              <input
                type="number"
                value={costPrice}
                onChange={(e) => setCostPrice(parseFloat(e.target.value) || 0)}
                className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-lg text-xs"
              />
              <span className="text-[10px] text-slate-400">Supplier invoice cost</span>
            </div>

            <div>
              <label className="block text-xs font-semibold text-slate-700 mb-1">Retail Selling Price (৳)</label>
              <input
                type="number"
                value={sellingPrice}
                onChange={(e) => setSellingPrice(parseFloat(e.target.value) || 0)}
                className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-[#0F4C81]"
              />
            </div>

            <div>
              <label className="block text-xs font-semibold text-slate-700 mb-1">Crossed-out Price (৳)</label>
              <input
                type="number"
                value={comparePrice}
                onChange={(e) => setComparePrice(parseFloat(e.target.value) || 0)}
                className="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-lg text-xs"
              />
            </div>

            {/* Real-Time Dropship Margin Calculation Alert */}
            <div className="p-3.5 bg-emerald-50/90 border border-emerald-200 rounded-xl space-y-1.5 font-mono text-xs">
              <div className="flex justify-between items-center text-emerald-950 font-bold">
                <span>Net Margin / Unit:</span>
                <span className="text-sm text-[#28A745]">৳{netMargin.toFixed(2)}</span>
              </div>
              <div className="flex justify-between items-center text-emerald-800 text-[11px]">
                <span>Profit Percentage:</span>
                <span className="font-semibold">{marginPercentage}%</span>
              </div>
            </div>
          </div>

          {/* Visibility Controls */}
          <div className="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-3 text-xs">
            <h3 className="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Storefront Flags</h3>

            <label className="flex items-center gap-3 cursor-pointer min-h-[44px]">
              <input
                type="checkbox"
                checked={isActive}
                onChange={(e) => setIsActive(e.target.checked)}
                className="w-4 h-4 text-[#0F4C81] rounded border-slate-300"
              />
              <span className="font-medium text-slate-700">Active in Storefront Catalog</span>
            </label>

            <label className="flex items-center gap-3 cursor-pointer min-h-[44px]">
              <input
                type="checkbox"
                checked={isFeatured}
                onChange={(e) => setIsFeatured(e.target.checked)}
                className="w-4 h-4 text-[#FF6B35] rounded border-slate-300"
              />
              <span className="font-medium text-slate-700">Featured Product on Homepage</span>
            </label>
          </div>
        </div>
      </div>
    </div>
  );
};
