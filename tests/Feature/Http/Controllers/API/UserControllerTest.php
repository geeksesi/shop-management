<?php

namespace Tests\Feature\Http\Controllers\API;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

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

    public function testAsGuestItCanNotLoginWithInValidUsernameFormat(){
        $login_info = [
            'username' => "",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertInvalid(['username']);
    }

    public function testAsGuestItCanNotLoginWithInValidPasswordFormat(){
        $login_info = [
            'password' => "",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertInvalid(['password']);
    }
    public function testAsGuestItCanLoginWithCorrectUserNameAndPassword(){

        $user = \App\Models\User::factory()->create();

        $login_info = [
            'username' => $user->username,
            'password' => 'password'
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertSuccessful();
    }

    public function testAsGuestItCanNotLoginWithUserNameThatDoesNotExist(){
        $user = \App\Models\User::factory()->create();

        $login_info = [
            'username' => "Abbas",
            'password' => 'password'
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertUnauthorized();
    }

    public  function testAsGuestItCanNotLoginWithCorrectUsernameAndWrongPassword(){
        $user = \App\Models\User::factory()->create();

        $login_info = [
            'username' => $user->username,
            'password' => "12345678",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertUnauthorized();
    }

    public function testAsUserLoggedItCanGetInfoOnAPIUser(){
        $user = \App\Models\User::factory()->create();

        $login_info = [
            'username' => $user->username,
            'password' => 'password'
        ];

        $info = $this->postJson(route('user.login'), $login_info);

        $token = $info['data']['token'];

        $this->getJson('api/user', ['Authorization' => 'Bearer ' . $token])
            ->assertSuccessful();

    }

    public function testAsGuestItCanNotGetInfoOnAPIUserWithWrongToken(){

        $token = " ";

        $this->getJson('api/user', ['Authorization' => 'Bearer ' . $token])
            ->assertUnauthorized();

    }

}
