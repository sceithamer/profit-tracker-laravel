<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
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
                'Video Games',
                'Office Supplies',
                'Pet Supplies',
                'Baby & Kids',
                'Vintage Items'
            ])
        ];
    }
}
