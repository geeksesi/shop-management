<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryController\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::latest()->paginate(10);
        return response()->json([
            'categories' => new CategoryCollection($categories),
            "meta" => [
                "hasMore" => !($categories->lastPage() == $categories->currentPage()),
            ],
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $category = auth()->user()->categories()->create($request->validated());
        return response()->json([
            "message" => "Category created",
            "category" => new CategoryResource($category)
        ] , 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->cannot('update', $category))
        {
            return response()->json([
                "message" => "Access denied",
            ] , 403);
        }
        $category->update($request->validated());
        return response()->json([
            "message" => "Category updated",
            "category" => new CategoryResource($category)
        ] , 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->cannot('delete', $category)) {
            return response()->json([
                "message" => "Access denied",
            ], 403);
        }

        #TODO Check if category has product

        if ($category->children()->count())
        {
            return response()->json([
                "message" => "It has children",
            ], 501);
        }

        $category->delete();
        return response()->json([
            "message" => "Category deleted",
        ] , 201);
    }
}
