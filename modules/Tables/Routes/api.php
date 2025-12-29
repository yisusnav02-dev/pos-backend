<?php

use Illuminate\Support\Facades\Route;
use Modules\Tables\Http\Controllers\TableController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Gestión de mesas
    Route::get('/tables', [TableController::class, 'index']);
    Route::post('/tables', [TableController::class, 'store']);
    Route::get('/tables/{table}', [TableController::class, 'show']);
    Route::put('/tables/{table}', [TableController::class, 'update']);
    Route::delete('/tables/{table}', [TableController::class, 'destroy']);
    
    // Estado y disponibilidad
    Route::put('/tables/{table}/status', [TableController::class, 'updateStatus']);
    Route::get('/tables/available', [TableController::class, 'available']);
    
    // Estadísticas
    Route::get('/tables/stats', [TableController::class, 'getStats']);
});