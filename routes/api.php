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

/*---------------products------------*/
/*Route::middleware('auth:sanctum')->group(function (){*/
Route::prefix('v1')->group(function () {
    Route::apiResource('products', \App\Http\Controllers\API\v1\ProductController::class);
});
/*});*/
