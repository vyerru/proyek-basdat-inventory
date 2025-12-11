<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMasterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Rute Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Aplikasi (Dilindungi Autentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // --- Grup Master Data ---
    Route::prefix('master')->name('master.')->group(function () {

        // --- 1. BARANG ---
        Route::get('/barang', [DataMasterController::class, 'barang'])->name('barang');
        Route::get('/barang/create', [DataMasterController::class, 'createBarang'])->name('barang.create');
        Route::post('/barang', [DataMasterController::class, 'storeBarang'])->name('barang.store');
        Route::get('/barang/{id}/edit', [DataMasterController::class, 'editBarang'])->name('barang.edit'); // BARU
        Route::put('/barang/{id}', [DataMasterController::class, 'updateBarang'])->name('barang.update');   // BARU
        Route::delete('/barang/{id}', [DataMasterController::class, 'destroyBarang'])->name('barang.destroy');

        // --- 2. SATUAN ---
        Route::get('/satuan', [DataMasterController::class, 'satuan'])->name('satuan');
        Route::get('/satuan/create', [DataMasterController::class, 'createSatuan'])->name('satuan.create');
        Route::post('/satuan', [DataMasterController::class, 'storeSatuan'])->name('satuan.store');
        Route::get('/satuan/{id}/edit', [DataMasterController::class, 'editSatuan'])->name('satuan.edit'); // BARU
        Route::put('/satuan/{id}', [DataMasterController::class, 'updateSatuan'])->name('satuan.update');   // BARU
        Route::delete('/satuan/{id}', [DataMasterController::class, 'destroySatuan'])->name('satuan.destroy');

        // --- 3. VENDOR ---
        Route::get('/vendor', [DataMasterController::class, 'vendor'])->name('vendor');
        Route::get('/vendor/create', [DataMasterController::class, 'createVendor'])->name('vendor.create');
        Route::post('/vendor', [DataMasterController::class, 'storeVendor'])->name('vendor.store');
        Route::get('/vendor/{id}/edit', [DataMasterController::class, 'editVendor'])->name('vendor.edit'); // BARU
        Route::put('/vendor/{id}', [DataMasterController::class, 'updateVendor'])->name('vendor.update');   // BARU
        Route::delete('/vendor/{id}', [DataMasterController::class, 'destroyVendor'])->name('vendor.destroy');

        // --- 4. MARGIN ---
        Route::get('/margin', [DataMasterController::class, 'margin'])->name('margin');
        Route::get('/margin/create', [DataMasterController::class, 'createMargin'])->name('margin.create');
        Route::post('/margin', [DataMasterController::class, 'storeMargin'])->name('margin.store');
        Route::get('/margin/{id}/edit', [DataMasterController::class, 'editMargin'])->name('margin.edit'); // BARU
        Route::put('/margin/{id}', [DataMasterController::class, 'updateMargin'])->name('margin.update');   // BARU
        Route::delete('/margin/{id}', [DataMasterController::class, 'destroyMargin'])->name('margin.destroy');

        // --- 5. ROLE ---
        Route::get('/role', [DataMasterController::class, 'role'])->name('role');
        Route::get('/role/create', [DataMasterController::class, 'createRole'])->name('role.create');
        Route::post('/role', [DataMasterController::class, 'storeRole'])->name('role.store');
        Route::get('/role/{id}/edit', [DataMasterController::class, 'editRole'])->name('role.edit');     // BARU
        Route::put('/role/{id}', [DataMasterController::class, 'updateRole'])->name('role.update');       // BARU
        Route::delete('/role/{id}', [DataMasterController::class, 'destroyRole'])->name('role.destroy');

        // --- 6. USER (Read Only sesuai request sebelumnya) ---
        Route::get('/user', [DataMasterController::class, 'user'])->name('user');
    });

    // --- Grup Transaksi (Read-Only) ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // --- PENGADAAN ---
        // 1. Menu Utama
        Route::get('/pengadaan', [TransaksiController::class, 'pengadaan'])->name('pengadaan');

        // 2. Action: Buat Header & Redirect ke Proses
        Route::post('/pengadaan', [TransaksiController::class, 'storePengadaan'])->name('pengadaan.store');

        // 3. Halaman PROSES (Edit Mode: Input Barang)
        Route::get('/pengadaan/{id}/proses', [TransaksiController::class, 'prosesPengadaan'])->name('pengadaan.proses');

        // 4. Action: Tambah Item (Panggil SP)
        Route::post('/pengadaan/{id}/detail', [TransaksiController::class, 'storeDetailPengadaan'])->name('pengadaan.detail.store');

        // 5. Action: Hapus Item
        Route::delete('/pengadaan/detail/{id_detail}', [TransaksiController::class, 'destroyDetailPengadaan'])->name('pengadaan.detail.destroy');

        // 6. Action: SELESAI INPUT (Hanya Redirect)
        Route::post('/pengadaan/{id}/simpan', [TransaksiController::class, 'simpanPengadaan'])->name('pengadaan.simpan');

        // 7. Action: Hapus PO (Draft)
        Route::delete('/pengadaan/{id}', [TransaksiController::class, 'destroyPengadaan'])->name('pengadaan.destroy');

        // 8. Halaman READ-ONLY (Hanya Lihat Detail)
        Route::get('/pengadaan/{id}/detail', [TransaksiController::class, 'showPengadaan'])->name('pengadaan.show');
        // 8. Halaman READ-ONLY (Hanya Lihat)

        // 1. Read (List Header)
        Route::get('/penerimaan', [TransaksiController::class, 'penerimaan'])->name('penerimaan');
        Route::post('/penerimaan', [TransaksiController::class, 'storePenerimaan'])->name('penerimaan.store');

        // 1. Halaman PROSES (Edit/Input Mode)
        Route::get('/penerimaan/{id}/proses', [TransaksiController::class, 'prosesPenerimaan'])->name('penerimaan.proses');

        // 2. Halaman READ-ONLY (Detail Selesai)
        Route::get('/penerimaan/{id}/detail', [TransaksiController::class, 'showPenerimaan'])->name('penerimaan.show');

        // Action CRUD Detail
        Route::post('/penerimaan/{id}/detail', [TransaksiController::class, 'storeDetailPenerimaan'])->name('penerimaan.detail.store');
        Route::delete('/penerimaan/detail/{id_detail}', [TransaksiController::class, 'destroyPenerimaanDetail'])->name('penerimaan.detail.destroy'); // Method baru

        // Action Selesai & Hapus Header
        Route::post('/penerimaan/{id}/simpan', [TransaksiController::class, 'simpanPenerimaan'])->name('penerimaan.simpan'); // Method baru
        Route::delete('/penerimaan/{id}', [TransaksiController::class, 'destroyPenerimaan'])->name('penerimaan.destroy');
        Route::get('/penjualan', [TransaksiController::class, 'penjualan'])->name('penjualan');

        // Mulai Transaksi Baru
        Route::post('/penjualan', [TransaksiController::class, 'storePenjualan'])->name('penjualan.store');

        // Halaman PROSES (Edit Mode)
        Route::get('/penjualan/{id}/proses', [TransaksiController::class, 'prosesPenjualan'])->name('penjualan.proses');

        // Action Tambah/Hapus Item
        Route::post('/penjualan/{id}/detail', [TransaksiController::class, 'storeDetailPenjualan'])->name('penjualan.detail.store');
        Route::delete('/penjualan/detail/{id_detail}', [TransaksiController::class, 'destroyDetailPenjualan'])->name('penjualan.detail.destroy');

        // Halaman READ-ONLY (Detail Selesai)
        Route::get('/penjualan/{id}/detail', [TransaksiController::class, 'showPenjualan'])->name('penjualan.show');
    });

    // --- Grup Laporan (Read-Only) ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/kartu-stok', [LaporanController::class, 'kartuStok'])->name('kartu.stok');
    });
});