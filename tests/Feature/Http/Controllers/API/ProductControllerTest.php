<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Enums\ProductTypeEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $user = User::factory()->create();
        $payload = Product::factory()->forCategory()->make()->toArray();
        $this->actingAs($user)->postJson(route('products.store'), $payload)->assertSuccessful();
    }

    public function testUpdateProduct()
    {
        $user = User::factory()->create();
        $product = Product::factory()->for($user, 'creator')->forCategory()->create();

        $payload = [
            'name' => 'update',
            'description' => 'tet description',
        ];
        $payload = array_merge($product->toArray(), $payload);
        $this->actingAs($user)->putJson(route('products.update', $product->id), $payload)->assertSuccessful();
    }

    public function testFilterWithCategory()
    {
        $category = Category::factory()->create();
        $category2 = Category::factory()->create();
        Product::factory()->for($category)->forCreator()->create();
        Product::factory()->count(5)->for($category2)->forCreator()->create();

        $this->getJson(route("products.index" , ['category' => $category->id]))
            ->assertSuccessful()
            ->assertJsonCount(1 , 'data')
        ;

        $this->getJson(route("products.index", ['category' => $category2->id]) )
            ->assertSuccessful()
            ->assertJsonCount(5 , 'data');

    }
    public function testFilterWithPrice()
    {
        $priceMoreThan50Count = 0;
        $priceLessThan50Count = 0;
        $price50Count = 0;

        for ($i = 0; $i < 10 ;$i++)
        {
            $price = rand(0 , 100);
            if ($price > 50)
                $priceMoreThan50Count++;
            else if($price < 50)
                $priceLessThan50Count++;
            else
                $price50Count++;

            $product = Product::factory()->forCategory()->forCreator()->create();
            $product->update([
                "price" => $price
            ]);
        }


        $this->getJson(route("products.index" , ['price' => 50 , 'price_action' => 'more_than']))
            ->dump()
            ->assertSuccessful()
            ->assertJsonCount($priceMoreThan50Count , 'data');


        $this->getJson(route("products.index" , ['price' => 50 , 'price_action' => 'less_than']))
            ->dump()
            ->assertSuccessful()
            ->assertJsonCount($priceLessThan50Count , 'data');


        $this->getJson(route("products.index" , ['price' => 50 ]))
            ->dump()
            ->assertSuccessful()
            ->assertJsonCount($price50Count , 'data');
    }

}
