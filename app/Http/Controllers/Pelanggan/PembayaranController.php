<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari data pelanggan berdasarkan user_id
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        // Default values if no pelanggan data
        if (!$pelanggan) {
            return view('pelanggan.pembayaran', [
                'tagihan'          => null,
                'riwayatPembayaran' => collect([]),
                'kodeUnik'         => rand(100, 999),
                'totalPembayaran'  => 0,
                'pelanggan'        => null,
            ]);
        }

        // Ambil tagihan yang belum dibayar (terbaru)
        $tagihan = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'belum_bayar')
            ->latest()
            ->first();

        // Generate kode unik (acak 3 digit)
        $kodeUnik = rand(100, 999);

        // Total pembayaran = nominal tagihan + kode unik
        $totalPembayaran = $tagihan ? ($tagihan->jumlah + $kodeUnik) : 0;

        // Riwayat pembayaran (semua data)
        $riwayatPembayaran = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        return view('pelanggan.pembayaran', [
            'tagihan'          => $tagihan,
            'riwayatPembayaran' => $riwayatPembayaran,
            'kodeUnik'         => $kodeUnik,
            'totalPembayaran'  => $totalPembayaran,
            'pelanggan'        => $pelanggan,
        ]);
    }
}
