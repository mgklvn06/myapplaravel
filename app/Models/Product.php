<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price', 'sku', 'stock',
        'is_active', 'is_featured', 'category_id', 'images', 'attributes',
        'view_count', 'sold_count', 'image'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    protected $casts = [
    'images' => 'array',
    'attributes' => 'array',
    'is_active' => 'boolean',
    'is_featured' => 'boolean',
];

}

