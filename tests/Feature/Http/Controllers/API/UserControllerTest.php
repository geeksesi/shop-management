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
            'password' => $this->faker->password(8),
            'username' => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'expires_at',
                ]
            ]);

        unset($payload['password']);
        $this->assertDatabaseHas('users', $payload);
    }

    public function testAsGuestItShouldNotBeAbleToRegisterWithIncorrectInputs()
    {
        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' => $this->faker->name(),
            'password' => $this->faker->password(8),
            'username' => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertInvalid(['email']);
    }

    public function testAsGuestItCanNotRegisterWithDuplicateUsername()
    {
        $user = \App\Models\User::factory()->create();

        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(8),
            'username' => $user->username,
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertInvalid(['username']);
    }

    public function testAsGuestItCanNotRegisterWithDuplicateEmail()
    {
        $user = \App\Models\User::factory()->create();

        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' =>  $user->email,
            'password' => $this->faker->password(8),
            'username' => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertInvalid(['email']);
    }

    public function testAsGuestItCanNotRegisterWithDuplicatePhoneNumber()
    {
        $user = \App\Models\User::factory()->create();

        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(8),
            'username' => $this->faker->userName(),
            'phone_number' => $user->phone_number,
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertInvalid(['phone_number']);
    }

    public function testAsGuestOnRegisterPasswordShouldBeStoredEncrypted()
    {
        $payload = [
            'name' => $this->faker->name(),
            'family' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(8),
            'username' => $this->faker->userName(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $this->postJson(route('user.register'), $payload)
            ->assertSuccessful();

        $this->assertDatabaseMissing('users', [
            'password' => $payload['password'],
        ]);
    }
}
