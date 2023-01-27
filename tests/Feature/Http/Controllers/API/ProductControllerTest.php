<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Models\User;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
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
    public function testGetProductById()
    {
        $user = \App\Models\User::factory()->create();
        $category = \App\Models\Category::factory()->create();
        $product = \App\Models\Product::factory()->create();
       $product_id = Product::get()->random()->id;
         $this->get('/api/products/' . $product_id)
            ->assertStatus(200);
    }

    public function testDeleteProduct()
    {
        $category = \App\Models\Category::factory()->create();
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('api_token');
        $product=Product::create([
            'name' => 'test',
            'description'=> 'tet description',
            'quantity'=>10,
            'creator' => $user->id,
            'category_id' => $category->id,
            'price' => 2000,
            'type' => ProductTypeEnum::AVAILABLE
        ]);
        $product_id = $product->id;
        $this->withHeaders( ['Authorization' => 'Bearer '. $token->plainTextToken , 'Accept'=>'application/json','content_type'=>'application/json'])
            ->json('DELETE', 'api/products/' . $product_id)->assertStatus(200);
    }

    public function testCreateProduct()
    {
        $category = \App\Models\Category::factory()->create();
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('api_token');
        $product=[
            'name' => 'test store',
            'description'=> 'tet description',
            'quantity'=>10,
            'creator' => $user->id,
            'category_id' => $category->id,
            'price' => 2000,
            'type' => ProductTypeEnum::AVAILABLE
        ];
        $this->withHeaders( ['Authorization' => 'Bearer '. $token->plainTextToken , 'Accept'=>'application/json','content_type'=>'application/json'])
            ->json('POST', 'api/products',$product)->assertStatus(201);
    }

    public function testUpdateProduct()
    {
        $category = \App\Models\Category::factory()->create();
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('api_token');
        $product=Product::create([
            'name' => 'test update',
            'description'=> 'tet description',
            'quantity'=>10,
            'creator' => $user->id,
            'category_id' => $category->id,
            'price' => 2000,
            'type' => ProductTypeEnum::AVAILABLE
        ]);
        $payload=[
            'name' => 'update',
            'description'=> 'tet description',
            'quantity'=>10,
            'creator' => $user->id,
            'category_id' => $category->id,
            'price' => 1000,
            'type' => ProductTypeEnum::FINISHED
        ];
        $this->withHeaders( ['Authorization' => 'Bearer '. $token->plainTextToken , 'Accept'=>'application/json','content_type'=>'application/json'])
            ->json('PUT','api/products/'. $product->id,$payload)->assertStatus(204);
    }
}
