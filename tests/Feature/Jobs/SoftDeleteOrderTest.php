<?php

namespace Jobs;

use App\Jobs\SoftDeleteOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftDeleteOrderTest extends TestCase
{
    use RefreshDatabase;

    public function testWhenPaymentIsPendingItSoftDeletes()
    {
        $user = User::factory();
        $product = Product::factory()->for($user, 'creator')->forCategory()->count(1);
        $order = Order::factory()->forUser()->has($product)->create();

        SoftDeleteOrder::dispatch($order);


        $this->assertSoftDeleted('orders', $order->toArray());


    }

    public function testWhenPaymentIsSuccessfulItSoftDeletes()
    {
        $user = User::factory();
        $product = Product::factory()->for($user, 'creator')->forCategory()->count(1);
        $order = Order::factory()->forUser()->has($product)->create(['payment_status'=>'successful']);

        SoftDeleteOrder::dispatch($order);


        $this->assertNotSoftDeleted('orders', $order->toArray());


    }

}
