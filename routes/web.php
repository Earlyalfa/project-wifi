<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pegawai\AuthController as PegawaiAuthController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\PelangganController as PegawaiPelangganController;
use App\Http\Controllers\Pegawai\ScanBarcodeController;
use Illuminate\Support\Facades\Route;

// Halaman Selamat Datang
Route::get('/', function () {
    return view('welcome');
})->middleware('guest')->name('home');

// Login/Register User Umum (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Login Pegawai (guest only)
Route::middleware('guest')->prefix('pegawai')->group(function () {
    Route::get('/login', [PegawaiAuthController::class, 'create'])->name('pegawai.login');
    Route::post('/login', [PegawaiAuthController::class, 'store'])->name('pegawai.login.store');
});

// Auth (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Dashboard utama - redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/kelola-pengguna', function () {
            return 'Halaman kelola pengguna (khusus admin)';
        })->name('admin.users');
    });

    // Area Pegawai (auth + role pegawai/admin)
    Route::prefix('pegawai')->name('pegawai.')->middleware('role:pegawai,admin')->group(function () {
        Route::post('/logout', [PegawaiAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
        Route::get('/scan-barcode', [ScanBarcodeController::class, 'create'])->name('scan-barcode');
        Route::post('/scan-barcode', [ScanBarcodeController::class, 'store'])->name('scan-barcode.store');
        Route::get('/pelanggan', [PegawaiPelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/{pelanggan}', [PegawaiPelangganController::class, 'show'])->name('pelanggan.show');
        Route::get('/pelanggan/{pelanggan}/foto-rumah', [PegawaiPelangganController::class, 'foto'])->name('pelanggan.foto');
        Route::post('/pelanggan/{pelanggan}/kunjungan', [PegawaiPelangganController::class, 'catatKunjungan'])->name('pelanggan.kunjungan');
        Route::post('/pelanggan/{pelanggan}/konfirmasi-pembayaran', [PegawaiPelangganController::class, 'konfirmasiPembayaran'])->name('pelanggan.konfirmasi-pembayaran');
    });
});

