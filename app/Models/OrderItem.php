<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasUuids;

    public $timestamps = false; // no created_at/updated_at in schema

    protected $fillable = [
        'order_id',
        'product_id',
        'strap_option_id',
        'quantity',
        'price_at_purchase',
        'tax_amount',
        'discount_amount',
        'cogs_at_purchase',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function strapOption(): BelongsTo
    {
        return $this->belongsTo(Productstrapoption::class, 'strap_option_id');
    }
}
