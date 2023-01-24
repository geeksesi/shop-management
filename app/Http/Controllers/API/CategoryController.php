<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CategoryController\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function getCategoryInTree()
    {
        return Category::whereNull("parent_id")->with("children")->latest()->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "categories" => new CategoryCollection($this->getCategoryInTree()) ,
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
        return response()->json([
            "categories" => new CategoryCollection($this->getCategoryInTree()) ,
        ] , 201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category):jsonResponse
    {
        Category::update($request->validated());
        //$this->getCategoryInTree()->update($category,$request);
        return $this->update();
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
