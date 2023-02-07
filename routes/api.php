<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/register', [App\Http\Controllers\API\UserController::class, 'register'])->name('user.register');

Route::post('user/login', [App\Http\Controllers\API\UserController::class, 'login'])->name('user.login')
    ->middleware('throttle:login');


Route::middleware(['auth:sanctum','auth:admin-api'])->group(function () {

    /*---------------category------------*/
    Route::apiResource("/categories", \App\Http\Controllers\API\CategoryController::class)
        ->except("index")
        ->middleware('auth:sanctum');

    /*---------------products------------*/
    Route::apiResource('products', \App\Http\Controllers\API\ProductController::class)->only([
        'store', 'update', 'destroy'
    ])->name('products.store', 'products.update', 'products.destroy');
});

/*---------------category------------*/
Route::get("/categories", [\App\Http\Controllers\API\CategoryController::class, "index"])->name("categories.index");

/*---------------products------------*/
Route::apiResource('products', \App\Http\Controllers\API\ProductController::class)->only([
    'index', 'show'
]);
