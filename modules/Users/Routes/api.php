<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UserController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/roles', [UserController::class, 'getRoles']);
    
    // Rutas con parámetros
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::put('/users/status/{user}', [UserController::class, 'updateStatus']);
});