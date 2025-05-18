<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;


//Login
Route::post('login', [AuthController::class, 'login']);

//Products
Route::prefix('products')->group(function () {
    Route::get('/history', [ProductController::class, 'history']);
    Route::post('/checkout', [ProductController::class, 'checkout']);
    Route::put('/{product}', [ProductController::class, 'update']); 
    Route::get('/report', [ProductController::class, 'report']); 
    Route::apiResource('/', ProductController::class)->except(['update']); 
    Route::put('/{id}/visibility', [ProductController::class, 'updateVisibility']);
    Route::get('/products/hidden', [ProductController::class, 'hidden']);
});

//Dashboard
Route::get('/dashboard/overview', [DashboardController::class, 'overview']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
