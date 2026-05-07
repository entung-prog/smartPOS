<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Smart POS
|--------------------------------------------------------------------------
*/

// Auth (public)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Dashboard stats
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);

    // Products
    Route::apiResource('products', ProductController::class);

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store']);
});
