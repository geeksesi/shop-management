<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testAsGuestItShouldNotBeAbleToStoreCategory()
    {
        $payload = [
            'title' => $this->faker->words(3,true),
            'description' => $this->faker->text(),
        ];
        $this->postJson(route('categories.store'), $payload)
            ->assertStatus(401);
    }

    public function testAsUserLoggedItShouldBeAbleToStoreCategory()
    {
        $user = \App\Models\User::factory()->create();
        $login_info = [
            'username' => $user->username,
            'password' => 'password'
        ];
        $this->postJson(route('user.login'), $login_info);
        $payload = [
            'title' => $this->faker->words(3,true),
            'description' => $this->faker->text(),
        ];
        $this->postJson(route('categories.store'),   $payload)
            ->assertStatus(201);
    }

    public function testAsGuestItShouldBeAbleToGetCategoryList()
    {
        for ($i=0; $i < 5 ; $i++)
        {
           \App\Models\Category::factory()->create();
        }
        $categories = Category::whereNull("parent_id")->latest()->get();
        $this->getJson(route('categories.index'))
            ->assertStatus(200)
            /*->assertJsonFragment([
                "categories" => new CategoryCollection($categories)
            ])*/
        ;
    }
}
