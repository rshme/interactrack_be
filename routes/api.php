<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\InteractionController;
use App\Http\Controllers\API\SaleController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        // Route::apiResource('users', UserController::class);
        // Add other admin-only routes
    });

    // Sales routes (accessible by both admin and sales)
    Route::middleware(['auth:sanctum', 'ability:sales-access'])->group(function () {
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('interaction', InteractionController::class);
        Route::apiResource('sale', SaleController::class);
    });
});
