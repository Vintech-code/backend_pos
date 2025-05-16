<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    // Add image_url to the model's array and JSON output
    protected $appends = ['image_url'];

    /**
     * Get the full public URL for the product image
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // If it's already a full URL (e.g., from external source), return as-is
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        // Generate the full public URL for locally stored images
        return Storage::disk('public')->url($this->image);
    }
}