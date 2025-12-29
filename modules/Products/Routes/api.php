<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Gestión de categorías
    Route::get('/products/categorias', [ProductController::class, 'getCategories']);
    Route::post('/products/categorias', [ProductController::class, 'storeCategory']);

    // Gestión de productos
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::put('/products/{product}/availability', [ProductController::class, 'updateAvailability']);
    
  
});