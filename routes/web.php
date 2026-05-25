<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard — redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Owner
    Route::middleware(['role:owner'])->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');
    });

    // Manajer & Supervisor
    Route::middleware(['role:manajer,supervisor'])->prefix('toko')->name('toko.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'manajer'])->name('dashboard');
    });

    // Transaksi
    Route::middleware(['role:kasir,supervisor,manajer'])->prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/',        [TransactionController::class, 'index'])->name('index');
        Route::get('/baru',    [TransactionController::class, 'create'])->name('create');
        Route::post('/baru',   [TransactionController::class, 'store'])->name('store');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    });

    // Stok Gudang
    Route::middleware(['role:gudang,supervisor,manajer'])->prefix('stok')->name('stok.')->group(function () {
        Route::get('/',            [StockController::class, 'index'])->name('index');
        Route::get('/masuk',       [StockController::class, 'masuk'])->name('masuk');
        Route::post('/masuk',      [StockController::class, 'storeMasuk'])->name('storeMasuk');
        Route::get('/riwayat',     [StockController::class, 'riwayat'])->name('riwayat');
    });

    // Laporan (dikerjakan Fatimah)
    Route::middleware(['role:owner,manajer'])->prefix('laporan')->name('laporan.')->group(function () {
        // Fatimah isi di sini
    });

    // User Management (dikerjakan Fatimah)
    Route::middleware(['role:owner'])->prefix('users')->name('users.')->group(function () {
        // Fatimah isi di sini
    });
});

require __DIR__.'/auth.php';