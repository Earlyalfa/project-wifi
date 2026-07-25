<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = today();

        // --- Kartu statistik ---
        $totalPelanggan = Pelanggan::count();
        $belumBayarCount = Pembayaran::where('status', 'belum_bayar')->count();
        $pembayaranHariIni = Pembayaran::where('status', 'lunas')
            ->whereDate('dibayar_at', $today)
            ->count();
        $gangguanHariIni = 0; // placeholder untuk modul gangguan

        // --- Scan terakhir ---
        $scanTerakhir = Kunjungan::with('pelanggan')
            ->latest('waktu_kunjungan')
            ->take(5)
            ->get();

        // --- Daftar belum bayar (untuk tabel) ---
        $daftarBelumBayar = Pembayaran::with('pelanggan')
            ->where('status', 'belum_bayar')
            ->latest()
            ->take(5)
            ->get();

        // --- Aktivitas terbaru ---
        $aktivitasTerbaru = collect();

        // Ambil pembayaran lunas terbaru
        $pembayaranLunas = Pembayaran::with('pelanggan')
            ->where('status', 'lunas')
            ->latest('dibayar_at')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'type' => 'pembayaran',
                    'icon' => 'credit-card',
                    'icon_bg' => 'bg-emerald-100',
                    'icon_color' => 'text-emerald-600',
                    'title' => 'Pembayaran dari ' . ($item->pelanggan->nama ?? 'Pelanggan'),
                    'desc' => 'Periode ' . $item->periode . ' - Rp ' . number_format($item->jumlah, 0, ',', '.'),
                    'time' => $item->dibayar_at ? $item->dibayar_at->diffForHumans() : '-',
                ];
            });

        // Ambil kunjungan terbaru (aktivitas scan)
        $kunjunganAktivitas = Kunjungan::with('pelanggan')
            ->latest('waktu_kunjungan')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $status = $item->status === 'tagihan_dibayar' ? 'Tagihan dibayar' : 'Kunjungan - Belum bayar';
                return (object) [
                    'type' => 'kunjungan',
                    'icon' => 'user-check',
                    'icon_bg' => 'bg-blue-100',
                    'icon_color' => 'text-blue-600',
                    'title' => ($item->pelanggan->nama ?? 'Pelanggan') . ' - ' . ($item->pelanggan->kode ?? ''),
                    'desc' => $status,
                    'time' => $item->waktu_kunjungan->diffForHumans(),
                ];
            });

        $aktivitasTerbaru = $pembayaranLunas->concat($kunjunganAktivitas)->sortByDesc('time')->take(5);

        return view('pegawai.dashboard', compact(
            'totalPelanggan',
            'belumBayarCount',
            'pembayaranHariIni',
            'gangguanHariIni',
            'scanTerakhir',
            'daftarBelumBayar',
            'aktivitasTerbaru',
        ));
    }
}
