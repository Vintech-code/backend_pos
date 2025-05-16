<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;


//Login
Route::post('login', [AuthController::class, 'login']);

//Products
Route::prefix('products')->group(function () {
    Route::get('/history', [ProductController::class, 'history']);
    Route::post('/checkout', [ProductController::class, 'checkout']);
    Route::apiResource('/', ProductController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
