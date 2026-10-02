<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /**
     * Get the unique machine-readable gateway identifier (e.g. 'cod', 'bkash', 'nagad').
     */
    public function getIdentifier(): string;

    /**
     * Get the human-friendly display label (e.g. 'Cash on Delivery (ক্যাশ অন ডেলিভারি)').
     */
    public function getTitle(): string;

    /**
     * Check if this gateway is permitted for the given order and delivery zone.
     */
    public function isAvailableForOrder(Order $order): bool;

    /**
     * Initiate payment transaction for the order.
     * For COD: Initializes pending collection record.
     * For bKash/Nagad: Generates tokenized payment URL.
     *
     * @param Order $order
     * @param array<string, mixed> $payload
     * @return array{
     *     success: bool,
     *     payment: Payment,
     *     transaction_id: string,
     *     status: string,
     *     message: string,
     *     redirect_url?: string|null
     * }
     */
    public function initiatePayment(Order $order, array $payload = []): array;

    /**
     * Verify payment status (e.g. rider delivery collection, gateway webhook, or IPN).
     *
     * @param Order $order
     * @param array<string, mixed> $verificationData
     * @return array{
     *     success: bool,
     *     status: string,
     *     message: string
     * }
     */
    public function verifyPayment(Order $order, array $verificationData = []): array;

    /**
     * Process refund if applicable.
     *
     * @param Order $order
     * @param float $amount
     * @param string $reason
     * @return array{
     *     success: bool,
     *     refund_id?: string,
     *     message: string
     * }
     */
    public function refund(Order $order, float $amount, string $reason): array;
}
