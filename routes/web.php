<?php

use App\Http\Controllers\KasirDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckKasirRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Role-based redirect after Breeze login
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return redirect('/admin');
    }

    return redirect('/kasir');
})->middleware(['auth', 'verified'])->name('dashboard');

// Kasir routes (Breeze layout, role: kasir)
Route::middleware(['auth', 'verified', CheckKasirRole::class])->prefix('kasir')->group(function () {
    Route::get('/', [KasirDashboardController::class, 'index'])->name('kasir.index');
    Route::get('/riwayat', [KasirDashboardController::class, 'riwayat'])->name('kasir.riwayat');
});

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
