<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category; // adjust if namespace differs

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->unique()->words(mt_rand(2,4), true); // e.g. "Cotton T-shirt"
        $price = $this->faker->randomFloat(2, 5, 300); // between $5 and $300
        $onSale = $this->faker->boolean(20); // 20% chance on sale
        $salePrice = $onSale ? round($price * $this->faker->randomFloat(2, 0.6, 0.95), 2) : null;

        // choose a category if exists, else null
        $categoryId = Category::inRandomOrder()->value('id');

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'description' => $this->faker->paragraphs(mt_rand(1,3), true),
            'price' => $price,
            'sale_price' => $salePrice,
            'sku' => strtoupper(Str::random(8)),
            'stock' => $this->faker->numberBetween(0, 200),
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(10),
            'category_id' => $categoryId,
            'images' => [
                // example placeholder images; replace with real storage paths later
                'https://picsum.photos/seed/' . $this->faker->unique()->numberBetween(1,1000) . '/800/800',
            ],
            'attributes' => [
                'color' => $this->faker->randomElement(['red','blue','green','black','white']),
                'size' => $this->faker->randomElement(['S','M','L','XL']),
                'weight_kg' => $this->faker->randomFloat(2, 0.1, 5),
            ],
            'view_count' => $this->faker->numberBetween(0, 2000),
            'sold_count' => $this->faker->numberBetween(0, 500),
        ];
    }
}
