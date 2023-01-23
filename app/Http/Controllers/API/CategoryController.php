<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CategoryController\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::whereNull("parent_id")->latest()->get();
        return response()->json([
            "categories" => new CategoryCollection($categories) ,
        ] , 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        Category::create($request->validated());
        $categories = Category::whereNull("parent_id")->latest()->get();
        return response()->json([
            "categories" => new CategoryCollection($categories) ,
        ] , 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
