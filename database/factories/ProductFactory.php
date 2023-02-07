<?php

namespace Database\Factories;

use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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

    protected $model = Product::class;
    public function definition()
    {
        Storage::fake('photos');
        return [
            'name' => fake()->name,
            'description' => fake()->text,
            'quantity' => 10,
            'price' => 2000,
            'type' => ProductTypeEnum::DIGITAL,
            'thumbnail' => UploadedFile::fake()->create('image.jpg')
        ];
    }
}
