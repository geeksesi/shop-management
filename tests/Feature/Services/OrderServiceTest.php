<?php

namespace Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;
    private array $products_quantity_before;
    private array $products_quantity_after;

    private array $order_products_id_and_quantity;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory();
        $num_of_products = 3;
        $products = Product::factory()->for($user, 'creator')->forCategory()->count($num_of_products);
        $this->order = Order::factory()->forUser()->has($products)->create();
        $this->get_quantity_before();
        $this->get_products_id_quantity();
    }

    public function get_quantity_before(){
        $products = $this->get_order_products();
        $products->each(function ($item){
            $this->products_quantity_before[$item->id] = $item->quantity;
        });
    }
    public function get_quantity_after(){
        $products = $this->get_order_products();
        $products->each(function ($item){
            $this->products_quantity_after[$item->id] = $item->quantity;
        });
    }

    public function get_order_products(){
        return $this->order->products()->get();
    }

    public function get_products_id_quantity(){
        $order_pivot_data = $this->order->product_orders()->get();
        $this->order_products_id_and_quantity = [];
        foreach ($order_pivot_data as $data){
            $this->order_products_id_and_quantity[$data->product_id] = $data->quantity;
        }
    }

    public function testAddStock()
    {

        OrderService::addStock($this->order);

        $this->get_quantity_after();
        $products = $this->get_order_products();

        $products->each(function ($item){
            $this->assertEquals($this->products_quantity_after[$item->id]
                                , $this->products_quantity_before[$item->id]
                                        + $this->order_products_id_and_quantity[$item->id]);
        });


    }

    public function testSubtractStock()
    {

        OrderService::subtractStock($this->order);

        $this->get_quantity_after();
        $products = $this->get_order_products();

        $products->each(function ($item){
            $this->assertEquals($this->products_quantity_after[$item->id]
                , $this->products_quantity_before[$item->id]
                - $this->order_products_id_and_quantity[$item->id]);
        });
    }


}
