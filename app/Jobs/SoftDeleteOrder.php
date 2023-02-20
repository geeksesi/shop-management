<?php

namespace App\Jobs;

use App\Enums\OrderPaymentStatusEnum;
use App\Models\Order;
use App\Models\product_order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SoftDeleteOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Order $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->order->payment_status == OrderPaymentStatusEnum::SUCCESSFUL->value){
            return;
        }

        $this->order->product_orders()->delete();
        product_order::where("order_id", $this->order->id)->delete();
        Order::where("id", $this->order->id)->delete();
        OrderService::addStock($this->order);

    }
}
