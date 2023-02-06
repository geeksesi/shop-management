<?php

namespace App\Http\Controllers\API;

use App\Filters\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductController\StoreProductRequest;
use App\Http\Requests\API\ProductController\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request,ProductFilter $filter)
    {
        #$products = Product::paginate();
        $products = Product::filter($filter)->paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data["creator_id"] = auth()->user()->id;
        Product::create($data);
        return response('', 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return response('');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response('');
    }
}
