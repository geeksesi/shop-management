<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use WithFaker;

    public function testCategoryHasChildrenShouldNotBeDeleted()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;
        $payload = [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            "parent_id" => $categoryId ,
        ];
        Category::create($payload);
        $category->delete();

        #TODO I dont know what I must Do
        if (is_null(Category::find($categoryId))) {

        }




    }
    public function testCategoryHasNotChildrenShouldBeDeleted()
    {
        $payload = [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            "parent_id" => null ,
        ];
        $category = Category::create($payload);
        $categoryId = $category->id;
        $category->delete();
        #TODO I dont know what I must Do
        if (is_null(Category::find($categoryId))) {

        }

    }
}
