<?php

namespace App\Services;

use App\Jobs\SoftDeleteOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_order;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public static function store($data, $products_id_quantity){

        DB::beginTransaction();
        try{
            $order = Order::create($data);

            foreach ($products_id_quantity as $product_id_quantity){
                $order->products()->attach($product_id_quantity['product_id']
                    , ['quantity'=> $product_id_quantity['quantity']]);
            }

            if(!OrderService::subtractStock($order)){
                DB::rollback();
                return response()->json(['Not enough quantity of product available'], 400);
            }

            SoftDeleteOrder::dispatch($order, $products_id_quantity)->delay(now()->addSeconds(15));
            DB::commit();
            return response('Order Stored.', 200);

        } catch (Exception $e){
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
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
                                                ->lockForUpdate()
                                                ->get()->toArray();


        $products = $order->products;
        $could_subtract = True;
        for ($key = 0; $key < count($products); $key++){
            if ($products[$key]->quantity < $products_id_quantity[$key]['quantity']){
                $could_subtract = false;
                break;
            }
            $products[$key]->quantity -= $products_id_quantity[$key]['quantity'];
            $products[$key]->save();
        }

        return $could_subtract;
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
