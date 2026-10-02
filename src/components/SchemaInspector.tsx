import React, { useState } from 'react';
import { Database, Key, Search, ArrowRight, ShieldCheck, Layers, FileCode, CheckCircle2 } from 'lucide-react';

interface TableDefinition {
  name: string;
  category: 'Core & Auth' | 'Catalog & Dropship' | 'Orders & Logistics' | 'Payments';
  description: string;
  columns: { name: string; type: string; key?: 'PK' | 'FK' | 'IDX' | 'UNI'; notes?: string }[];
  compositeIndexes: { name: string; columns: string[]; purpose: string; isStrictRequirement?: boolean }[];
  foreignKeys: { column: string; references: string; onDelete: string }[];
}

const SCHEMA_TABLES: TableDefinition[] = [
  {
    name: 'orders',
    category: 'Orders & Logistics',
    description: 'Master order transactions with dedicated tracking and fulfillment metadata.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'order_number', type: 'VARCHAR(32)', key: 'UNI', notes: 'CM-20261002-XXXX' },
      { name: 'customer_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'customer_phone', type: 'VARCHAR(15)', key: 'IDX', notes: '01XXXXXXXXX snapshot' },
      { name: 'shipping_address_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'delivery_zone_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'subtotal', type: 'DECIMAL(12,2)', notes: 'Items subtotal in BDT' },
      { name: 'delivery_charge', type: 'DECIMAL(8,2)', notes: 'Zone shipping charge' },
      { name: 'discount_amount', type: 'DECIMAL(8,2)' },
      { name: 'total_amount', type: 'DECIMAL(12,2)', notes: 'Payable BDT amount' },
      { name: 'payment_method', type: 'ENUM(cod, bkash, nagad, sslcommerz)' },
      { name: 'payment_status', type: 'ENUM(pending, paid, partially_paid, failed, refunded)' },
      { name: 'order_status', type: 'ENUM(pending, confirmed, processing, ...)' },
      { name: 'courier_name', type: 'VARCHAR(50)', notes: 'Steadfast / Pathao / RedX' },
      { name: 'courier_tracking_code', type: 'VARCHAR(100)', key: 'IDX' },
      { name: 'confirmed_at', type: 'TIMESTAMP NULL' },
      { name: 'delivered_at', type: 'TIMESTAMP NULL' },
      { name: 'created_at / updated_at', type: 'TIMESTAMP' },
      { name: 'deleted_at', type: 'TIMESTAMP NULL' }
    ],
    compositeIndexes: [
      {
        name: 'idx_orders_tracking_number_phone',
        columns: ['order_number', 'customer_phone'],
        purpose: 'Lightning-fast guest & customer order tracking lookups without table scans',
        isStrictRequirement: true
      },
      {
        name: 'idx_orders_customer_history',
        columns: ['customer_id', 'created_at'],
        purpose: 'Chronological order history retrieval for repeat buyers'
      },
      {
        name: 'idx_orders_status_created',
        columns: ['order_status', 'created_at'],
        purpose: 'Real-time fulfillment queue filtering for operations staff'
      }
    ],
    foreignKeys: [
      { column: 'customer_id', references: 'customers.id', onDelete: 'RESTRICT' },
      { column: 'shipping_address_id', references: 'addresses.id', onDelete: 'RESTRICT' },
      { column: 'delivery_zone_id', references: 'delivery_zones.id', onDelete: 'RESTRICT' }
    ]
  },
  {
    name: 'products',
    category: 'Catalog & Dropship',
    description: 'Core product catalog with BDT margins, stock counters, and full-text search capability.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'supplier_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'category_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'name', type: 'VARCHAR(255)' },
      { name: 'slug', type: 'VARCHAR(255)', key: 'UNI' },
      { name: 'sku', type: 'VARCHAR(64)', key: 'UNI' },
      { name: 'cost_price', type: 'DECIMAL(10,2)', notes: 'Wholesale BDT cost' },
      { name: 'selling_price', type: 'DECIMAL(10,2)', notes: 'Public listing price' },
      { name: 'compare_at_price', type: 'DECIMAL(10,2) NULL' },
      { name: 'stock_quantity', type: 'INT', notes: 'Tracked with lockForUpdate()' },
      { name: 'low_stock_threshold', type: 'SMALLINT UNSIGNED', notes: 'Default: 5' },
      { name: 'is_active', type: 'BOOLEAN', notes: 'Default: true' },
      { name: 'is_featured', type: 'BOOLEAN', notes: 'Homepage feature flag' },
      { name: 'has_variants', type: 'BOOLEAN' }
    ],
    compositeIndexes: [
      {
        name: 'idx_products_cat_active_price',
        columns: ['category_id', 'is_active', 'selling_price'],
        purpose: 'Instant category browsing with price filtering and sorting',
        isStrictRequirement: true
      },
      {
        name: 'idx_products_active_featured',
        columns: ['is_active', 'is_featured', 'created_at'],
        purpose: 'Homepage hero & top deals feed query optimization'
      },
      {
        name: 'idx_products_name_sku_fulltext',
        columns: ['name', 'sku'],
        purpose: 'MySQL 8 FULLTEXT search index for storefront search bar'
      }
    ],
    foreignKeys: [
      { column: 'supplier_id', references: 'suppliers.id', onDelete: 'RESTRICT' },
      { column: 'category_id', references: 'categories.id', onDelete: 'RESTRICT' }
    ]
  },
  {
    name: 'addresses',
    category: 'Orders & Logistics',
    description: '4-tier Bangladesh geographical address records linked to regional delivery zones.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'customer_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'delivery_zone_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'recipient_name', type: 'VARCHAR(255)' },
      { name: 'recipient_phone', type: 'VARCHAR(15)', key: 'IDX' },
      { name: 'division', type: 'VARCHAR(50)', notes: 'Dhaka, Chattogram, etc.' },
      { name: 'district', type: 'VARCHAR(80)', notes: 'Dhaka, Gazipur, Bogura, etc.' },
      { name: 'upazila', type: 'VARCHAR(80)', notes: 'Mirpur, Savar, Tongi, etc.' },
      { name: 'area', type: 'VARCHAR(100) NULL', notes: 'Neighborhood / Ward' },
      { name: 'street_address', type: 'TEXT', notes: 'House, Road, Block' },
      { name: 'landmark', type: 'VARCHAR(150) NULL', notes: 'Rider landmark' },
      { name: 'is_default_shipping', type: 'BOOLEAN' }
    ],
    compositeIndexes: [
      {
        name: 'idx_addresses_bd_hierarchy',
        columns: ['division', 'district', 'upazila'],
        purpose: 'Regional courier delivery sorting and hub routing',
        isStrictRequirement: true
      },
      {
        name: 'idx_addresses_customer_default',
        columns: ['customer_id', 'is_default_shipping'],
        purpose: '1-click checkout default address retrieval'
      }
    ],
    foreignKeys: [
      { column: 'customer_id', references: 'customers.id', onDelete: 'CASCADE' },
      { column: 'delivery_zone_id', references: 'delivery_zones.id', onDelete: 'RESTRICT' }
    ]
  },
  {
    name: 'customers',
    category: 'Core & Auth',
    description: 'Customer profiles supporting both guest checkout and registered members.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'user_id', type: 'BIGINT UNSIGNED NULL', key: 'FK' },
      { name: 'name', type: 'VARCHAR(255)' },
      { name: 'phone', type: 'VARCHAR(15)', key: 'UNI', notes: 'Standard 01XXXXXXXXX format' },
      { name: 'email', type: 'VARCHAR(255) NULL' },
      { name: 'status', type: 'ENUM(active, flagged, blocked)' },
      { name: 'total_orders', type: 'INT UNSIGNED' },
      { name: 'total_spent', type: 'DECIMAL(12,2)' }
    ],
    compositeIndexes: [
      {
        name: 'idx_customers_status_created',
        columns: ['status', 'created_at'],
        purpose: 'Fraud screening and high-value customer analytics'
      }
    ],
    foreignKeys: [
      { column: 'user_id', references: 'users.id', onDelete: 'SET NULL' }
    ]
  },
  {
    name: 'delivery_zones',
    category: 'Orders & Logistics',
    description: 'Bangladesh shipping rate and SLA definitions (Inside Dhaka: ৳60, Suburbs: ৳100, Outside Dhaka: ৳130).',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'name', type: 'VARCHAR(255)' },
      { name: 'code', type: 'VARCHAR(50)', key: 'UNI', notes: 'DHAKA_INSIDE, DHAKA_SUBURB, OUTSIDE_DHAKA' },
      { name: 'base_charge', type: 'DECIMAL(8,2)', notes: '৳60, ৳100, or ৳130' },
      { name: 'cod_available', type: 'BOOLEAN', notes: 'Default: true' },
      { name: 'estimated_days_min / max', type: 'TINYINT UNSIGNED' }
    ],
    compositeIndexes: [
      {
        name: 'idx_delivery_zones_active_charge',
        columns: ['is_active', 'base_charge'],
        purpose: 'Checkout zone shipping fee determination'
      }
    ],
    foreignKeys: []
  },
  {
    name: 'payments',
    category: 'Payments',
    description: 'Standardized payment records supporting Cash on Delivery rider hand-over and mobile wallets.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'order_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'payment_gateway', type: 'VARCHAR(50)', notes: 'cod / bkash / nagad' },
      { name: 'transaction_id', type: 'VARCHAR(120)', key: 'UNI', notes: 'COD-CM-XXXX or MFS TRX' },
      { name: 'amount', type: 'DECIMAL(12,2)', notes: 'Total BDT' },
      { name: 'currency', type: 'CHAR(3)', notes: 'BDT' },
      { name: 'status', type: 'ENUM(initiated, pending, completed, failed, refunded)' },
      { name: 'gateway_response', type: 'JSON NULL', notes: 'Rider name, delivery timestamp' },
      { name: 'verified_at', type: 'TIMESTAMP NULL' }
    ],
    compositeIndexes: [
      {
        name: 'idx_payments_order_status',
        columns: ['order_id', 'status'],
        purpose: 'Order settlement audit and reconciliation'
      }
    ],
    foreignKeys: [
      { column: 'order_id', references: 'orders.id', onDelete: 'CASCADE' }
    ]
  },
  {
    name: 'order_items',
    category: 'Orders & Logistics',
    description: 'Snapshot of purchased items with wholesale dropship cost and selling price.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'order_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'product_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'supplier_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'product_name', type: 'VARCHAR(255)' },
      { name: 'sku', type: 'VARCHAR(64)', key: 'IDX' },
      { name: 'unit_cost', type: 'DECIMAL(10,2)', notes: 'Supplier wholesale price' },
      { name: 'unit_price', type: 'DECIMAL(10,2)', notes: 'Retail customer price' },
      { name: 'quantity', type: 'INT UNSIGNED' },
      { name: 'subtotal', type: 'DECIMAL(12,2)' },
      { name: 'dropship_status', type: 'ENUM(...)', notes: 'pending, dispatched, received, fulfilled' }
    ],
    compositeIndexes: [
      {
        name: 'idx_order_items_supplier_routing',
        columns: ['order_id', 'supplier_id'],
        purpose: 'Automated split-shipment dispatch notices to vendor warehouses'
      }
    ],
    foreignKeys: [
      { column: 'order_id', references: 'orders.id', onDelete: 'CASCADE' },
      { column: 'product_id', references: 'products.id', onDelete: 'RESTRICT' },
      { column: 'supplier_id', references: 'suppliers.id', onDelete: 'RESTRICT' }
    ]
  },
  {
    name: 'order_status_history',
    category: 'Orders & Logistics',
    description: 'Immutable lifecycle audit trail of every status transition and customer SMS notification.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'order_id', type: 'BIGINT UNSIGNED', key: 'FK' },
      { name: 'user_id', type: 'BIGINT UNSIGNED NULL', key: 'FK' },
      { name: 'from_status', type: 'VARCHAR(50) NULL' },
      { name: 'to_status', type: 'VARCHAR(50)' },
      { name: 'comment', type: 'TEXT NULL' },
      { name: 'notified_customer', type: 'BOOLEAN' },
      { name: 'created_at', type: 'TIMESTAMP' }
    ],
    compositeIndexes: [
      {
        name: 'idx_order_status_history_timeline',
        columns: ['order_id', 'created_at'],
        purpose: 'Customer timeline rendering and rider dispute resolution'
      }
    ],
    foreignKeys: [
      { column: 'order_id', references: 'orders.id', onDelete: 'CASCADE' },
      { column: 'user_id', references: 'users.id', onDelete: 'SET NULL' }
    ]
  },
  {
    name: 'suppliers',
    category: 'Catalog & Dropship',
    description: 'Local Bangladesh suppliers, manufacturing partners, and fulfillment hubs.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'company_name', type: 'VARCHAR(255)' },
      { name: 'contact_person', type: 'VARCHAR(255)' },
      { name: 'phone', type: 'VARCHAR(15)', key: 'UNI' },
      { name: 'commission_rate', type: 'DECIMAL(5,2)' },
      { name: 'lead_time_days', type: 'TINYINT UNSIGNED' },
      { name: 'status', type: 'ENUM(active, suspended, inactive)' }
    ],
    compositeIndexes: [
      {
        name: 'idx_suppliers_status_name',
        columns: ['status', 'company_name'],
        purpose: 'Vendor lookup during batch order fulfillment'
      }
    ],
    foreignKeys: []
  },
  {
    name: 'categories',
    category: 'Catalog & Dropship',
    description: 'Hierarchical catalog categories with parent_id nesting and display priorities.',
    columns: [
      { name: 'id', type: 'BIGINT UNSIGNED', key: 'PK' },
      { name: 'parent_id', type: 'BIGINT UNSIGNED NULL', key: 'FK' },
      { name: 'name', type: 'VARCHAR(255)' },
      { name: 'slug', type: 'VARCHAR(255)', key: 'UNI' },
      { name: 'display_order', type: 'SMALLINT UNSIGNED' },
      { name: 'is_active', type: 'BOOLEAN' }
    ],
    compositeIndexes: [
      {
        name: 'idx_categories_active_order',
        columns: ['is_active', 'display_order'],
        purpose: 'Mobile menu and category bar ordering'
      }
    ],
    foreignKeys: [
      { column: 'parent_id', references: 'categories.id', onDelete: 'SET NULL' }
    ]
  }
];

export const SchemaInspector: React.FC = () => {
  const [selectedTable, setSelectedTable] = useState<TableDefinition>(SCHEMA_TABLES[0]);
  const [filterCategory, setFilterCategory] = useState<string>('All');
  const [searchQuery, setSearchQuery] = useState<string>('');

  const categories = ['All', 'Orders & Logistics', 'Catalog & Dropship', 'Payments', 'Core & Auth'];

  const filteredTables = SCHEMA_TABLES.filter(t => {
    const matchesCat = filterCategory === 'All' || t.category === filterCategory;
    const matchesQuery = t.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                         t.description.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCat && matchesQuery;
  });

  return (
    <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
      {/* Left Table Nav */}
      <div className="lg:col-span-4 space-y-4">
        <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
          <div className="flex items-center justify-between mb-3">
            <div className="flex items-center gap-2">
              <Database className="w-5 h-5 text-[#0F4C81]" />
              <h3 className="font-semibold text-slate-900 text-sm">MySQL 8.0 Schema (13 Tables)</h3>
            </div>
            <span className="text-xs text-slate-500 font-mono">Laravel 11</span>
          </div>

          <div className="relative mb-3">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              placeholder="Search table or column..."
              className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#0F4C81]"
            />
          </div>

          {/* Category Tabs */}
          <div className="flex flex-wrap gap-1 mb-3 pb-2 border-b border-slate-100">
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setFilterCategory(cat)}
                className={`text-[11px] px-2 py-1 rounded-md transition-colors ${
                  filterCategory === cat
                    ? 'bg-[#0F4C81] text-white font-medium'
                    : 'text-slate-600 hover:bg-slate-100'
                }`}
              >
                {cat}
              </button>
            ))}
          </div>

          {/* Tables List */}
          <div className="space-y-1.5 max-h-[500px] overflow-y-auto pr-1">
            {filteredTables.map((tbl) => {
              const isSelected = selectedTable.name === tbl.name;
              const hasStrictReq = tbl.compositeIndexes.some(i => i.isStrictRequirement);

              return (
                <button
                  key={tbl.name}
                  onClick={() => setSelectedTable(tbl)}
                  className={`w-full text-left px-3 py-2.5 rounded-lg text-xs transition-all flex items-center justify-between ${
                    isSelected
                      ? 'bg-blue-50 border border-blue-200 text-[#0F4C81] font-semibold shadow-xs'
                      : 'hover:bg-slate-50 border border-transparent text-slate-700'
                  }`}
                >
                  <div className="flex items-center gap-2 truncate">
                    <span className="font-mono">{tbl.name}</span>
                    {hasStrictReq && (
                      <span className="w-1.5 h-1.5 rounded-full bg-[#FF6B35]" title="Includes prompt-specified composite index" />
                    )}
                  </div>
                  <span className="text-[10px] text-slate-500">{tbl.columns.length} cols</span>
                </button>
              );
            })}
          </div>
        </div>

        {/* Index Highlights Info Card */}
        <div className="bg-amber-50/70 border border-amber-200/80 rounded-xl p-4 text-xs text-amber-900 space-y-2">
          <div className="flex items-center gap-1.5 font-semibold text-amber-950">
            <ShieldCheck className="w-4 h-4 text-amber-700" />
            <span>Strict Specification Compliance</span>
          </div>
          <p className="text-amber-800 leading-relaxed">
            Composite index <code className="bg-amber-100/90 px-1 py-0.5 rounded font-mono text-[11px]">['order_number', 'customer_phone']</code> enables sub-5ms order tracking for guest and returning users without full table scans.
          </p>
        </div>
      </div>

      {/* Right Table Detail */}
      <div className="lg:col-span-8 space-y-5">
        <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-sm space-y-6">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div>
              <div className="flex items-center gap-2.5">
                <h2 className="text-xl font-bold font-mono text-slate-900">{selectedTable.name}</h2>
                <span className="text-xs text-slate-500 font-sans">({selectedTable.category})</span>
              </div>
              <p className="text-xs text-slate-600 mt-1">{selectedTable.description}</p>
            </div>
            <div className="text-xs text-slate-500 font-mono">
              Engine: InnoDB · Charset: utf8mb4
            </div>
          </div>

          {/* Strict Composite Indexes Section */}
          <div>
            <h4 className="text-xs font-semibold text-slate-800 uppercase tracking-wider mb-2.5 flex items-center gap-2">
              <Layers className="w-4 h-4 text-[#FF6B35]" />
              <span>Composite Indexes ({selectedTable.compositeIndexes.length})</span>
            </h4>
            <div className="space-y-2">
              {selectedTable.compositeIndexes.map((idx, i) => (
                <div
                  key={i}
                  className={`p-3 rounded-lg border text-xs ${
                    idx.isStrictRequirement
                      ? 'bg-orange-50/80 border-orange-200 text-slate-900'
                      : 'bg-slate-50 border-slate-200 text-slate-700'
                  }`}
                >
                  <div className="flex items-center justify-between mb-1">
                    <span className="font-mono font-semibold text-[#0F4C81]">{idx.name}</span>
                    {idx.isStrictRequirement && (
                      <span className="text-[10px] bg-[#FF6B35] text-white px-2 py-0.5 rounded-full font-medium">
                        Master Spec Index
                      </span>
                    )}
                  </div>
                  <div className="font-mono text-slate-600 mb-1">
                    INDEX ({idx.columns.join(', ')})
                  </div>
                  <p className="text-slate-500 text-[11px]">{idx.purpose}</p>
                </div>
              ))}
            </div>
          </div>

          {/* Columns Table */}
          <div>
            <h4 className="text-xs font-semibold text-slate-800 uppercase tracking-wider mb-2.5 flex items-center gap-2">
              <FileCode className="w-4 h-4 text-[#0F4C81]" />
              <span>Table Columns & Constraints</span>
            </h4>
            <div className="overflow-x-auto border border-slate-200 rounded-lg">
              <table className="w-full text-left text-xs">
                <thead className="bg-slate-50 border-b border-slate-200 text-slate-600">
                  <tr>
                    <th className="py-2.5 px-3 font-semibold">Column</th>
                    <th className="py-2.5 px-3 font-semibold">Type</th>
                    <th className="py-2.5 px-3 font-semibold">Key</th>
                    <th className="py-2.5 px-3 font-semibold">Details & Notes</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {selectedTable.columns.map((col, idx) => (
                    <tr key={idx} className="hover:bg-slate-50/60 transition-colors">
                      <td className="py-2 px-3 font-mono font-medium text-slate-900">
                        {col.name}
                      </td>
                      <td className="py-2 px-3 font-mono text-slate-600 text-[11px]">
                        {col.type}
                      </td>
                      <td className="py-2 px-3">
                        {col.key === 'PK' && (
                          <span className="text-[10px] font-mono font-bold text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded">PK</span>
                        )}
                        {col.key === 'FK' && (
                          <span className="text-[10px] font-mono font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">FK</span>
                        )}
                        {col.key === 'UNI' && (
                          <span className="text-[10px] font-mono font-bold text-purple-700 bg-purple-100 px-1.5 py-0.5 rounded">UNI</span>
                        )}
                        {col.key === 'IDX' && (
                          <span className="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">IDX</span>
                        )}
                      </td>
                      <td className="py-2 px-3 text-slate-500 text-[11px]">
                        {col.notes || '-'}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          {/* Foreign Keys */}
          {selectedTable.foreignKeys.length > 0 && (
            <div>
              <h4 className="text-xs font-semibold text-slate-800 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                <Key className="w-4 h-4 text-slate-600" />
                <span>Foreign Key Relationships</span>
              </h4>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                {selectedTable.foreignKeys.map((fk, i) => (
                  <div key={i} className="p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs flex items-center justify-between">
                    <span className="font-mono text-slate-800">{fk.column}</span>
                    <div className="flex items-center gap-1.5 text-slate-500">
                      <ArrowRight className="w-3.5 h-3.5" />
                      <span className="font-mono text-[#0F4C81]">{fk.references}</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
