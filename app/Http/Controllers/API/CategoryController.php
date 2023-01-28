<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CategoryController\CategoryRequest;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use stdClass;

class CategoryController extends Controller
{

    private $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = $this->service->getTree();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        $categories = $this->service->getTree();
        return response(CategoryResource::collection($categories), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        $categories = $this->service->getTree();
        return CategoryResource::collection($categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response();
    }
}
