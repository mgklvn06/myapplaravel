<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price', 'sku', 'stock_quantity',
        'is_active', 'is_featured', 'category_id', 'images', 'attributes',
        'view_count', 'sold_count', 'image'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Category relationship
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected $casts = [
    'attributes' => 'array',
    'is_active' => 'boolean',
    'is_featured' => 'boolean',
];

    /**
     * Get the main image URL for the product.
     */
    public function getImageUrlAttribute()
    {
        $images = $this->getOriginal('images'); // get raw value from DB

        if (is_string($images)) {
            $decoded = json_decode($images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $images = $decoded;
            } else {
                // it's a plain string URL
                return $images;
            }
        }

        if (is_array($images) && !empty($images)) {
            return $images[0];
        }

        // Fallback to 'image' field if it exists
        return $this->getAttribute('image') ?? null;
    }
}
