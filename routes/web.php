<?php

use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtkController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halamanutama', [SiteController::class, 'index'])->name('halamanutama');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest'); // Hanya tamu
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth'); // Hanya yang sudah login

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/atk', [AtkController::class, 'index'])->name('atk.index');
    // Tambahkan route lain yang butuh login di sini
});