<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    protected $fillable = ['title', 'link', 'image_url', 'source', 'pub_date'];

    protected $casts = [
        'pub_date' => 'datetime',
    ];
}
