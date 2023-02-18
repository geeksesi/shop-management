<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\OrderController\OrderRequest;
use App\Jobs\SoftDeleteOrder;
use App\Models\Order;
use App\Models\Product_order;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(OrderRequest $request){
        $data = $request->validated();
        $products_id_quantity = $data['product_ids'];
        unset($data['product_ids']);

        $data['user_id'] = auth()->user()->id;
        $data['price'] = OrderService::calculate_price($products_id_quantity);

        if (!OrderService::all_products_has_stock($products_id_quantity)){
            return response('A Product is not available!', 400);
        }

        OrderService::store($data, $products_id_quantity);


        return response(status: 200);
        #release the database.
    }

    public function check(Order $order){
        $products_id_quantity = Product_order::select('product_id', 'quantity')
                                 ->where('order_id', $order->id)
                                 ->get()->toArray();
        if(!OrderService::all_products_has_stock($products_id_quantity)){
            return response('A Product is not available anymore!',400);
        }
        return response('Order is validated!',200);
    }

}
