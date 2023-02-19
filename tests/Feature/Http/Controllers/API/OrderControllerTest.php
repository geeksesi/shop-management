<?php

namespace Http\Controllers\API;

use App\Enums\OrderPaymentStatusEnum;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $products;
    private User $user;
    private Order $order;
    private  $products_id_quantity;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function createNormalStock(){
        $this->products = Product::factory()->for($this->user, 'creator')->forCategory()->count(3)->create();
        $this->create_products_id_quantity();
    }
    public function createZeroStock(){
        $this->products = Product::factory()->zeroStock()->for($this->user, 'creator')->forCategory()->count(3)->create();
        $this->create_products_id_quantity();
    }

    public function createNormalOrders(){
        $this->products = Product::factory()->for($this->user, 'creator')->forCategory()->count(3);
        $this->order = Order::factory()->has($this->products)->create();
        $this->get_products_id_quantity();
    }
    public function createOrdersWithZeroStock(){
        $this->products = Product::factory()->zeroStock()->for($this->user, 'creator')->forCategory()->count(3);
        $this->order = Order::factory()->has($this->products)->create();
        $this->get_products_id_quantity();
    }

    public function create_products_id_quantity(){
        foreach ($this->products as $product){
            $this->products_id_quantity[] = ['product_id' => $product->id, 'quantity' => 2];
        }
    }

    public function get_products_id_quantity(){
        $this->products_id_quantity = Product_order::select('product_id', 'quantity')
            ->where('order_id', $this->order->id)
            ->get()->toArray();
    }


    public function testStoreOrderSuccessful()
    {
        $this->createNormalStock();

        $payload = [
            "payment_status" => OrderPaymentStatusEnum::PENDING,
            "product_ids" => $this->products_id_quantity
        ];


        $this->actingAs($this->user)->postJson(route('orders.store'), $payload)->assertSuccessful();
    }

    public function testStoreOrderFail(){

        $this->createZeroStock();

        $payload = [
            "payment_status" => OrderPaymentStatusEnum::PENDING,
            "product_ids" => $this->products_id_quantity
        ];


        $this->actingAs($this->user)->postJson(route('orders.store'), $payload)
            ->assertStatus(400);
    }

    public function testCheckOrderIsValid(){

        $this->createNormalOrders();
        $this->actingAs($this->user)->getJson(route('orders.check', $this->order))->assertSuccessful();
    }

    public function testCheckOrderIsNotValid(){

        $this->createOrdersWithZeroStock();
        $this->actingAs($this->user)->getJson(route('orders.check', $this->order))->assertStatus(400);
    }
}
