<?php

namespace App\Http\Controllers\API;

use App\Actions\SendProductDetailToTelegramAction;
use App\Filters\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductController\StoreProductRequest;
use App\Http\Requests\API\ProductController\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private SendProductDetailToTelegramAction $action)
    {
    }

    public function index(Request $request,ProductFilter $filter)
    {
        $products = Product::filter($filter)->paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data["creator_id"] = auth()->user()->id;
        $relative_path = $request->file('thumbnail')
                                 ->storeAs('products_thumbnail', sprintf("%s.jpg", $data["name"]));
        $data["thumbnail"] = $relative_path;

        Product::create($data);
        $this->action->handle($data);
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
        $this->action->handle($data);
        return response('');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response('');
    }
}
