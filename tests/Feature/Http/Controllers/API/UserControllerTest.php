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

    public function testAsGuestItCanLoginWithInValidUsernameFormat(){
        $login_info = [
            'username' => "",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertInvalid(['username']);
    }

    public function testAsGuestItCanLoginWithInValidPasswordFormat(){
        $login_info = [
            'password' => "",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertInvalid(['password']);
    }
    public function testAsGuestItCanLoginWithCorrectUserNameAndPassword(){

        $this->registerAUser();

        $login_info = [
            'username' => "geeksesi",
            'password' => "passowrd",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertSuccessful();
    }

    public function testAsGuestItCanNotLoginWithUserNameThatDoesNotExist(){

        $this->registerAUser();

        $login_info = [
            'username' => "Abbas",
            'password' => "passowrd",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertNotFound();
    }

    public  function testAsGuestItCanNotLoinWithCorrectUsernameAndWrongPassword(){

        $this->registerAUser();

        $login_info = [
            'username' => "geeksesi",
            'password' => "12345678",
        ];

        $this->postJson(route('user.login'), $login_info)
            ->assertUnauthorized();
    }

    public function testAsUserRegisteredItCanGetInfoOnAPIUser(){
        $payload = [
            'name' => "Mohammad Javad",
            'family' => "Ghasemy",
            'email' => "geeksesi@gmail.com",
            'password' => "passowrd",
            'username' => "geeksesi",
            'phone_number' => "09100101543",
        ];
        $info = $this->postJson(route('user.register'), $payload);

        $token = $info['data']['token'];

        $this->getJson('api/user', ['Authorization' => 'Bearer ' . $token])
            ->assertSuccessful();

    }

    public function testAsUserLoggedItCanGetInfoOnAPIUser(){

        $this->registerAUser();

        $login_info = [
            'username' => "geeksesi",
            'password' => "passowrd",
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

    public function registerAUser(){
        $payload = [
            'name' => "Mohammad Javad",
            'family' => "Ghasemy",
            'email' => "geeksesi@gmail.com",
            'password' => "passowrd",
            'username' => "geeksesi",
            'phone_number' => "09100101543",
        ];

        $this->postJson(route('user.register'), $payload);
    }

}
