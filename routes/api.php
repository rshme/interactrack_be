<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // // Admin only routes
    // Route::middleware('role:admin')->group(function () {
    //     Route::apiResource('users', UserController::class);
    //     // Add other admin-only routes
    // });

    // // Sales routes (accessible by both admin and sales)
    // Route::middleware('ability:sales-access')->group(function () {
    //     Route::apiResource('customers', CustomerController::class);
    //     Route::apiResource('interactions', InteractionController::class);
    //     Route::apiResource('sales', SaleController::class);
    // });
});
