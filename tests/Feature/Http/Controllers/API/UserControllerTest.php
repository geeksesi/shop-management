<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testAsGuestItShouldBeAbleToRegisterWithCorrectInputs()
    {
        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
            'username' => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertSuccessful()
            ->dump()
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expires_at',
                ]
            ]);

        unset($payload['password']);
        $this->assertDatabaseHas('users', $payload);
    }
}
