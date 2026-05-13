<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'name_km',       // ឈ្មោះជាភាសាខ្មែរ
        'description',
        'description_km',
        'price',
        'sale_price',
        'stock',
        'images',        // array of image URLs
        'category_id',
        'tags',
        'is_active',
        'is_featured',
        'sku',
        'weight',
        'attributes',    // color, size, etc.
    ];

    protected $casts = [
        'price'       => 'float',
        'sale_price'  => 'float',
        'stock'       => 'integer',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'images'      => 'array',
        'tags'        => 'array',
        'attributes'  => 'array',
    ];

    protected $attributes = [
        'is_active'   => true,
        'is_featured' => false,
        'stock'       => 0,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }
}
