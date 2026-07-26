<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pelanggan', 'pegawai']);

        // Filter search (nama pelanggan, kode, catatan)
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                })
                ->orWhere('catatan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->status && in_array($request->status, ['tagihan_dibayar', 'belum_bayar'])) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->tanggal_awal) {
            $query->whereDate('waktu_kunjungan', '>=', $request->tanggal_awal);
        }
        if ($request->tanggal_akhir) {
            $query->whereDate('waktu_kunjungan', '<=', $request->tanggal_akhir);
        }

        // Filter pegawai (untuk admin yang mau lihat semua, atau pegawai lihat sendiri)
        if ($request->user()->isAdmin() && $request->pegawai_id) {
            $query->where('pegawai_id', $request->pegawai_id);
        } elseif (! $request->user()->isAdmin()) {
            $query->where('pegawai_id', $request->user()->id);
        }

        $kunjungans = $query->latest('waktu_kunjungan')->paginate(15);

        // Statistik
        $today = today();
        $totalHariIni = Kunjungan::whereDate('waktu_kunjungan', $today)
            ->when(!$request->user()->isAdmin(), fn($q) => $q->where('pegawai_id', $request->user()->id))
            ->count();
        $totalTagihanDibayar = Kunjungan::where('status', 'tagihan_dibayar')
            ->when(!$request->user()->isAdmin(), fn($q) => $q->where('pegawai_id', $request->user()->id))
            ->count();
        $totalBelumBayar = Kunjungan::where('status', 'belum_bayar')
            ->when(!$request->user()->isAdmin(), fn($q) => $q->where('pegawai_id', $request->user()->id))
            ->count();
        $totalSemua = Kunjungan::query()
            ->when(!$request->user()->isAdmin(), fn($q) => $q->where('pegawai_id', $request->user()->id))
            ->count();

        return view('pegawai.kunjungan.index', compact(
            'kunjungans', 'totalHariIni', 'totalTagihanDibayar',
            'totalBelumBayar', 'totalSemua'
        ));
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pelanggan', 'pegawai']);
        return view('pegawai.kunjungan.show', compact('kunjungan'));
    }
}

