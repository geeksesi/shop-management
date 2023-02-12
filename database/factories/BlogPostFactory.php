<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\blog_post>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "title" => fake()->words(3 , true),
            "body" => fake()->text(),
            "seo_description" => fake()->text(),
            "thumbnail" => fake()->image(),
            "tags" => fake()->words(3 , true),
        ];
    }
}
