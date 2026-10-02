<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CodGateway implements PaymentGatewayInterface
{
    public const IDENTIFIER = 'cod';

    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    public function getTitle(): string
    {
        return 'Cash on Delivery (ক্যাশ অন ডেলিভারি)';
    }

    /**
     * Verify whether Cash on Delivery is supported for this specific delivery zone.
     */
    public function isAvailableForOrder(Order $order): bool
    {
        // COD requires active delivery zone that allows COD
        if (!$order->deliveryZone || !$order->deliveryZone->cod_available) {
            return false;
        }

        // Fraud / threshold protection: orders over 25,000 BDT may require advance payment
        if ($order->total_amount > 25000.00) {
            return false;
        }

        return true;
    }

    /**
     * Initialize COD transaction.
     * Records an authorized/pending payment to be collected at the customer's doorstep.
     */
    public function initiatePayment(Order $order, array $payload = []): array
    {
        if (!$this->isAvailableForOrder($order)) {
            throw new InvalidArgumentException(
                "Cash on Delivery is unavailable for {$order->deliveryZone?->name} or order total exceeds threshold."
            );
        }

        return DB::transaction(function () use ($order, $payload) {
            $transactionId = 'COD-' . $order->order_number . '-' . strtoupper(Str::random(4));

            /** @var Payment $payment */
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => self::IDENTIFIER,
                'transaction_id' => $transactionId,
                'amount' => $order->total_amount,
                'currency' => 'BDT',
                'status' => 'pending',
                'gateway_response' => [
                    'initiated_via' => 'web_checkout',
                    'collection_due_bdt' => (float) $order->total_amount,
                    'customer_phone' => $order->customer_phone,
                    'delivery_zone' => $order->deliveryZone?->name,
                    'notes' => $payload['instructions'] ?? 'Collect exact cash upon physical parcel inspection',
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);

            // Update order payment status
            $order->update([
                'payment_method' => self::IDENTIFIER,
                'payment_status' => 'pending',
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'transaction_id' => $transactionId,
                'status' => 'pending_cod_collection',
                'message' => "Order #{$order->order_number} confirmed. Please pay ৳" . number_format($order->total_amount, 2) . " to the delivery courier upon arrival.",
                'redirect_url' => null,
            ];
        });
    }

    /**
     * Mark COD order as paid once delivery courier confirms physical cash hand-over.
     */
    public function verifyPayment(Order $order, array $verificationData = []): array
    {
        return DB::transaction(function () use ($order, $verificationData) {
            /** @var Payment|null $payment */
            $payment = $order->payments()
                ->where('payment_gateway', self::IDENTIFIER)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->latest()
                ->first();

            if (!$payment) {
                return [
                    'success' => false,
                    'status' => 'payment_not_found',
                    'message' => 'No pending COD payment record found for this order.',
                ];
            }

            $collectedAmount = isset($verificationData['collected_amount']) 
                ? (float) $verificationData['collected_amount'] 
                : (float) $order->total_amount;

            $courierRider = $verificationData['rider_name'] ?? 'Courier Delivery Agent';
            $consignmentId = $verificationData['consignment_id'] ?? $order->courier_tracking_code;

            $payment->update([
                'status' => 'completed',
                'verified_at' => now(),
                'gateway_response' => array_merge($payment->gateway_response ?? [], [
                    'collected_amount' => $collectedAmount,
                    'collected_by_rider' => $courierRider,
                    'courier_consignment' => $consignmentId,
                    'settlement_status' => 'courier_wallet_pending',
                    'collected_at' => now()->toIso8601String(),
                ]),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'delivered',
                'delivered_at' => now(),
            ]);

            $order->addStatusHistory(
                toStatus: 'delivered',
                comment: "COD cash collected: ৳" . number_format($collectedAmount, 2) . " by {$courierRider}.",
                notified: true,
                channel: 'sms'
            );

            return [
                'success' => true,
                'status' => 'completed',
                'message' => 'COD cash collection successfully verified and recorded.',
            ];
        });
    }

    /**
     * COD refund handler (e.g. for returned goods after payment).
     */
    public function refund(Order $order, float $amount, string $reason): array
    {
        return DB::transaction(function () use ($order, $amount, $reason) {
            $payment = $order->payments()
                ->where('payment_gateway', self::IDENTIFIER)
                ->where('status', 'completed')
                ->first();

            if (!$payment) {
                return [
                    'success' => false,
                    'message' => 'Cannot refund an uncollected COD order.',
                ];
            }

            $refundId = 'REF-' . strtoupper(Str::random(8));

            $order->payments()->create([
                'payment_gateway' => self::IDENTIFIER,
                'transaction_id' => $refundId,
                'amount' => -$amount,
                'currency' => 'BDT',
                'status' => 'refunded',
                'failure_reason' => null,
                'gateway_response' => [
                    'original_transaction_id' => $payment->transaction_id,
                    'refund_amount' => $amount,
                    'reason' => $reason,
                    'processed_at' => now()->toIso8601String(),
                ],
                'verified_at' => now(),
            ]);

            $order->update(['payment_status' => 'refunded']);

            return [
                'success' => true,
                'refund_id' => $refundId,
                'message' => "Refund of ৳{$amount} recorded for manual bKash/bank disbursement.",
            ];
        });
    }
}
