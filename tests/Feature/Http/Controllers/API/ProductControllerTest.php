<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Jobs\SendProductDetailToTelegram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;


    public function testListProduct()
    {
        $response = $this->get('api/products');

        $response->assertStatus(200);
    }
    public function testGetProductById()
    {
        $creator = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->for($creator, 'creator')->for($category)->create();

        $this->get('/api/products/' . $product->id)
            ->assertStatus(200);
    }

    public function testDeleteProduct()
    {
        $product = Product::factory()->forCategory()->forCreator()->create();

        $this->actingAs($product->creator)->deleteJson(route('products.destroy', $product->id))->assertSuccessful();

        $this->assertDatabaseMissing(app(Product::class)->getTable(), ['id' => $product->id]);
    }

    public function testCreateProduct()
    {
        Bus::fake();
        $user = User::factory()->create();
        $payload = Product::factory()->forCategory()->make()->toArray();
        $payload["photo_url"] = "test";
        $payload["social_message"] = "test";
        $this->actingAs($user)->postJson(route('products.store'), $payload)->assertSuccessful();
        Bus::assertDispatched(SendProductDetailToTelegram::class);
    }

    public function testUpdateProduct()
    {
        Bus::fake();
        $user = User::factory()->create();
        $product = Product::factory()->for($user, 'creator')->forCategory()->create();

        $payload = [
            'name' => 'update',
            'description' => 'tet description',
            'photo_url' => 'test',
            'social_message' => 'test'
        ];

        $payload = array_merge($product->toArray(), $payload);
        $this->actingAs($user)->putJson(route('products.update', $product->id), $payload)->assertSuccessful();
        Bus::assertDispatched(SendProductDetailToTelegram::class);
    }
}
