<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Collection extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'slug', 'description', 'image_url'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}