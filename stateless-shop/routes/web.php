<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CartController::class, 'index'])->name('shop.index');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::patch('/admin/users/{user}/role', [RoleController::class, 'update'])
    ->middleware(['auth', 'can:manage-roles'])
    ->name('admin.users.role.update');

Route::post('/cart/add', [CartController::class, 'add'])
    ->middleware(['auth', 'can:use-cart'])
    ->name('cart.add');

Route::post('/cart/update', [CartController::class, 'update'])
    ->middleware(['auth', 'can:use-cart'])
    ->name('cart.update');
