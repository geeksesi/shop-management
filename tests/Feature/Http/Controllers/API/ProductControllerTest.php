<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Jobs\SendProductDetailToTelegram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Support\Facades\Queue;
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

        $payload = Product::factory()->forCategory()->make()->toArray();
        $payload["social_message"] = "test";

        Queue::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->postJson(route('products.store'), $payload)->assertSuccessful();
        Queue::assertPushed(SendProductDetailToTelegram::class);
    }

    public function testUpdateProduct()
    {

        Queue::fake();
        $user = User::factory()->create();
        $product = Product::factory()->for($user, 'creator')->forCategory()->create();
        $payload = [
            'name' => 'update',
            'description' => 'tet description',
            'social_message' => 'test'
        ];
        $payload = array_merge($product->toArray(), $payload);
        $this->actingAs($user)->putJson(route('products.update', $product->id), $payload)->assertSuccessful();
        Queue::assertPushed(SendProductDetailToTelegram::class);
    }

    public function testFilterWithCategory()
    {
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
        Product::factory()->for($category)->forCreator()->create();
        Product::factory()->count(5)->for($category2)->forCreator()->create();

        $this->getJson(route("products.index", ['category' => $category->id]))
            ->assertSuccessful()
            ->assertJsonCount(1, 'data');

        $this->getJson(route("products.index", ['category' => $category2->id]))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    }
    public function testFilterWithPrice()
    {
        $priceMoreThan50Count = 0;
        $priceLessThan50Count = 0;
        $price50Count = 0;

        for ($i = 0; $i < 10; $i++) {
            $price = rand(0, 100);
            if ($price > 50)
                $priceMoreThan50Count++;
            else if ($price < 50)
                $priceLessThan50Count++;
            else
                $price50Count++;

            $product = Product::factory()->forCategory()->forCreator()->create();
            $product->update([
                "price" => $price
            ]);
        }


        $this->getJson(route("products.index", ['price' => 50, 'price_action' => 'more_than']))
            ->assertSuccessful()
            ->assertJsonCount($priceMoreThan50Count, 'data');


        $this->getJson(route("products.index", ['price' => 50, 'price_action' => 'less_than']))
            ->assertSuccessful()
            ->assertJsonCount($priceLessThan50Count, 'data');


        $this->getJson(route("products.index", ['price' => 50]))
            ->assertSuccessful()
            ->assertJsonCount($price50Count, 'data');
    }
}
