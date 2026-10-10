<?php

use App\Http\Controllers\KeranjangController;
use Illuminate\Support\Facades\Route;

Route::get('/', [KeranjangController::class, 'index'])->name('daftar_barang');
Route::get('/tugas2/index', [KeranjangController::class, 'index']);

Route::get('/keranjang', [KeranjangController::class, 'keranjang'])->name('keranjang');
Route::get('/tugas2/keranjang', [KeranjangController::class, 'keranjang']);

Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::post('/keranjang/tambah-qty/{id}', [KeranjangController::class, 'tambahQty'])->name('keranjang.tambah_qty');
Route::post('/keranjang/kurang-qty/{id}', [KeranjangController::class, 'kurangQty'])->name('keranjang.kurang');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapusItem'])->name('keranjang.hapus');
Route::post('/keranjang/kosongkan', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');
