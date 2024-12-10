<?php

use Illuminate\Support\Facades\Route;
// Mengimpor file controller yang diperlukan
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OrderController;

// Route untuk pengguna yang belum login (guest)
Route::middleware('isGuest')->group(function () {
    Route::get('/', [UsersController::class, 'showLogin'])->name('login.auth');
    Route::post('/login', [UsersController::class, 'loginAuth'])->name('login.proses');
});

// Route untuk pengguna yang sudah login
Route::middleware('isLogin')->group(function () {
    Route::get('/landing', [LandingPageController::class, 'index'])->name('landing_page');
    Route::get('/logout', [UsersController::class, 'logout'])->name('logout');

    // Rute untuk pengguna dengan role admin
    Route::middleware('isAdmin')->group(function () {
        // Rute untuk data obat
        Route::get('/order', [OrderController::class, 'indexAdmin'])->name('pembelian.admin');
        Route::get('/order/export-excel', [OrderController::class, 'exportExcel'])->name('pembelian.admin.export');
        Route::prefix('/obat')->name('obat.')->group(function () {
            Route::get('/data', [MedicineController::class, 'index'])->name('data');
            Route::get('/tambah', [MedicineController::class, 'create'])->name('tambah');
            Route::post('/tambah', [MedicineController::class, 'store'])->name('tambah.formulir');
            Route::delete('/hapus/{id}', [MedicineController::class, 'destroy'])->name('hapus');
            Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
            Route::patch('/edit/{id}', [MedicineController::class, 'update'])->name('edit.formulir');
            Route::patch('/edit/stock/{id}', [MedicineController::class, 'updateStock'])->name('edit.stok');
        });

        Route::get('/user', [UsersController::class, 'index'])->name('user.admin');
        Route::get('/user/export-excel', [UsersController::class, 'indexExcel'])->name('user.admin.export');
        // Rute untuk akun
        Route::prefix('/akun')->name('akun.')->group(function () {
            Route::get('/create', [UsersController::class, 'create'])->name('create');
            Route::post('/store', [UsersController::class, 'store'])->name('store');
            Route::get('/data', [UsersController::class, 'index'])->name('home');
            Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('edit');
            Route::patch('/{id}', [UsersController::class, 'update'])->name('update');
            Route::delete('/{id}', [UsersController::class, 'destroy'])->name('delete');
        });
    });

    // Rute untuk pengguna dengan role kasir
    Route::middleware('isKasir')->group(function () {
        Route::prefix('/pembelian')->name('kasir.')->group(function () {
            Route::get('/order', [OrderController::class, 'index'])->name('order');
            Route::get('/formulir', [OrderController::class, 'create'])->name('formulir');
            Route::post('/store', [OrderController::class, 'store'])->name('order.store');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/download-pdf/{id}', [OrderController::class, 'downloadPDF'])->name('download_pdf');
        });
    });
});
