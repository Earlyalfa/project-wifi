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
use App\Http\Controllers\Pegawai\PembayaranController as PegawaiPembayaranController;
use App\Http\Controllers\Pegawai\ProfileController as PegawaiProfileController;
use App\Http\Controllers\Pegawai\ScanBarcodeController;
use App\Http\Controllers\Pegawai\SettingsController as PegawaiSettingsController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PaketController as AdminPaketController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Pelanggan\DashboardController as PelangganDashboardController;
use App\Http\Controllers\Pelanggan\PembayaranController as PelangganPembayaranController;
use App\Http\Controllers\Pelanggan\PengaduanController as PelangganPengaduanController;
use App\Http\Controllers\Pelanggan\ProfileController as PelangganProfileController;
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
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
Route::get('/settings', function () { return view('admin.settings'); })->name('settings');

        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Admin Laporan
        Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/export-pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [AdminLaporanController::class, 'exportExcel'])->name('laporan.export-excel');

        // Admin Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');

        Route::get('/kelola-pengguna', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/kelola-pengguna/create', [AdminDashboardController::class, 'create'])->name('users.create');
        Route::post('/kelola-pengguna', [AdminDashboardController::class, 'store'])->name('users.store');
        Route::get('/kelola-pengguna/{user}', [AdminDashboardController::class, 'show'])->name('users.show');
        Route::get('/kelola-pengguna/{user}/edit', [AdminDashboardController::class, 'edit'])->name('users.edit');
        Route::put('/kelola-pengguna/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
        Route::patch('/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->name('users.role');
        Route::patch('/users/{user}/status', [AdminDashboardController::class, 'updateStatus'])->name('users.status');

        // Pembayaran
        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/{pembayaran}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('/pembayaran/{pembayaran}/verifikasi', [AdminPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

        // Pengaduan Gangguan
        Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
        Route::patch('/pengaduan/{pengaduan}/status', [AdminPengaduanController::class, 'updateStatus'])->name('pengaduan.update-status');

        // Paket WiFi CRUD
        Route::get('/paket', [AdminPaketController::class, 'index'])->name('paket.index');
        Route::get('/paket/create', [AdminPaketController::class, 'create'])->name('paket.create');
        Route::post('/paket', [AdminPaketController::class, 'store'])->name('paket.store');
        Route::get('/paket/{paket}/edit', [AdminPaketController::class, 'edit'])->name('paket.edit');
        Route::put('/paket/{paket}', [AdminPaketController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{paket}', [AdminPaketController::class, 'destroy'])->name('paket.destroy');
    });

    // Area Pelanggan
    Route::prefix('pelanggan')->name('pelanggan.')->middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

        Route::middleware('role:pelanggan')->group(function () {
            Route::get('/dashboard', [PelangganDashboardController::class, 'index'])->name('dashboard');
            Route::get('/pembayaran', [PelangganPembayaranController::class, 'index'])->name('pembayaran');
            Route::post('/pembayaran', [PelangganPembayaranController::class, 'store'])->name('pembayaran.store');
            Route::get('/pengaduan', [PelangganPengaduanController::class, 'index'])->name('pengaduan');
            Route::post('/pengaduan', [PelangganPengaduanController::class, 'store'])->name('pengaduan.store');
            Route::get('/pengaduan/{pengaduan}', [PelangganPengaduanController::class, 'show'])->name('pengaduan.show');

            // Profile
            Route::get('/profile', [PelangganProfileController::class, 'show'])->name('profile');
            Route::patch('/profile', [PelangganProfileController::class, 'update'])->name('profile.update');
            Route::patch('/profile/password', [PelangganProfileController::class, 'updatePassword'])->name('profile.password');

            // Notifications (AJAX)
            Route::get('/notifications', [PelangganPengaduanController::class, 'notifications'])->name('notifications');
            Route::post('/notifications/{id}/read', [PelangganPengaduanController::class, 'markRead'])->name('notifications.read');
            Route::post('/notifications/read-all', [PelangganPengaduanController::class, 'markAllRead'])->name('notifications.read-all');
        });
    });

    // Area Pegawai (auth)
    Route::prefix('pegawai')->name('pegawai.')->middleware('auth')->group(function () {
        // Logout — ga perlu cek role, biar pegawai bisa logout bebas
        Route::post('/logout', [PegawaiAuthController::class, 'destroy'])->name('logout');

        // Route yang butuh role pegawai/admin
        Route::middleware('role:pegawai,admin')->group(function () {
            Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
            Route::get('/scan-barcode', [ScanBarcodeController::class, 'create'])->name('scan-barcode');
            Route::post('/scan-barcode/cari', [ScanBarcodeController::class, 'cariPelanggan'])->name('scan-barcode.cari');
            Route::post('/scan-barcode/konfirmasi', [ScanBarcodeController::class, 'konfirmasiBayar'])->name('scan-barcode.konfirmasi');

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

            // Pembayaran
            Route::get('/pembayaran', [PegawaiPembayaranController::class, 'index'])->name('pembayaran.index');
            Route::get('/pembayaran/{pembayaran}', [PegawaiPembayaranController::class, 'show'])->name('pembayaran.show');
            Route::post('/pembayaran/{pembayaran}/verifikasi', [PegawaiPembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

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
