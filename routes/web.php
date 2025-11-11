<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// Halaman Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// --- Grup Master Data ---
Route::prefix('master')->name('master.')->group(function () {
    Route::get('/barang', [DataMasterController::class, 'barang'])->name('barang');
    Route::get('/satuan', [DataMasterController::class, 'satuan'])->name('satuan');
    Route::get('/vendor', [DataMasterController::class, 'vendor'])->name('vendor');
    Route::get('/margin', [DataMasterController::class, 'margin'])->name('margin');
    Route::get('/user', [DataMasterController::class, 'user'])->name('user');
});

// --- Grup Transaksi ---
Route::prefix('transaksi')->name('transaksi.')->group(function () {
    Route::get('/pengadaan', [TransaksiController::class, 'pengadaan'])->name('pengadaan');
    Route::get('/penerimaan', [TransaksiController::class, 'penerimaan'])->name('penerimaan');
});

// --- Grup Laporan ---
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
    Route::get('/kartu-stok', [LaporanController::class, 'kartuStok'])->name('kartu.stok');
});