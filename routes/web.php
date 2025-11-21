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

        // --- Barang ---
        Route::get('/barang', [DataMasterController::class, 'barang'])->name('barang');
        Route::get('/barang/create', [DataMasterController::class, 'createBarang'])->name('barang.create');
        Route::post('/barang', [DataMasterController::class, 'storeBarang'])->name('barang.store');
        Route::delete('/barang/{id}', [DataMasterController::class, 'destroyBarang'])->name('barang.destroy');

        // --- Satuan ---
        Route::get('/satuan', [DataMasterController::class, 'satuan'])->name('satuan');
        Route::get('/satuan/create', [DataMasterController::class, 'createSatuan'])->name('satuan.create');
        Route::post('/satuan', [DataMasterController::class, 'storeSatuan'])->name('satuan.store');
        Route::delete('/satuan/{id}', [DataMasterController::class, 'destroySatuan'])->name('satuan.destroy');

        // --- (BARU) Vendor ---
        Route::get('/vendor', [DataMasterController::class, 'vendor'])->name('vendor');
        Route::get('/vendor/create', [DataMasterController::class, 'createVendor'])->name('vendor.create');
        Route::post('/vendor', [DataMasterController::class, 'storeVendor'])->name('vendor.store');
        Route::delete('/vendor/{id}', [DataMasterController::class, 'destroyVendor'])->name('vendor.destroy');

        // --- (BARU) Margin ---
        Route::get('/margin', [DataMasterController::class, 'margin'])->name('margin');
        Route::get('/margin/create', [DataMasterController::class, 'createMargin'])->name('margin.create');
        Route::post('/margin', [DataMasterController::class, 'storeMargin'])->name('margin.store');
        Route::delete('/margin/{id}', [DataMasterController::class, 'destroyMargin'])->name('margin.destroy');

        // --- User (Read-Only) ---
        Route::get('/user', [DataMasterController::class, 'user'])->name('user');

        // --- (BARU) Role ---
        Route::get('/role', [DataMasterController::class, 'role'])->name('role');
        Route::get('/role/create', [DataMasterController::class, 'createRole'])->name('role.create');
        Route::post('/role', [DataMasterController::class, 'storeRole'])->name('role.store');
        Route::delete('/role/{id}', [DataMasterController::class, 'destroyRole'])->name('role.destroy');
    });

    // --- Grup Transaksi (Read-Only) ---
    Route::prefix('transaksi')->name('transaksi.')->group(function () {

        // --- (BARU) Rute untuk Pengadaan ---

        // 1. READ (Halaman utama Pengadaan, memanggil SP Filter)
        Route::get('/pengadaan', [TransaksiController::class, 'pengadaan'])->name('pengadaan');

        // 3. CREATE (Menyimpan PO Header baru)
        Route::post('/pengadaan', [TransaksiController::class, 'storePengadaan'])->name('pengadaan.store');

        // 4. READ (Menampilkan 1 PO dan detailnya)
        Route::get('/pengadaan/{id}', [TransaksiController::class, 'showPengadaan'])->name('pengadaan.show');

        // 5. CREATE (Menyimpan item detail baru ke PO yang ada)
        Route::post('/pengadaan/{id}/detail', [TransaksiController::class, 'storeDetailPengadaan'])->name('pengadaan.detail.store');

        // 6. DELETE (Menghapus 1 item detail dari PO)
        Route::delete('/pengadaan/detail/{id_detail}', [TransaksiController::class, 'destroyDetailPengadaan'])->name('pengadaan.detail.destroy');

        // 7. DELETE (Menghapus 1 PO beserta semua detailnya)
        Route::delete('/pengadaan/{id}', [TransaksiController::class, 'destroyPengadaan'])->name('pengadaan.destroy');

        // --- Rute Penerimaan ---
        Route::get('/penerimaan', [TransaksiController::class, 'penerimaan'])->name('penerimaan');
    });

    // --- Grup Laporan (Read-Only) ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/penjualan', [LaporanController::class, 'penjualan'])->name('penjualan');
        Route::get('/kartu-stok', [LaporanController::class, 'kartuStok'])->name('kartu.stok');
    });
});