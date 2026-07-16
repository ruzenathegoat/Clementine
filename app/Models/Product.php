<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'collection_id', 'name', 'slug', 'tagline', 'description', 'price', 'cogs',
        'gender', 'material', 'movement', 'diameter_mm', 'water_resistance',
        'crystal', 'case_material', 'warranty_years', 'stock', 'status', 'release_at', 'scheduled_publish_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cogs' => 'decimal:2',
        'release_at' => 'datetime',
        'scheduled_publish_at' => 'datetime',
    ];

    protected $with = ['primaryImage'];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function straps()
    {
        return $this->hasMany(ProductStrapOption::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductMedia::class)->where('type', 'image')->orderBy('sort_order');
    }

    public static function transitionExpiredDrops()
    {
        static::where('status', 'new')
            ->whereNotNull('scheduled_publish_at')
            ->where('scheduled_publish_at', '<=', now()->subMinutes(40))
            ->update([
                'status' => 'active',
                'updated_at' => now(), // Reset updated_at to ensure 7-day catalog rule starts from drop end
            ]);
    }
}