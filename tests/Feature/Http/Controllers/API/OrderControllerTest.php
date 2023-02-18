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
        $this->products = Product::factory()->for($this->user, 'creator')->forCategory()->count(3);
        $this->order = Order::factory()->has($this->products)->create();
        $this->get_product_ids();

    }

    public function setUpZeroStock(){
        $this->products = Product::factory()->zeroStock()->for($this->user, 'creator')->forCategory()->count(3);
        $this->order = Order::factory()->has($this->products)->create();
        $this->get_product_ids();
    }

    public function get_product_ids(){
        $this->products_id_quantity = Product_order::select('product_id', 'quantity')
                                        ->where('order_id', $this->order->id)
                                        ->get()->toArray();
    }

    public function testStoreOrderSuccessful()
    {

        $payload = [
            "payment_status" => OrderPaymentStatusEnum::PENDING,
            "product_ids" => $this->products_id_quantity
        ];


        $this->actingAs($this->user)->postJson(route('orders.store'), $payload)->assertSuccessful();
    }

    public function testStoreOrderFail(){

        $this->setUpZeroStock();

        $payload = [
            "payment_status" => OrderPaymentStatusEnum::PENDING,
            "product_ids" => $this->products_id_quantity
        ];


        $this->actingAs($this->user)->postJson(route('orders.store'), $payload)->assertStatus(400);
    }

    public function testCheckOrderIsValid(){

        $this->actingAs($this->user)->getJson(route('orders.check', $this->order))->assertSuccessful();
    }

    public function testCheckOrderIsNotValid(){

        $this->setUpZeroStock();

        $this->actingAs($this->user)->getJson(route('orders.check', $this->order))->assertStatus(400);
    }
}
