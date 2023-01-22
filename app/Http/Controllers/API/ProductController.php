<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductController\StoreProductRequest;
use App\Http\Requests\API\ProductController\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        /*
         * to do
         * category_id => category resource
         * creator => user resource
         * */
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'creator' => $request->creator,
            'category_id' => $request->category_id,
            'price' => $request->price,

        ]);
        return response()->json([
            "message" => 'product create successful',
            "status" => 201
        ],201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'creator' => $request->creator,
            'category_id' => $request->category_id,
            'price' => $request->price,

        ]);
        return response()->json([
            "message" => 'product update successful',
            "status" => 204
        ],204);

    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            "message" => 'product delete successful',
            "status" => 200
        ]);
    }
}
