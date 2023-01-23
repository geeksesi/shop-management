<?php

namespace Tests\Feature\Http\Controllers\API;

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
}
