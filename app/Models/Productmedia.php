<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductMedia extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['product_id', 'url', 'type', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}