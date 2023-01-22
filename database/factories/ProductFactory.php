<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model=Product::class;
    public function definition()
    {
        return [
            'name' => fake()->name,
            'description'=> fake()->text,
            'quantity'=>10,
            'creator' => 1,
            'category_id' => 1
        ];
    }
}
