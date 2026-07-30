<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default date range (bulan ini)
        $dari = $request->input('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->endOfMonth()->format('Y-m-d'));
        $jenis_laporan = $request->input('jenis_laporan', 'semua');
        $status_pembayaran = $request->input('status_pembayaran', 'semua');

        // Base query pembayaran
        $pembayaranQuery = Pembayaran::with(['pelanggan' => function ($q) {
                $q->select('id', 'nama', 'paket');
            }])
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59']);

        // Filter jenis laporan
        if ($jenis_laporan === 'pembayaran') {
            // only pembayaran (default)
        } elseif ($jenis_laporan === 'tunggakan') {
            $pembayaranQuery->where('status', 'belum_bayar');
        } elseif ($jenis_laporan === 'lunas') {
            $pembayaranQuery->where('status', 'lunas');
        }

        // Filter status pembayaran
        if ($status_pembayaran !== 'semua') {
            $pembayaranQuery->where('status', $status_pembayaran);
        }

        $pembayaranList = $pembayaranQuery->latest()->get();

        // Statistik
        $totalPendapatan = Pembayaran::where('status', 'lunas')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalTunai = Pembayaran::where('status', 'lunas')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalQris = Pembayaran::where('status', 'lunas')
            ->where('metode_pembayaran', 'qris')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalPengaduan = Gangguan::whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])->count();

        return view('admin.laporan.index', compact(
            'dari', 'sampai', 'jenis_laporan', 'status_pembayaran',
            'pembayaranList', 'totalPendapatan', 'totalTunai', 'totalQris', 'totalPengaduan'
        ));
    }

    public function exportPdf(Request $request)
    {
        // TODO: Implement PDF export using barryvdh/laravel-dompdf or similar
        return redirect()->route('admin.laporan')->with('status', 'Fitur export PDF akan segera tersedia.');
    }

    public function exportExcel(Request $request)
    {
        // TODO: Implement Excel export using maatwebsite/laravel-excel or similar
        return redirect()->route('admin.laporan')->with('status', 'Fitur export Excel akan segera tersedia.');
    }
}

