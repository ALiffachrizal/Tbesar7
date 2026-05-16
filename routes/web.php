<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

    // Kasir (dikerjakan Ibnu)
    Route::middleware(['role:kasir,supervisor,manajer'])->prefix('transaksi')->name('transaksi.')->group(function () {
        // Ibnu isi di sini
    });

    // Gudang (dikerjakan Ibnu)
    Route::middleware(['role:gudang,supervisor,manajer'])->prefix('stok')->name('stok.')->group(function () {
        // Ibnu isi di sini
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