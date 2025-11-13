<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // adjust if needed

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Clothing', 'slug' => 'clothing'],
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
