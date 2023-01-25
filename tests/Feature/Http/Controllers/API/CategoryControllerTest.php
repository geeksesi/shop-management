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
            'title' => $this->faker->words(3, true),
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
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->text(),
        ];
        $this->postJson(route('categories.store'),   $payload)
            ->assertStatus(201);
    }


    public function testAsGuestItShouldBeAbleToGetCategoryList()
    {
        for ($i = 0; $i < 2; $i++) {
            $parent = Category::factory()->create();

            $c1 = Category::factory()->count(2)->create(['parent_id' => $parent->id]);
            foreach ($c1 as $cat) {
                Category::factory()->times(1)->create(['parent_id' => $cat->id]);
            }
        }



        $categories = Category::whereNull("parent_id")->latest()->get();
        $response = $this->getJson(route('categories.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    '*' => [
                        'children' => [
                            '*' => [
                                'children' => [
                                    '*' => [
                                        'children' => []
                                    ]

                                ]

                            ]
                        ]
                    ]
                ]
            ]);

        $data = $response->json('data');

        $this->assertEmpty($data[0]['children'][0]['children'][0]['children']);
        $this->assertNotEmpty($data[0]['children'][0]['children']);
    }
}
