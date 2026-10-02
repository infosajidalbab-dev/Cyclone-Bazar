import React, { useState } from 'react';
import { SchemaInspector } from './components/SchemaInspector';
import { CodeViewer } from './components/CodeViewer';
import { FrontendPdpPreview } from './components/FrontendPdpPreview';
import { AdminProductManagerPreview } from './components/AdminProductManagerPreview';
import { AdminOrderManagerPreview } from './components/AdminOrderManagerPreview';
import { Phase3CartCheckoutPreview } from './components/Phase3CartCheckoutPreview';
import { Phase4AdminDashboardPreview } from './components/Phase4AdminDashboardPreview';
import { StorefrontHomePreview } from './components/StorefrontHomePreview';
import { Database, FileCode, Smartphone, Package, CheckCircle2, ShoppingBag, Layers, Truck, CreditCard, LayoutDashboard, Store } from 'lucide-react';

export default function App() {
  const [activeView, setActiveView] = useState<'storefront_home' | 'admin_dashboard' | 'checkout_phase3' | 'pdp' | 'admin_products' | 'admin_orders' | 'schema' | 'code'>('storefront_home');

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900 flex flex-col font-sans">
      {/* Top Bar Contract (Strict 3-zone layout) */}
      <header className="sticky top-0 z-40 bg-white border-b border-slate-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          {/* Zone 1: Single text element wordmark */}
          <div className="flex items-center gap-3">
            <a href="/" className="text-xl font-bold tracking-tight text-[#0F172A]">
              Cyclone<span className="text-[#DC2626]">Mart</span>
            </a>
            <span className="hidden sm:inline text-xs text-slate-400 font-mono">v1.0-bd</span>
          </div>

          {/* Zone 2: Navigation Links (Single-line controls) */}
          <nav className="hidden lg:flex items-center gap-3.5 text-xs sm:text-sm font-medium text-slate-600">
            <button
              onClick={() => setActiveView('storefront_home')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'storefront_home' ? 'text-[#DC2626] font-extrabold' : 'hover:text-slate-900'
              }`}
            >
              <Store className="w-4 h-4 text-[#DC2626]" />
              <span>Storefront (Live)</span>
            </button>

            <button
              onClick={() => setActiveView('admin_dashboard')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'admin_dashboard' ? 'text-[#0F4C81] font-bold' : 'hover:text-slate-900'
              }`}
            >
              <LayoutDashboard className="w-4 h-4 text-[#FF6B35]" />
              <span>Dashboard</span>
            </button>

            <button
              onClick={() => setActiveView('checkout_phase3')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'checkout_phase3' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <CreditCard className="w-4 h-4" />
              <span>Checkout & Cart</span>
            </button>

            <button
              onClick={() => setActiveView('pdp')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'pdp' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <Smartphone className="w-4 h-4" />
              <span>PDP</span>
            </button>

            <button
              onClick={() => setActiveView('admin_products')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'admin_products' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <Package className="w-4 h-4" />
              <span>Products</span>
            </button>

            <button
              onClick={() => setActiveView('admin_orders')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'admin_orders' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <Truck className="w-4 h-4" />
              <span>Orders</span>
            </button>

            <button
              onClick={() => setActiveView('schema')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'schema' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <Database className="w-4 h-4" />
              <span>Schema</span>
            </button>

            <button
              onClick={() => setActiveView('code')}
              className={`transition-colors flex items-center gap-1.5 ${
                activeView === 'code' ? 'text-[#0F4C81] font-semibold' : 'hover:text-slate-900'
              }`}
            >
              <FileCode className="w-4 h-4" />
              <span>Code</span>
            </button>
          </nav>

          {/* Zone 3: Primary Action */}
          <div className="flex items-center gap-2">
            <div className="flex items-center gap-1.5 text-xs text-red-700 bg-red-50 px-3 py-1.5 rounded-lg border border-red-200">
              <span className="w-2 h-2 rounded-full bg-[#DC2626] animate-ping"></span>
              <span className="font-bold text-[#DC2626]">Storefront Live</span>
            </div>
          </div>
        </div>

        {/* Mobile Sub-Navigation Bar */}
        <div className="lg:hidden flex border-t border-slate-100 bg-white overflow-x-auto py-1 px-2 gap-1">
          <button
            onClick={() => setActiveView('storefront_home')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-bold ${
              activeView === 'storefront_home' ? 'bg-red-50 text-[#DC2626]' : 'text-slate-600'
            }`}
          >
            Storefront
          </button>
          <button
            onClick={() => setActiveView('admin_dashboard')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'admin_dashboard' ? 'bg-blue-50 text-[#0F4C81] font-bold' : 'text-slate-600'
            }`}
          >
            Dashboard
          </button>
          <button
            onClick={() => setActiveView('checkout_phase3')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'checkout_phase3' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            Checkout & Cart
          </button>
          <button
            onClick={() => setActiveView('pdp')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'pdp' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            PDP
          </button>
          <button
            onClick={() => setActiveView('admin_products')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'admin_products' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            Products
          </button>
          <button
            onClick={() => setActiveView('admin_orders')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'admin_orders' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            Orders
          </button>
          <button
            onClick={() => setActiveView('schema')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'schema' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            Schema
          </button>
          <button
            onClick={() => setActiveView('code')}
            className={`px-3 py-1.5 text-xs rounded-md whitespace-nowrap font-medium ${
              activeView === 'code' ? 'bg-blue-50 text-[#0F4C81]' : 'text-slate-600'
            }`}
          >
            Code
          </button>
        </div>
      </header>

      {/* Main Container */}
      <main className="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        {/* Editorial Subheader */}
        <div className="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-xs">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div className="space-y-1.5 max-w-3xl">
              <div className="flex items-center gap-2 text-xs text-slate-500 font-medium">
                <span className="text-[#0F172A] font-bold">Cyclone Mart</span>
                <span aria-hidden="true">·</span>
                <span className="text-[#DC2626] font-semibold">Mobile-First Storefront</span>
                <span aria-hidden="true">·</span>
                <span>Laravel 11 & Tailwind CSS</span>
                <span aria-hidden="true">·</span>
                <span>Bangladesh Market</span>
              </div>

              <h1 className="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 leading-tight">
                {activeView === 'storefront_home'
                  ? 'Cyclone Mart E-Commerce Home Page Layout (সাইক্লোন মার্ট)'
                  : 'Cyclone Mart Dropshipping Platform Architecture'}
              </h1>
              <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                Featuring the complete Dark Navy Blue (<code className="font-mono text-[#0F172A] bg-slate-100 px-1 py-0.5 rounded text-xs">#0F172A</code>) and Crimson Red (<code className="font-mono text-[#DC2626] bg-red-50 px-1 py-0.5 rounded text-xs">#DC2626</code>) palette, hotline announcement bar, category sidebar with hero banner, and responsive 2-column mobile to 6-column desktop product grid with dual <strong className="text-slate-900 font-semibold">'কার্ট'</strong> & <strong className="text-[#DC2626] font-semibold">'অর্ডার করুন'</strong> buttons.
              </p>
            </div>

            {/* Quick Metrics */}
            <div className="grid grid-cols-2 gap-3 shrink-0">
              <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                <span className="text-[10px] text-slate-500 block uppercase font-mono tracking-wider">Mobile Grid</span>
                <span className="text-lg font-bold font-mono text-[#DC2626] tabular-nums">2 Columns</span>
                <span className="text-[10px] text-slate-400 block">320px–430px Target</span>
              </div>
              <div className="p-3 bg-slate-50 border border-slate-100 rounded-xl">
                <span className="text-[10px] text-slate-500 block uppercase font-mono tracking-wider">Desktop Grid</span>
                <span className="text-lg font-bold font-mono text-[#0F172A] tabular-nums">4 to 6 Cols</span>
                <span className="text-[10px] text-slate-400 block">Category Sidebar</span>
              </div>
            </div>
          </div>
        </div>

        {/* View Component Display */}
        <div>
          {activeView === 'storefront_home' && <StorefrontHomePreview />}
          {activeView === 'admin_dashboard' && <Phase4AdminDashboardPreview />}
          {activeView === 'checkout_phase3' && <Phase3CartCheckoutPreview />}
          {activeView === 'pdp' && <FrontendPdpPreview />}
          {activeView === 'admin_products' && <AdminProductManagerPreview />}
          {activeView === 'admin_orders' && <AdminOrderManagerPreview />}
          {activeView === 'schema' && <SchemaInspector />}
          {activeView === 'code' && <CodeViewer />}
        </div>
      </main>

      {/* Footer */}
      <footer className="mt-auto border-t border-slate-200 bg-white py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
          <div className="flex items-center gap-2">
            <span className="font-semibold text-slate-700">Cyclone Mart</span>
            <span>— Mobile-First Dropshipping E-Commerce Architecture (Bangladesh)</span>
          </div>
          <div className="flex items-center gap-4 text-slate-500 font-mono text-[11px]">
            <span>Primary: #0F172A</span>
            <span>Accent/CTA: #DC2626</span>
            <span>Canvas: #F8FAFC</span>
          </div>
        </div>
      </footer>
    </div>
  );
}
