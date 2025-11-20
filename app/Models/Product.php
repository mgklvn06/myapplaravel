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
    'images' => 'string',
    'attributes' => 'array',
    'is_active' => 'boolean',
    'is_featured' => 'boolean',
];

}

