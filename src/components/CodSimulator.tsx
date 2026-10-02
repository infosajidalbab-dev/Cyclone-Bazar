import React, { useState } from 'react';
import { Truck, CheckCircle2, DollarSign, RotateCcw, AlertTriangle, ShieldCheck, ArrowRight, Clock } from 'lucide-react';

interface MockOrder {
  orderNumber: string;
  customerName: string;
  customerPhone: string;
  deliveryZone: string;
  totalAmount: number;
  deliveryCharge: number;
  itemsSummary: string;
  paymentMethod: 'cod';
  paymentStatus: 'pending' | 'paid' | 'refunded';
  orderStatus: 'pending' | 'confirmed' | 'handed_over_to_courier' | 'delivered' | 'returned';
  transactionId: string;
  timeline: { title: string; time: string; comment: string }[];
}

export const CodSimulator: React.FC = () => {
  const [order, setOrder] = useState<MockOrder>({
    orderNumber: 'CM-20261002-7749',
    customerName: 'Tanvir Hossain',
    customerPhone: '01712345678',
    deliveryZone: 'Inside Dhaka',
    totalAmount: 1850.00,
    deliveryCharge: 60.00,
    itemsSummary: '1x Wireless Bluetooth Earbuds (Black)',
    paymentMethod: 'cod',
    paymentStatus: 'pending',
    orderStatus: 'confirmed',
    transactionId: 'COD-CM-20261002-7749-8F2B',
    timeline: [
      {
        title: 'Order Placed (COD Initiated)',
        time: '12:30 PM',
        comment: 'CodGateway::initiatePayment() generated pending transaction COD-CM-20261002-7749-8F2B'
      },
      {
        title: 'Order Confirmed by Operations',
        time: '12:45 PM',
        comment: 'Phone verified with customer. Dispatched to hub.'
      }
    ]
  });

  const [riderName, setRiderName] = useState<string>('Rahim (Steadfast Courier)');
  const [cashCollected, setCashCollected] = useState<number>(1850);
  const [refundReason, setRefundReason] = useState<string>('Customer parcel return within 7 days warranty');
  const [actionSuccess, setActionSuccess] = useState<string | null>(null);

  const simulateCourierDispatch = () => {
    setOrder(prev => ({
      ...prev,
      orderStatus: 'handed_over_to_courier',
      timeline: [
        ...prev.timeline,
        {
          title: 'Handed Over to Courier',
          time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          comment: `Parcel consigned to Steadfast Courier. Consignment ID: STD-${Math.floor(100000 + Math.random() * 900000)}`
        }
      ]
    }));
    setActionSuccess('Dispatched to courier hub successfully.');
    setTimeout(() => setActionSuccess(null), 3000);
  };

  const simulateRiderCashCollection = () => {
    // Simulates CodGateway::verifyPayment()
    setOrder(prev => ({
      ...prev,
      paymentStatus: 'paid',
      orderStatus: 'delivered',
      timeline: [
        ...prev.timeline,
        {
          title: 'Cash Collected & Delivered (CodGateway::verifyPayment)',
          time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          comment: `৳${cashCollected.toFixed(2)} cash collected by ${riderName}. Order marked DELIVERED and PAID.`
        }
      ]
    }));
    setActionSuccess('CodGateway::verifyPayment executed. Payment status marked PAID.');
    setTimeout(() => setActionSuccess(null), 3000);
  };

  const simulateRefund = () => {
    // Simulates CodGateway::refund()
    setOrder(prev => ({
      ...prev,
      paymentStatus: 'refunded',
      orderStatus: 'returned',
      timeline: [
        ...prev.timeline,
        {
          title: 'COD Refund Processed (CodGateway::refund)',
          time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          comment: `Refund REF-${Math.random().toString(36).substring(2, 8).toUpperCase()} logged: ${refundReason}. Amount: ৳${order.totalAmount}`
        }
      ]
    }));
    setActionSuccess('Refund recorded and settlement updated.');
    setTimeout(() => setActionSuccess(null), 3000);
  };

  const resetOrder = () => {
    setOrder({
      orderNumber: `CM-20261002-${Math.floor(1000 + Math.random() * 9000)}`,
      customerName: 'Tanvir Hossain',
      customerPhone: '01712345678',
      deliveryZone: 'Inside Dhaka',
      totalAmount: 1850.00,
      deliveryCharge: 60.00,
      itemsSummary: '1x Wireless Bluetooth Earbuds (Black)',
      paymentMethod: 'cod',
      paymentStatus: 'pending',
      orderStatus: 'confirmed',
      transactionId: `COD-CM-${Math.floor(10000 + Math.random() * 90000)}`,
      timeline: [
        {
          title: 'Order Placed (COD Initiated)',
          time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
          comment: 'CodGateway::initiatePayment() created pending collection record.'
        }
      ]
    });
  };

  return (
    <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
      {/* Simulation Controls */}
      <div className="lg:col-span-5 space-y-4">
        <div className="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
          <div className="flex items-center justify-between pb-3 border-b border-slate-100">
            <div className="flex items-center gap-2">
              <DollarSign className="w-5 h-5 text-[#FF6B35]" />
              <h3 className="font-semibold text-slate-900 text-sm">CodGateway Lifecycle Simulator</h3>
            </div>
            <button
              onClick={resetOrder}
              className="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1 font-mono"
            >
              <RotateCcw className="w-3.5 h-3.5" />
              <span>Reset</span>
            </button>
          </div>

          {actionSuccess && (
            <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 flex items-center gap-2">
              <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
              <span>{actionSuccess}</span>
            </div>
          )}

          {/* Step 1: Gateway Availability Rule */}
          <div className="p-3.5 bg-slate-50 rounded-lg border border-slate-200 text-xs space-y-1.5">
            <div className="flex items-center justify-between">
              <span className="font-semibold text-slate-900">Gateway Abstraction Check</span>
              <span className="text-[11px] text-emerald-700 bg-emerald-100 font-mono px-2 py-0.5 rounded font-medium">AVAILABLE</span>
            </div>
            <p className="text-slate-600 text-[11px]">
              <code className="font-mono text-[#0F4C81]">isAvailableForOrder()</code> verified zone allows COD (Inside Dhaka = YES) and total ৳1,850.00 &lt; ৳25,000 threshold.
            </p>
          </div>

          {/* Action 1: Dispatch to Courier */}
          <div className="space-y-2">
            <span className="text-xs font-semibold text-slate-800">1. Fulfillment Hand-Off</span>
            <button
              disabled={order.orderStatus !== 'confirmed'}
              onClick={simulateCourierDispatch}
              className={`w-full py-2.5 px-4 rounded-lg text-xs font-medium flex items-center justify-center gap-2 transition-all ${
                order.orderStatus === 'confirmed'
                  ? 'bg-[#0F4C81] text-white hover:bg-[#0A355C]'
                  : 'bg-slate-100 text-slate-400 cursor-not-allowed'
              }`}
            >
              <Truck className="w-4 h-4" />
              <span>Dispatch Parcel to Steadfast Courier</span>
            </button>
          </div>

          {/* Action 2: Rider Doorstep Collection */}
          <div className="space-y-2 pt-2 border-t border-slate-100">
            <span className="text-xs font-semibold text-slate-800">2. Doorstep Cash Collection (Rider Confirmation)</span>
            <div className="space-y-2">
              <div className="flex items-center gap-2">
                <input
                  type="text"
                  value={riderName}
                  onChange={(e) => setRiderName(e.target.value)}
                  placeholder="Rider name"
                  className="w-full text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg"
                />
                <input
                  type="number"
                  value={cashCollected}
                  onChange={(e) => setCashCollected(parseFloat(e.target.value) || 0)}
                  placeholder="Cash (BDT)"
                  className="w-32 text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg font-mono"
                />
              </div>
              <button
                disabled={order.orderStatus !== 'handed_over_to_courier' || order.paymentStatus === 'paid'}
                onClick={simulateRiderCashCollection}
                className={`w-full py-2.5 px-4 rounded-lg text-xs font-medium flex items-center justify-center gap-2 transition-all ${
                  order.orderStatus === 'handed_over_to_courier' && order.paymentStatus !== 'paid'
                    ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm'
                    : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                }`}
              >
                <CheckCircle2 className="w-4 h-4" />
                <span>Execute CodGateway::verifyPayment()</span>
              </button>
            </div>
          </div>

          {/* Action 3: Customer Refund */}
          {order.paymentStatus === 'paid' && (
            <div className="space-y-2 pt-2 border-t border-slate-100">
              <span className="text-xs font-semibold text-slate-800">3. Process COD Return / Refund</span>
              <input
                type="text"
                value={refundReason}
                onChange={(e) => setRefundReason(e.target.value)}
                placeholder="Reason for return"
                className="w-full text-xs p-2 bg-slate-50 border border-slate-200 rounded-lg"
              />
              <button
                onClick={simulateRefund}
                className="w-full py-2 px-3 rounded-lg text-xs font-medium border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors"
              >
                Execute CodGateway::refund() (৳{order.totalAmount})
              </button>
            </div>
          )}
        </div>
      </div>

      {/* Order Status & Audit Trail */}
      <div className="lg:col-span-7 space-y-4">
        <div className="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-5">
          {/* Order Header */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-3">
            <div>
              <div className="flex items-center gap-2">
                <span className="text-sm font-bold font-mono text-slate-900">{order.orderNumber}</span>
                <span className={`text-[10px] font-mono px-2 py-0.5 rounded font-medium ${
                  order.paymentStatus === 'paid'
                    ? 'bg-emerald-100 text-emerald-800'
                    : order.paymentStatus === 'refunded'
                    ? 'bg-purple-100 text-purple-800'
                    : 'bg-amber-100 text-amber-800'
                }`}>
                  PAYMENT: {order.paymentStatus.toUpperCase()}
                </span>
                <span className="text-[10px] font-mono px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                  {order.orderStatus.toUpperCase()}
                </span>
              </div>
              <p className="text-xs text-slate-500 mt-1">
                Customer: <span className="font-semibold text-slate-700">{order.customerName}</span> · Phone: <span className="font-mono text-slate-700">{order.customerPhone}</span>
              </p>
            </div>

            <div className="text-right">
              <span className="text-xs text-slate-500 block">Total Due (BDT)</span>
              <span className="text-lg font-bold font-mono text-[#0F4C81]">৳{order.totalAmount.toFixed(2)}</span>
            </div>
          </div>

          {/* Details Grid */}
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            <div className="p-3 bg-slate-50 rounded-lg">
              <span className="text-slate-500 block text-[11px]">Payment Gateway</span>
              <span className="font-mono font-medium text-slate-800">CodGateway</span>
            </div>
            <div className="p-3 bg-slate-50 rounded-lg">
              <span className="text-slate-500 block text-[11px]">Transaction ID</span>
              <span className="font-mono text-[11px] text-slate-800 truncate block">{order.transactionId}</span>
            </div>
            <div className="p-3 bg-slate-50 rounded-lg">
              <span className="text-slate-500 block text-[11px]">Delivery Zone</span>
              <span className="font-medium text-slate-800">{order.deliveryZone} (৳{order.deliveryCharge})</span>
            </div>
          </div>

          {/* Audit History Timeline (OrderStatusHistory table) */}
          <div>
            <h4 className="text-xs font-semibold text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <Clock className="w-4 h-4 text-slate-500" />
              <span>Audit Trail (order_status_history)</span>
            </h4>
            <div className="space-y-3 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
              {order.timeline.map((item, idx) => (
                <div key={idx} className="relative pl-7 text-xs">
                  <span className="absolute left-1.5 top-1.5 w-3 h-3 rounded-full bg-[#0F4C81] border-2 border-white ring-1 ring-slate-200" />
                  <div className="flex items-center justify-between">
                    <span className="font-semibold text-slate-800">{item.title}</span>
                    <span className="text-[11px] text-slate-500 font-mono">{item.time}</span>
                  </div>
                  <p className="text-slate-600 text-[11px] mt-0.5 bg-slate-50 p-2 rounded border border-slate-100 font-mono">
                    {item.comment}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
