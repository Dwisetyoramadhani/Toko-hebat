<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
});
Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
});;
Route::middleware('auth:sanctum', 'admin')->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
});
