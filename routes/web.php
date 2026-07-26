<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pegawai\AuthController as PegawaiAuthController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\GangguanController as PegawaiGangguanController;
use App\Http\Controllers\Pegawai\KunjunganController as PegawaiKunjunganController;
use App\Http\Controllers\Pegawai\NotificationController as PegawaiNotificationController;
use App\Http\Controllers\Pegawai\PelangganController as PegawaiPelangganController;
use App\Http\Controllers\Pegawai\ProfileController as PegawaiProfileController;
use App\Http\Controllers\Pegawai\ScanBarcodeController;
use App\Http\Controllers\Pegawai\SettingsController as PegawaiSettingsController;
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

    // Google Login — user umum
    Route::get('/login/google', [SocialiteController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('login.google.callback');
});

// Login Pegawai (guest only)
Route::middleware('guest')->prefix('pegawai')->group(function () {
    Route::get('/login', [PegawaiAuthController::class, 'create'])->name('pegawai.login');
    Route::post('/login', [PegawaiAuthController::class, 'store'])->name('pegawai.login.store');

    // Google Login — pegawai
    Route::get('/login/google', [SocialiteController::class, 'redirectToGooglePegawai'])->name('pegawai.login.google');
    Route::get('/login/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('pegawai.login.google.callback');
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

    // Area Pegawai (auth)
    Route::prefix('pegawai')->name('pegawai.')->middleware('auth')->group(function () {
        // Logout — ga perlu cek role, biar pegawai bisa logout bebas
        Route::post('/logout', [PegawaiAuthController::class, 'destroy'])->name('logout');

        // Route yang butuh role pegawai/admin
        Route::middleware('role:pegawai,admin')->group(function () {
            Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
            Route::get('/scan-barcode', [ScanBarcodeController::class, 'create'])->name('scan-barcode');
            Route::post('/scan-barcode', [ScanBarcodeController::class, 'store'])->name('scan-barcode.store');

            // CRUD Pelanggan
            Route::get('/pelanggan', [PegawaiPelangganController::class, 'index'])->name('pelanggan.index');
            Route::get('/pelanggan/create', [PegawaiPelangganController::class, 'create'])->name('pelanggan.create');
            Route::post('/pelanggan', [PegawaiPelangganController::class, 'store'])->name('pelanggan.store');
            Route::get('/pelanggan/{pelanggan}', [PegawaiPelangganController::class, 'show'])->name('pelanggan.show');
            Route::get('/pelanggan/{pelanggan}/edit', [PegawaiPelangganController::class, 'edit'])->name('pelanggan.edit');
            Route::put('/pelanggan/{pelanggan}', [PegawaiPelangganController::class, 'update'])->name('pelanggan.update');
            Route::delete('/pelanggan/{pelanggan}', [PegawaiPelangganController::class, 'destroy'])->name('pelanggan.destroy');
            Route::get('/pelanggan/{pelanggan}/foto-rumah', [PegawaiPelangganController::class, 'foto'])->name('pelanggan.foto');
            Route::post('/pelanggan/{pelanggan}/kunjungan', [PegawaiPelangganController::class, 'catatKunjungan'])->name('pelanggan.kunjungan');
            Route::post('/pelanggan/{pelanggan}/konfirmasi-pembayaran', [PegawaiPelangganController::class, 'konfirmasiPembayaran'])->name('pelanggan.konfirmasi-pembayaran');

// Notifications (AJAX)
            Route::get('/notifications', [PegawaiNotificationController::class, 'index'])->name('notifications.index');
            Route::post('/notifications/{notification}/read', [PegawaiNotificationController::class, 'markRead'])->name('notifications.read');
            Route::post('/notifications/read-all', [PegawaiNotificationController::class, 'markAllRead'])->name('notifications.read-all');

            // Gangguan
            Route::get('/gangguan', [PegawaiGangguanController::class, 'index'])->name('gangguan.index');
            Route::get('/gangguan/create', [PegawaiGangguanController::class, 'create'])->name('gangguan.create');
            Route::post('/gangguan', [PegawaiGangguanController::class, 'store'])->name('gangguan.store');
            Route::get('/gangguan/{gangguan}', [PegawaiGangguanController::class, 'show'])->name('gangguan.show');
            Route::patch('/gangguan/{gangguan}/status', [PegawaiGangguanController::class, 'updateStatus'])->name('gangguan.update-status');
            Route::delete('/gangguan/{gangguan}', [PegawaiGangguanController::class, 'destroy'])->name('gangguan.destroy');

            // Kunjungan
            Route::get('/kunjungan', [PegawaiKunjunganController::class, 'index'])->name('kunjungan.index');
            Route::get('/kunjungan/{kunjungan}', [PegawaiKunjunganController::class, 'show'])->name('kunjungan.show');

            // Profile
            Route::get('/profile', [PegawaiProfileController::class, 'show'])->name('profile.show');
            Route::patch('/profile', [PegawaiProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/password', [PegawaiProfileController::class, 'updatePassword'])->name('profile.password');

            // Settings
            Route::get('/settings', [PegawaiSettingsController::class, 'index'])->name('settings');
            Route::patch('/settings', [PegawaiSettingsController::class, 'update'])->name('settings.update');
        });
    });
});

