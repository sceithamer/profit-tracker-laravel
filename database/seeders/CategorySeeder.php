<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Home & Garden',
            'Books',
            'Furniture',
            'Jewelry',
            'Collectibles',
            'Tools',
            'Sports & Outdoors',
            'Kitchen & Dining',
            'Toys & Games',
            'Automotive',
            'Art & Crafts',
            'Beauty & Health',
            'Musical Instruments',
            'Uncategorized'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }
}
