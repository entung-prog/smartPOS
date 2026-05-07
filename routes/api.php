<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Middleware\CheckAdminRole;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Smart POS
|--------------------------------------------------------------------------
*/

// Auth (public) — with rate limiting
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Protected routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::prefix('auth')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Admin-level endpoints
    Route::middleware(CheckAdminRole::class)->group(function () {
        Route::get('dashboard/stats', [DashboardController::class, 'stats']);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('transactions', TransactionController::class);
    });
});
