<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Str;
use App\Models\Category; // adjust if namespace differs

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        // Use English Faker
        $faker = FakerFactory::create('en_US');

        // Custom English words for names
        $englishWords = ['Cotton', 'Silk', 'Wool', 'Leather', 'Denim', 'Linen', 'Polyester', 'Nylon', 'Rayon', 'Spandex', 'T-Shirt', 'Shirt', 'Pants', 'Jeans', 'Jacket', 'Coat', 'Dress', 'Skirt', 'Sweater', 'Hoodie', 'Sneakers', 'Boots', 'Sandals', 'Hat', 'Bag', 'Watch', 'Belt', 'Scarf', 'Gloves', 'Socks'];

        $nameWords = $faker->randomElements($englishWords, mt_rand(2,4));
        $name = implode(' ', $nameWords);

        $price = $faker->randomFloat(2, 5, 300); // between $5 and $300
        $onSale = $faker->boolean(20); // 20% chance on sale
        $salePrice = $onSale ? round($price * $faker->randomFloat(2, 0.6, 0.95), 2) : null;

        // choose a category if exists, else null
        $categoryId = Category::inRandomOrder()->value('id');

        // Use first word of name as keyword for Unsplash
        $keyword = $nameWords[0];

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'description' => $faker->realText(mt_rand(100, 300)),
            'price' => $price,
            'sale_price' => $salePrice,
            'sku' => strtoupper(Str::random(8)),
            'stock_quantity' => $faker->numberBetween(0, 200),
            'is_active' => $faker->boolean(90),
            'is_featured' => $faker->boolean(10),
            'category_id' => $categoryId,
            'images' => ['https://dummyimage.com/800x600/cccccc/000.png&text=Product+Image'],
            'attributes' => [
                'color' => $faker->randomElement(['red','blue','green','black','white']),
                'size' => $faker->randomElement(['S','M','L','XL']),
                'weight_kg' => $faker->randomFloat(2, 0.1, 5),
            ],
            'view_count' => $faker->numberBetween(0, 2000),
            'sold_count' => $faker->numberBetween(0, 500),
        ];
    }

    // Method to customize the language or data
    public function english()
    {
        return $this->state(function (array $attributes) {
            $faker = FakerFactory::create('en_US');
            $englishWords = ['Cotton', 'Silk', 'Wool', 'Leather', 'Denim', 'Linen', 'Polyester', 'Nylon', 'Rayon', 'Spandex', 'T-Shirt', 'Shirt', 'Pants', 'Jeans', 'Jacket', 'Coat', 'Dress', 'Skirt', 'Sweater', 'Hoodie', 'Sneakers', 'Boots', 'Sandals', 'Hat', 'Bag', 'Watch', 'Belt', 'Scarf', 'Gloves', 'Socks'];
            $nameWords = $faker->randomElements($englishWords, mt_rand(2,4));
            $name = implode(' ', $nameWords);
            return [
                'name' => ucfirst($name),
                'slug' => Str::slug($name) . '-' . Str::random(6),
                'description' => $faker->paragraphs(mt_rand(1,3), true),
                'images' => [
                    'https://picsum.photos/800/600',
                ],
            ];
        });
    }
}
