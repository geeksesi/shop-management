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

/*---------------products------------*/
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('products', \App\Http\Controllers\API\ProductController::class)->only([
        'create', 'store','update','destroy'
    ]);

});
//  Route::apiResource('', \App\Http\Controllers\API\v1\ProductController::class);
 Route::resource('products', \App\Http\Controllers\API\ProductController::class)->only([
    'index', 'show'
]);
