<?php

namespace Tests\Feature\Http\Controllers\API;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testTitleValidation()
    {
        $payload = [
            'title' => "",
            "description" => $this->faker->text(),
        ];
        $user = \App\Models\User::factory()->create();
        Auth::login($user);
        $this->postJson(route('category.store'), $payload)
            ->assertInvalid(['title']);
    }

}
