<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Satu pintu masuk /dashboard untuk semua role.
     * Kalau role user di database diubah (lewat phpMyAdmin) jadi "admin"
     * atau "pegawai", user otomatis melihat dashboard yang sesuai.
     * Kalau tidak diubah (tetap "pelanggan"), tetap masuk dashboard pelanggan.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin'   => view('dashboard.admin', ['user' => $user]),
            'pegawai' => redirect()->route('pegawai.dashboard'),
            default   => view('dashboard.pelanggan', ['user' => $user]),
        };
    }
}
