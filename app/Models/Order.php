<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_phone',
        'shipping_address_id',
        'delivery_zone_id',
        'subtotal',
        'delivery_charge',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'courier_name',
        'courier_tracking_code',
        'courier_consignment_id',
        'customer_notes',
        'admin_notes',
        'confirmed_at',
        'dispatched_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'dispatched_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest('created_at');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Fast Order Tracking scope by Order Number + Phone (Strict Requirement).
     */
    public function scopeTrack(Builder $query, string $orderNumber, string $phone): Builder
    {
        $normalizedPhone = Customer::normalizePhone($phone);
        
        return $query->where('order_number', trim($orderNumber))
                     ->where('customer_phone', $normalizedPhone);
    }

    /**
     * Scope for pending verification orders.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('order_status', 'pending');
    }

    /**
     * Generate unique Cyclone Mart order identifier (e.g. CM-20261002-8842).
     */
    public static function generateOrderNumber(): string
    {
        $datePrefix = date('Ymd');
        $random = strtoupper(Str::random(5));
        return "CM-{$datePrefix}-{$random}";
    }

    /**
     * Check if customer can cancel this order.
     */
    public function canCancel(): bool
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Record an audit timeline entry.
     */
    public function addStatusHistory(
        string $toStatus,
        ?string $comment = null,
        ?int $userId = null,
        bool $notified = false,
        ?string $channel = 'sms'
    ): OrderStatusHistory {
        $fromStatus = $this->order_status;
        $this->update(['order_status' => $toStatus]);

        return $this->statusHistory()->create([
            'user_id' => $userId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'comment' => $comment,
            'notified_customer' => $notified,
            'notification_channel' => $channel,
        ]);
    }
}
