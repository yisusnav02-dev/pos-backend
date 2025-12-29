<?php

use Illuminate\Support\Facades\Route;
use Modules\Comanda\Http\Controllers\ComandaController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Gestión de productos
    Route::get('/comanda', [ComandaController::class, 'index']);
    Route::post('/comanda', [ComandaController::class, 'store']);
    Route::get('/comanda/{table}/{comanda}', [ComandaController::class, 'show']);
    /* Route::put('/products/{product}', [ComandaController::class, 'update']);
    Route::delete('/products/{product}', [ComandaController::class, 'destroy']);
    Route::put('/products/{product}/availability', [ComandaController::class, 'updateAvailability']);*/
    
  
});