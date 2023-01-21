<?php

namespace Tests\Feature\Policy;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testCategoryHasChildrenShouldNotBeDeleted()
    {
        $category = Category::factory()->create();
        $payload = [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            "parent_id" => $category->id ,
        ];
        Category::create($payload);
        $user = \App\Models\User::factory()->create();
        $this->assertTrue($user->cannot('delete' , $category));

    }
    public function testCategoryHasNotChildrenShouldBeDeleted()
    {
        $payload = [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            "parent_id" => null ,
        ];
        $category = Category::create($payload);
        $user = \App\Models\User::factory()->create();
        $this->assertTrue($user->can('delete' , $category));

    }
}

