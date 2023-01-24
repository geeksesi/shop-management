<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    public function testListProduct()
    {
        $response = $this->get('api/products');

        $response->assertStatus(200);
    }

    public function testLoggedInCanStoreNewProduct()
    {
        $user = \App\Models\User::factory()->create();
        $category = \App\Models\Category::factory()->create();

        $login_info = [
            'username' => $user->username,
            'password' => 'password'
        ];

        $info = $this->postJson(route('user.login'), $login_info);

        $token = $info['data']['token'];
        $payload = $this->post('/products', [
            'name' => 'Apple',
            'type' => 'Fruit',
            'price' =>50000,
            'category_id' => $category->id,
            'creator' => $user->id,
            'Authorization' => 'Bearer ' . $token
        ]);
        $payload->assertSessionHasNoErrors();
        $payload->assertRedirect('/products');
        $this->assertCount(1, Product::all());
        $this->assertDatabaseHas('products', ['name' => 'Apple', 'type' => 'finished', 'price' => 50000]);
    }
}
