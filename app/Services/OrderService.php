<?php

namespace App\Services;

use App\Jobs\SoftDeleteOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_order;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public static function store($data, $products_id_quantity){

        DB::transaction(function () use ($data, $products_id_quantity) {
            $order = Order::create($data);
            foreach ($products_id_quantity as $product_id_quantity){
                $order->products()->attach($product_id_quantity['product_id']
                    , ['quantity'=> $product_id_quantity['quantity']]);
            }
            OrderService::subtractStock($order); ##move to observer
            SoftDeleteOrder::dispatch($order, $products_id_quantity)->delay(now()->addSeconds(15));
        });

    }
    public static function addStock(Order $order){



        $products_id_quantity = Product_order::select('product_id', 'quantity')
                                    ->where('order_id', $order->id)
                                    ->get()->toArray();

        $products = $order->products();
        $products->each(function ($item, $key) use ($products_id_quantity){
            $item->quantity += $products_id_quantity[$key]['quantity'];
            $item->save();
        });
    }

    public static function subtractStock(Order $order){



        $products_id_quantity = Product_order::select('product_id', 'quantity')
            ->where('order_id', $order->id)
            ->get()->toArray();

        $products = $order->products();
        $products->each(function ($item, $key) use ($products_id_quantity){
            $item->quantity -= $products_id_quantity[$key]['quantity'];
            $item->save();
        });


    }


    public static function calculate_price(array $products_id_quantity){
        $price = 0;
        foreach ($products_id_quantity as $product_id_quantity){
            $product = Product::find($product_id_quantity['product_id']);
            $price += $product->price;
        }
        return $price;
    }

    public static function all_products_has_stock(array $products_id_quantity){
        $Stock_is_available = true;
        foreach ($products_id_quantity as $product_id_quantity){
            $product = DB::table('products')
                        ->where('id', '=', $product_id_quantity['product_id'])
                        ->where('quantity' , '=' ,0)->first();

            if(!empty($product)){
                $Stock_is_available = false;
            }
        }
        return $Stock_is_available;
    }





}
