<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Kunjungan;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = today();

        // --- Kartu statistik (4 card) ---
$totalPelanggan    = Pelanggan::count();
        $belumBayarCount   = Pembayaran::where('status', 'belum_bayar')->count();
        $pembayaranHariIni = Pembayaran::where('status', 'lunas')
            ->whereDate('dibayar_at', $today)
            ->count();
        $gangguanHariIni   = Gangguan::whereDate('tanggal_lapor', $today)
            ->where('status', 'menunggu')
            ->count();

        // --- Section 1: Daftar Pelanggan Belum Bayar ---
        $daftarBelumBayar = Pembayaran::with('pelanggan')
            ->where('status', 'belum_bayar')
            ->latest()
            ->take(5)
            ->get();

        // --- Section 2: Pengaduan Gangguan Terbaru (data riil) ---
        $gangguanTerbaru = Gangguan::with('pelanggan')
            ->latest('tanggal_lapor')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id'            => $item->id,
                    'pelanggan_nama' => $item->pelanggan->nama ?? '-',
                    'pelanggan_kode' => $item->pelanggan->kode ?? '-',
                    'keluhan'        => $item->keluhan,
                    'waktu_laporan'  => $item->tanggal_lapor,
                    'status'         => $item->status,
                ];
            });

        // --- Section 3: Aktivitas Hari Ini ---
        $aktivitasHariIni = collect();

        // Pembayaran berhasil hari ini
        $pembayaranLunas = Pembayaran::with('pelanggan')
            ->where('status', 'lunas')
            ->whereDate('dibayar_at', $today)
            ->latest('dibayar_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'icon'  => 'credit-card',
                    'color' => 'emerald',
                    'text'  => 'Pembayaran berhasil dikonfirmasi — <strong>' . ($item->pelanggan->nama ?? '-') . '</strong> (Rp ' . number_format($item->jumlah, 0, ',', '.') . ')',
                    'time'  => $item->dibayar_at ? $item->dibayar_at->diffForHumans() : '-',
                ];
            });

        $aktivitasHariIni = $aktivitasHariIni->concat($pembayaranLunas);

        // Kunjungan hari ini
        $kunjunganHariIni = Kunjungan::with('pelanggan')
            ->whereDate('waktu_kunjungan', $today)
            ->latest('waktu_kunjungan')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $statusText = $item->status === 'tagihan_dibayar' ? 'Tagihan dibayar' : 'Kunjungan - Belum bayar';
                return (object) [
                    'icon'  => $item->status === 'tagihan_dibayar' ? 'check-circle' : 'map-pin',
                    'color' => $item->status === 'tagihan_dibayar' ? 'emerald' : 'amber',
                    'text'  => 'Kunjungan ke pelanggan selesai — <strong>' . ($item->pelanggan->nama ?? '-') . '</strong> (' . $statusText . ')',
                    'time'  => $item->waktu_kunjungan->diffForHumans(),
                ];
            });

        $aktivitasHariIni = $aktivitasHariIni->concat($kunjunganHariIni);

        // Tambah placeholder aktivitas jika kosong
        if ($aktivitasHariIni->isEmpty()) {
            $aktivitasHariIni = collect([
                (object) [
                    'icon'  => 'info',
                    'color' => 'slate',
                    'text'  => 'Belum ada aktivitas hari ini.',
                    'time'  => '-',
                ],
            ]);
        } else {
            $aktivitasHariIni = $aktivitasHariIni->sortByDesc('time')->take(10);
        }

        // --- Section 4: Ringkasan Hari Ini ---
$totalKunjungan = Kunjungan::whereDate('waktu_kunjungan', $today)->count();
        $totalPembayaranNominal = Pembayaran::where('status', 'lunas')
            ->whereDate('dibayar_at', $today)
            ->sum('jumlah');
        $gangguanSelesai = Gangguan::whereDate('tanggal_lapor', $today)
            ->where('status', 'selesai')
            ->count();

        return view('pegawai.dashboard', compact(
            'totalPelanggan',
            'belumBayarCount',
            'pembayaranHariIni',
            'gangguanHariIni',
            'daftarBelumBayar',
            'gangguanTerbaru',
            'aktivitasHariIni',
            'totalKunjungan',
            'totalPembayaranNominal',
            'gangguanSelesai',
        ));
    }
}
