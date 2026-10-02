import React, { useState } from 'react';
import { Truck, CheckCircle2, Search, Filter, Clock, ChevronRight, X, DollarSign, ShieldCheck } from 'lucide-react';

interface MockOrderRecord {
  id: number;
  orderNumber: string;
  customerName: string;
  customerPhone: string;
  zone: string;
  deliveryCharge: number;
  totalAmount: number;
  paymentMethod: 'cod';
  paymentStatus: 'pending' | 'paid' | 'refunded';
  orderStatus: 'pending' | 'confirmed' | 'processing' | 'handed_over_to_courier' | 'delivered' | 'cancelled';
  courierName?: string;
  courierTrackingCode?: string;
  items: { name: string; sku: string; qty: number; unitPrice: number; supplier: string }[];
  address: { hierarchy: string; street: string; landmark: string };
  timeline: { status: string; time: string; comment: string }[];
}

const INITIAL_ORDERS: MockOrderRecord[] = [
  {
    id: 1,
    orderNumber: 'CM-20261002-8821',
    customerName: 'Tanvir Hossain',
    customerPhone: '01712345678',
    zone: 'Inside Dhaka',
    deliveryCharge: 60,
    totalAmount: 1310.00,
    paymentMethod: 'cod',
    paymentStatus: 'pending',
    orderStatus: 'pending',
    items: [
      { name: 'Cyclone TWS Pro Wireless Earbuds', sku: 'CM-EAR-BLK-01', qty: 1, unitPrice: 1250, supplier: 'Dhaka Hub' }
    ],
    address: {
      hierarchy: 'Mirpur, Dhaka, Dhaka Division',
      street: 'House 42, Road 7, Block C',
      landmark: 'Near Metro Rail Pillar 240'
    },
    timeline: [
      { status: 'PENDING', time: '10:15 AM', comment: 'Order placed by customer via Cash on Delivery.' }
    ]
  },
  {
    id: 2,
    orderNumber: 'CM-20261002-9402',
    customerName: 'Nasrin Akter',
    customerPhone: '01899123456',
    zone: 'Outside Dhaka',
    deliveryCharge: 130,
    totalAmount: 2630.00,
    paymentMethod: 'cod',
    paymentStatus: 'pending',
    orderStatus: 'confirmed',
    courierName: 'Steadfast Courier',
    courierTrackingCode: 'STD-884129',
    items: [
      { name: 'Cyclone MagPulse Powerbank 20000mAh', sku: 'CM-PWR-20K-BLK', qty: 1, unitPrice: 2250, supplier: 'Chittagong Importers' },
      { name: 'Type-C Braided Fast Cable', sku: 'CM-CBL-65W', qty: 1, unitPrice: 250, supplier: 'Dhaka Hub' }
    ],
    address: {
      hierarchy: 'Bogura Sadar, Bogura, Rajshahi Division',
      street: 'Holding 14, Thana Road, Jaleshwaritola',
      landmark: 'Opposite to Central Mosque'
    },
    timeline: [
      { status: 'PENDING', time: 'Yesterday 04:30 PM', comment: 'Order placed online.' },
      { status: 'CONFIRMED', time: 'Yesterday 05:15 PM', comment: 'Customer confirmed via phone verification.' }
    ]
  },
  {
    id: 3,
    orderNumber: 'CM-20261001-7110',
    customerName: 'Farhan Kabir',
    customerPhone: '01911987654',
    zone: 'Dhaka Suburbs',
    deliveryCharge: 100,
    totalAmount: 1550.00,
    paymentMethod: 'cod',
    paymentStatus: 'paid',
    orderStatus: 'delivered',
    courierName: 'Pathao Courier',
    courierTrackingCode: 'PTH-992144',
    items: [
      { name: 'Cyclone Smart Magnetic Stand', sku: 'CM-STD-MAG', qty: 1, unitPrice: 1450, supplier: 'Gazipur Hub' }
    ],
    address: {
      hierarchy: 'Tongi, Gazipur, Dhaka Division',
      street: 'College Gate Road, Block B',
      landmark: 'Near Tongi Station'
    },
    timeline: [
      { status: 'PENDING', time: '01 Oct 11:00 AM', comment: 'Order submitted.' },
      { status: 'HANDED_OVER_TO_COURIER', time: '01 Oct 02:00 PM', comment: 'Consigned to Pathao Courier.' },
      { status: 'DELIVERED', time: '02 Oct 01:15 PM', comment: 'COD cash ৳1,550 collected by rider. Reconciled.' }
    ]
  }
];

export const AdminOrderManagerPreview: React.FC = () => {
  const [orders, setOrders] = useState<MockOrderRecord[]>(INITIAL_ORDERS);
  const [search, setSearch] = useState<string>('');
  const [statusFilter, setStatusFilter] = useState<string>('all');
  const [selectedOrder, setSelectedOrder] = useState<MockOrderRecord | null>(null);

  // Modal actions
  const [courierName, setCourierName] = useState('Steadfast Courier');
  const [trackingCode, setTrackingCode] = useState('');
  const [riderName, setRiderName] = useState('Rider Karim');

  const filteredOrders = orders.filter((o) => {
    const matchesSearch =
      search === '' ||
      o.orderNumber.toLowerCase().includes(search.toLowerCase()) ||
      o.customerPhone.includes(search);

    const matchesStatus = statusFilter === 'all' || o.orderStatus === statusFilter;
    return matchesSearch && matchesStatus;
  });

  const updateOrderStatus = (newStatus: MockOrderRecord['orderStatus']) => {
    if (!selectedOrder) return;

    const updatedOrders = orders.map((o) => {
      if (o.id === selectedOrder.id) {
        const updated = {
          ...o,
          orderStatus: newStatus,
          timeline: [
            ...o.timeline,
            {
              status: newStatus.toUpperCase(),
              time: 'Just Now',
              comment: `Status updated to ${newStatus.toUpperCase()} by Operations Manager.`
            }
          ]
        };
        setSelectedOrder(updated);
        return updated;
      }
      return o;
    });

    setOrders(updatedOrders);
  };

  const handleAssignCourier = () => {
    if (!selectedOrder || !trackingCode) return;

    const updatedOrders = orders.map((o) => {
      if (o.id === selectedOrder.id) {
        const updated: MockOrderRecord = {
          ...o,
          courierName: courierName,
          courierTrackingCode: trackingCode,
          orderStatus: 'handed_over_to_courier',
          timeline: [
            ...o.timeline,
            {
              status: 'HANDED_OVER_TO_COURIER',
              time: 'Just Now',
              comment: `Parcel consigned to ${courierName}. Consignment Code: ${trackingCode}. SMS sent to ${o.customerPhone}.`
            }
          ]
        };
        setSelectedOrder(updated);
        return updated;
      }
      return o;
    });

    setOrders(updatedOrders);
    setTrackingCode('');
  };

  const handleReconcileCod = () => {
    if (!selectedOrder) return;

    const updatedOrders = orders.map((o) => {
      if (o.id === selectedOrder.id) {
        const updated: MockOrderRecord = {
          ...o,
          paymentStatus: 'paid',
          orderStatus: 'delivered',
          timeline: [
            ...o.timeline,
            {
              status: 'DELIVERED',
              time: 'Just Now',
              comment: `COD cash ৳${o.totalAmount} collected at doorstep by ${riderName}. Reconciled via CodGateway.`
            }
          ]
        };
        setSelectedOrder(updated);
        return updated;
      }
      return o;
    });

    setOrders(updatedOrders);
  };

  return (
    <div className="space-y-6">
      {/* Intro info bar */}
      <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
        <div>
          <span className="font-semibold text-slate-900 block">Livewire 3 Admin Order Management & Courier Routing</span>
          <span className="text-slate-500">
            Interactive simulation of <code className="text-[#0F4C81] font-mono">OrderManager.php</code> and <code className="text-[#0F4C81] font-mono">order-manager.blade.php</code>.
          </span>
        </div>
        <div className="flex items-center gap-2 font-mono text-[11px] text-slate-600">
          <span>Search Composite Index: ['order_number', 'customer_phone']</span>
        </div>
      </div>

      {/* Filter Toolbar */}
      <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row gap-3">
        <div className="flex-1 relative">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by Order ID (CM-...) or BD Phone (01XXXXXXXXX)..."
            className="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white"
          />
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
        </div>

        <select
          value={statusFilter}
          onChange={(e) => setStatusFilter(e.target.value)}
          className="text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]"
        >
          <option value="all">All Order Statuses</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="handed_over_to_courier">Handed over to Courier</option>
          <option value="delivered">Delivered</option>
        </select>
      </div>

      {/* Order Table */}
      <div className="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
              <tr>
                <th className="py-3 px-4">Order ID</th>
                <th className="py-3 px-4">Customer & Phone</th>
                <th className="py-3 px-4">Delivery Zone</th>
                <th className="py-3 px-4">Total (BDT)</th>
                <th className="py-3 px-4">Payment</th>
                <th className="py-3 px-4">Status</th>
                <th className="py-3 px-4 text-right">Action</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filteredOrders.map((o) => (
                <tr key={o.id} className="hover:bg-slate-50/70 transition-colors">
                  <td className="py-3 px-4 font-mono font-bold text-slate-900">{o.orderNumber}</td>
                  <td className="py-3 px-4">
                    <span className="font-semibold text-slate-800 block">{o.customerName}</span>
                    <span className="text-[11px] font-mono text-[#0F4C81]">{o.customerPhone}</span>
                  </td>
                  <td className="py-3 px-4 text-slate-700">{o.zone} (৳{o.deliveryCharge})</td>
                  <td className="py-3 px-4 font-mono font-bold text-slate-900">৳{o.totalAmount.toFixed(2)}</td>
                  <td className="py-3 px-4">
                    <span
                      className={`text-[10px] font-mono px-2 py-0.5 rounded font-medium ${
                        o.paymentStatus === 'paid'
                          ? 'bg-emerald-100 text-emerald-800'
                          : 'bg-amber-100 text-amber-800'
                      }`}
                    >
                      {o.paymentStatus.toUpperCase()} (COD)
                    </span>
                  </td>
                  <td className="py-3 px-4">
                    <span className="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-[#0F4C81] border border-blue-200">
                      {o.orderStatus.toUpperCase().replace(/_/g, ' ')}
                    </span>
                  </td>
                  <td className="py-3 px-4 text-right">
                    <button
                      onClick={() => setSelectedOrder(o)}
                      className="px-3 py-1.5 min-h-[44px] text-xs font-semibold text-[#0F4C81] bg-blue-50 hover:bg-blue-100 rounded-lg inline-flex items-center gap-1 transition-colors"
                    >
                      <span>Manage</span>
                      <ChevronRight className="w-3.5 h-3.5" />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Slide-over Drawer / Modal */}
      {selectedOrder && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex justify-end">
          <div className="w-full max-w-xl bg-white h-full shadow-2xl p-6 overflow-y-auto space-y-5">
            <div className="flex items-center justify-between pb-3 border-b border-slate-200">
              <div>
                <h3 className="font-bold text-base font-mono text-slate-900">{selectedOrder.orderNumber}</h3>
                <span className="text-xs text-slate-500">Managing Order Lifecycle</span>
              </div>
              <button
                onClick={() => setSelectedOrder(null)}
                className="p-2 text-slate-400 hover:text-slate-700 min-h-[44px] min-w-[44px] flex items-center justify-center"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {/* Status Transition Lifecycle */}
            <div className="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
              <span className="text-xs font-bold text-slate-800 block">Advance Order Status</span>
              <div className="flex flex-wrap gap-2">
                {(['pending', 'confirmed', 'processing', 'handed_over_to_courier', 'delivered'] as const).map((st) => (
                  <button
                    key={st}
                    onClick={() => updateOrderStatus(st)}
                    className={`px-3 py-1.5 text-xs font-semibold rounded-lg min-h-[38px] transition-colors ${
                      selectedOrder.orderStatus === st
                        ? 'bg-[#0F4C81] text-white shadow-xs'
                        : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100'
                    }`}
                  >
                    {st.toUpperCase().replace(/_/g, ' ')}
                  </button>
                ))}
              </div>
            </div>

            {/* Courier Dispatch Assignment */}
            <div className="bg-white border border-slate-200 rounded-xl p-4 space-y-3 text-xs">
              <div className="flex items-center gap-1.5 font-bold text-slate-800">
                <Truck className="w-4 h-4 text-[#0F4C81]" />
                <span>Steadfast / Pathao Courier Assignment</span>
              </div>

              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-[11px] text-slate-500 block mb-1">Courier Partner</label>
                  <select
                    value={courierName}
                    onChange={(e) => setCourierName(e.target.value)}
                    className="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs"
                  >
                    <option value="Steadfast Courier">Steadfast Courier</option>
                    <option value="Pathao Courier">Pathao Courier</option>
                    <option value="RedX Logistics">RedX Logistics</option>
                  </select>
                </div>
                <div>
                  <label className="text-[11px] text-slate-500 block mb-1">Consignment ID</label>
                  <input
                    type="text"
                    value={trackingCode}
                    onChange={(e) => setTrackingCode(e.target.value)}
                    placeholder="e.g. STD-882194"
                    className="w-full p-2 font-mono bg-slate-50 border border-slate-200 rounded-lg text-xs"
                  />
                </div>
              </div>

              <button
                onClick={handleAssignCourier}
                disabled={!trackingCode}
                className="w-full py-2 bg-[#0F4C81] text-white rounded-lg font-semibold hover:bg-[#0A355C] disabled:opacity-50 min-h-[44px]"
              >
                Assign Tracking & Dispatch (Sends SMS)
              </button>
            </div>

            {/* Reconcile Doorstep COD */}
            {selectedOrder.paymentStatus !== 'paid' && (
              <div className="p-4 bg-orange-50 border border-orange-200 rounded-xl space-y-2 text-xs">
                <span className="font-bold text-orange-950 block">Reconcile Doorstep COD Collection</span>
                <p className="text-[11px] text-orange-800">
                  Execute <code className="font-mono bg-orange-100 px-1 py-0.5 rounded">CodGateway::verifyPayment()</code> when courier confirms delivery.
                </p>
                <div className="flex gap-2">
                  <input
                    type="text"
                    value={riderName}
                    onChange={(e) => setRiderName(e.target.value)}
                    placeholder="Rider / Hub ID"
                    className="flex-1 p-2 bg-white border border-orange-300 rounded text-xs"
                  />
                  <button
                    onClick={handleReconcileCod}
                    className="px-4 py-2 bg-[#28A745] hover:bg-emerald-700 text-white font-bold rounded min-h-[44px]"
                  >
                    Confirm ৳{selectedOrder.totalAmount.toFixed(2)} Paid
                  </button>
                </div>
              </div>
            )}

            {/* Customer & Address Details */}
            <div className="p-4 bg-slate-50 rounded-xl space-y-2 text-xs">
              <span className="font-bold text-slate-800 block">Recipient & Address</span>
              <div className="text-slate-600 space-y-1">
                <p>
                  <strong>{selectedOrder.customerName}</strong> ({selectedOrder.customerPhone})
                </p>
                <p>{selectedOrder.address.street}</p>
                <p className="text-[#0F4C81]">{selectedOrder.address.hierarchy}</p>
                <p className="text-amber-800">Near: {selectedOrder.address.landmark}</p>
              </div>
            </div>

            {/* Audit Timeline */}
            <div className="space-y-2 text-xs">
              <span className="font-bold text-slate-800 block">Audit Timeline (OrderStatusHistory)</span>
              <div className="space-y-2">
                {selectedOrder.timeline.map((t, idx) => (
                  <div key={idx} className="p-2.5 bg-slate-50 border border-slate-200 rounded-lg">
                    <div className="flex justify-between font-mono text-[10px] text-slate-500 mb-0.5">
                      <span className="font-bold text-[#0F4C81]">{t.status}</span>
                      <span>{t.time}</span>
                    </div>
                    <p className="text-slate-700 text-[11px] font-mono">{t.comment}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
