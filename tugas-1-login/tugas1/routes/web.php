<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Login/dashboard
Route::get('/', function () {
    return redirect()->route('login');
});
// Rute untuk guest user
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// Rute untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});