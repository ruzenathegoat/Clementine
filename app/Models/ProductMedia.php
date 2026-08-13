<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductMedia extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = ['product_id', 'url', 'type', 'sort_order'];

    /**
     * Rewrite old Supabase URLs to Cloudflare R2 URLs on the fly.
     */
    public function getUrlAttribute($value)
    {
        $supabasePrefix = 'https://qcrmvarkayzimbjyolum.supabase.co/storage/v1/object/public/products';
        $r2Url = rtrim(config('filesystems.disks.r2.url'), '/');

        if (!empty($r2Url) && str_contains($value, $supabasePrefix)) {
            return str_replace($supabasePrefix, $r2Url, $value);
        }

        return $value;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}