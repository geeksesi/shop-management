<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductController\StoreProductRequest;
use App\Http\Requests\API\ProductController\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\SendProductDetailToTelegram;
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
        $data = $request->validated();
        $data["creator_id"] = auth()->user()->id;
        Product::create($data);

        SendProductDetailToTelegram::dispatchIf(isset($data["social_message"])
                                                , $data["photo_url"], $data["name"] , $data["social_message"]);
        return response('', 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);

        SendProductDetailToTelegram::dispatchIf(isset($data["social_message"])
                                                , $data["photo_url"], $data["name"] ,$data["social_message"]);
        return response('');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response('');
    }
}
