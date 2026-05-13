<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;

// =================== Public Routes ===================
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Products - Public
Route::get('/products',          [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/{id}',     [ProductController::class, 'show']);

// Categories - Public
Route::get('/categories', [CategoryController::class, 'index']);

// =================== Auth Required ===================
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::post('/auth/logout',  [AuthController::class, 'logout']);
    Route::get('/auth/me',       [AuthController::class, 'me']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);

    // Orders (Customer)
    Route::get('/orders',           [OrderController::class, 'index']);
    Route::post('/orders',          [OrderController::class, 'store']);
    Route::get('/orders/{id}',      [OrderController::class, 'show']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

    // =================== Admin Only ===================
    Route::middleware('admin')->prefix('admin')->group(function () {
        // Products
        Route::post('/products',       [ProductController::class, 'store']);
        Route::put('/products/{id}',   [ProductController::class, 'update']);
        Route::delete('/products/{id}',[ProductController::class, 'destroy']);

        // Categories
        Route::post('/categories',        [CategoryController::class, 'store']);
        Route::put('/categories/{id}',    [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Orders
        Route::get('/orders',                       [OrderController::class, 'adminIndex']);
        Route::put('/orders/{id}/status',           [OrderController::class, 'updateStatus']);
    });
});
