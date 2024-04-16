<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
    
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('locations', LocationController::class)->except('index');
    Route::apiResource('products', ProductController::class);
    
    Route::apiResource('orders', OrderController::class)->only('index', 'show', 'store');  
    Route::prefix('orders')->group(function () {
        Route::get('get-order-items/{order}', [OrderController::class, 'getOrderItems']);
        Route::get('get-user-orders/{user}', [OrderController::class, 'getUserOrders']);
        Route::post('change-order-status/{order}', [OrderController::class, 'changeOrderStatus']);
    });
});
