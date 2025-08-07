<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Platform;
use App\Models\User;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = Platform::all();
        $users = User::all();
        
        if ($platforms->isEmpty() || $users->isEmpty()) {
            $this->command->error('No platforms or users found. Please seed platforms and users first.');
            return;
        }

        $items = [
            'Underwear', 'Socks', 'T-Shirt', 'Jeans', 'Shoes', 'Watch', 'Sunglasses',
            'Backpack', 'Wallet', 'Phone Case', 'Headphones', 'Laptop', 'Books',
            'Coffee Mug', 'Picture Frame', 'Lamp', 'Pillow', 'Blanket', 'Towel',
            'Jacket', 'Hat', 'Belt', 'Tie', 'Scarf', 'Gloves', 'Ring', 'Necklace',
            'Earrings', 'Bracelet', 'Perfume', 'Cologne', 'Lotion', 'Shampoo',
            'Toothbrush', 'Mirror', 'Comb', 'Razor', 'Candle', 'Vase', 'Clock',
            'Calendar', 'Notebook', 'Pen', 'Pencil', 'Stapler', 'Scissors',
            'Flashlight', 'Batteries', 'Charger', 'Cable', 'Mouse', 'Keyboard'
        ];

        $categories = [
            'Clothing', 'Electronics', 'Accessories', 'Home & Garden', 'Books & Media',
            'Health & Beauty', 'Sporting Goods', 'Toys & Games', 'Office Supplies'
        ];

        for ($i = 0; $i < 50; $i++) {
            $salePrice = rand(1000, 50000) / 100; // Random price between $10.00 and $500.00
            $fees = round($salePrice * (rand(5, 15) / 100), 2); // 5-15% fees
            $shippingCost = rand(0, 2000) / 100; // Random shipping $0.00 to $20.00
            
            Sale::create([
                'storage_unit_id' => 1,
                'user_id' => $users->random()->id,
                'platform_id' => $platforms->random()->id,
                'item_name' => "Kevin's " . $items[array_rand($items)],
                'item_category' => $categories[array_rand($categories)],
                'sale_price' => $salePrice,
                'fees' => $fees,
                'shipping_cost' => $shippingCost,
                'sale_date' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        $this->command->info('Created 50 sales for Storage Unit ID 1 with Kevin\'s prefix');
    }
}
