<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    protected $fillable = [
        'product_id', 'product_name', 'quantity', 'price', 'checked_out_at',
    ];
}