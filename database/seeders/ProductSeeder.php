<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ensure some categories exist
        $this->call(CategorySeeder::class);

        // Create 50 products
        Product::factory()->count(50)->create();

        // Optionally create a few explicit products for demo
        if (Category::where('slug', 'clothing')->exists()) {
            $cat = Category::where('slug', 'clothing')->first();
            Product::factory()->create([
                'name' => 'Demo Cotton T-Shirt',
                'slug' => 'demo-cotton-t-shirt',
                'category_id' => $cat->id,
                'price' => 19.99,
                'sale_price' => 14.99,
                'is_featured' => true,
            ]);
        }
    }
}
