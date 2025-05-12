<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'image',
        'sizes',
        'colors',
        'types',
    ];

    // Cast JSON columns to arrays automatically
    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'types' => 'array',
    ];
}
