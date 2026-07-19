<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUuids;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'status',
        'contact_email',
        'shipping_full_name',
        'shipping_address1',
        'shipping_address2',
        'shipping_city',
        'shipping_postal_code',
        'shipping_country',
        'tracking_number',
        'shipped_at',
        'billing_same_as_shipping',
        'payment_method',
        'payment_status',
        'payment_details',
        'subtotal',
        'shipping_fee',
        'shipping_tax',
        'tax',
        'discount_amount',
        'promo_code_id',
        'total',
        'cancel_reason',
        'cancel_description',
    ];

    protected $casts = [
        'payment_details' => 'array',
        'shipped_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted()
    {
        static::saved(function ($order) {
            if ($order->user_id && $order->payment_status === 'paid') {
                $user = $order->user;
                if ($user && !$user->is_vip) {
                    $totalSpent = \App\Models\Order::where('user_id', $user->id)
                        ->where('payment_status', 'paid')
                        ->sum('total');
                        
                    if ($totalSpent > 10000) {
                        $user->update(['is_vip' => true]);
                    }
                }
            }
        });
    }
}
