<?php

namespace App\Http\Controllers\API;

use App\Actions\SendProductDetailToTelegramAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductController\StoreProductRequest;
use App\Http\Requests\API\ProductController\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\SendProductDetailToTelegram;
use App\Models\Product;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request, SendProductDetailToTelegramAction $action)
    {
        $data = $request->validated();
        $data["creator_id"] = auth()->user()->id;
        Product::create($data);
        $action->handle($data);
        return response('', 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product, SendProductDetailToTelegramAction $action)
    {
        $data = $request->validated();
        $product->update($data);
        $action->handle($data);
        return response('');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response('');
    }
}
