<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'delivery_zone_id',
        'recipient_name',
        'recipient_phone',
        'division',
        'district',
        'upazila',
        'area',
        'street_address',
        'landmark',
        'postal_code',
        'is_default_shipping',
        'is_default_billing',
    ];

    protected $casts = [
        'is_default_shipping' => 'boolean',
        'is_default_billing' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    /**
     * Get fully formatted Bangladesh delivery address string.
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street_address,
            $this->area ? "Area: {$this->area}" : null,
            $this->landmark ? "(Near: {$this->landmark})" : null,
            "{$this->upazila}, {$this->district}",
            $this->division,
            $this->postal_code ? "Postal Code: {$this->postal_code}" : null,
        ]);

        return implode(', ', $parts);
    }
}
