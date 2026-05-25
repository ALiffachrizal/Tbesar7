<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

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

        // Laporan
    Route::middleware(['role:owner,manajer'])->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/',                [ReportController::class, 'index'])->name('index');
        Route::get('/transaksi',       [ReportController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/cetak', [ReportController::class, 'cetakTransaksi'])->name('cetakTransaksi');
        Route::get('/stok',            [ReportController::class, 'stok'])->name('stok');
        Route::get('/stok/cetak',      [ReportController::class, 'cetakStok'])->name('cetakStok');
    });

    // User Management
    Route::middleware(['role:owner'])->prefix('users')->name('users.')->group(function () {
        Route::get('/',            [UserController::class, 'index'])->name('index');
        Route::get('/tambah',      [UserController::class, 'create'])->name('create');
        Route::post('/tambah',     [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',      [UserController::class, 'update'])->name('update');
        Route::delete('/{user}',   [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';