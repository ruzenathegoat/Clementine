<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductStrapOption extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['product_id', 'strap_name', 'price_delta', 'sort_order'];

    protected $casts = [
        'price_delta' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}