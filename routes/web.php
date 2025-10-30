<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\AuthController;    // <-- Tambahkan ini jika belum
use App\Http\Controllers\HomeController;     // <-- Tambahkan ini jika belum
use App\Http\Controllers\ProcedureController; // <-- Tambahkan ini

// Rute utama (/) akan menampilkan halaman link ke semua view
Route::get('/', function () {
    return view('welcome_views');
})->name('home');

// --- Rute Login/Logout (Tetap diperlukan) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// --- Routes di dalam Middleware Auth ---
Route::middleware(['auth'])->group(function () {

    // Route Dashboard (HomeController akan mengecek role)
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard'); // Ubah nama jadi dashboard?


    // --- Routes untuk MENAMPILKAN Views Database ---

    // Bisa diakses Admin & Super Admin (Hanya Data Aktif)
    Route::middleware(['role:Admin,Super Admin'])->group(function () {
        Route::get('/view/barang-aktif', [ViewController::class, 'viewBarangActive'])->name('view.barang.active');
        Route::get('/view/satuan-aktif', [ViewController::class, 'viewSatuanActive'])->name('view.satuan.active');
        Route::get('/view/vendor-aktif', [ViewController::class, 'viewVendorActive'])->name('view.vendor.active');
        Route::get('/view/margin-penjualan-aktif', [ViewController::class, 'viewMarginPenjualanActive'])->name('view.margin_penjualan.active');
    });

    // Hanya bisa diakses Super Admin (Semua Data / Info User)
    Route::middleware(['role:Super Admin'])->group(function () {
        Route::get('/view/barang-semua', [ViewController::class, 'viewBarangAll'])->name('view.barang.all');
        Route::get('/view/satuan-semua', [ViewController::class, 'viewSatuanAll'])->name('view.satuan.all');
        Route::get('/view/vendor-semua', [ViewController::class, 'viewVendorAll'])->name('view.vendor.all');
        Route::get('/view/margin-penjualan-semua', [ViewController::class, 'viewMarginPenjualanAll'])->name('view.margin_penjualan.all');
        Route::get('/view/user-role', [ViewController::class, 'viewUserRole'])->name('view.user_role');
        Route::get('/view/role-semua', [ViewController::class, 'viewRoleAll'])->name('view.role.all');
        // Tambahkan view lain khusus Super Admin di sini jika ada

        // --- Routes untuk MEMANGGIL Stored Procedures (Hanya Super Admin) ---
        Route::prefix('procedures')->name('procedures.')->group(function () {
            // Barang
            Route::get('/barang/create', [ProcedureController::class, 'createBarangForm'])->name('barang.create');
            Route::post('/barang', [ProcedureController::class, 'storeBarang'])->name('barang.store');
            // Pengadaan
            Route::get('/pengadaan/create', [ProcedureController::class, 'createPengadaanForm'])->name('pengadaan.create');
            Route::post('/pengadaan', [ProcedureController::class, 'storePengadaan'])->name('pengadaan.store');
            // Penjualan
            Route::get('/penjualan/create', [ProcedureController::class, 'createPenjualanForm'])->name('penjualan.create');
            Route::post('/penjualan', [ProcedureController::class, 'storePenjualan'])->name('penjualan.store');
            // Penerimaan
            Route::get('/penerimaan/create', [ProcedureController::class, 'createPenerimaanForm'])->name('penerimaan.create');
            Route::post('/penerimaan', [ProcedureController::class, 'storePenerimaan'])->name('penerimaan.store');
        });
    });

}); // Akhir middleware auth