<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'status',
        'total_orders',
        'total_spent',
        'last_ordered_at',
    ];

    protected $casts = [
        'total_orders' => 'integer',
        'total_spent' => 'decimal:2',
        'last_ordered_at' => 'datetime',
    ];

    /**
     * Optional linked user account (for registered users vs guest checkout).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All saved delivery addresses.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Customer's primary/default shipping address.
     */
    public function defaultShippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default_shipping', true);
    }

    /**
     * Orders placed by this customer.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->latest();
    }

    /**
     * Normalize Bangladesh phone number (ensure 11 digits format: 01XXXXXXXXX).
     */
    public static function normalizePhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading country code if present (+880 or 880)
        if (str_starts_with($clean, '8801')) {
            $clean = substr($clean, 2);
        }
        
        return $clean;
    }
}
