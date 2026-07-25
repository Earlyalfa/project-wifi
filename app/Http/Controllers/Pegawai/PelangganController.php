<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = Pelanggan::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('kode', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('pegawai.pelanggan.index', compact('pelanggans'));
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['pembayarans' => function ($q) {
            $q->latest();
        }, 'kunjungans.pegawai']);

        $tagihanTerbaru = $pelanggan->pembayarans->first();

        return view('pegawai.pelanggan.detail', compact('pelanggan', 'tagihanTerbaru'));
    }

    /**
     * Endpoint JSON untuk lazy-load foto rumah.
     * Foto TIDAK dikirim bersamaan dengan halaman detail — baru diambil
     * lewat fetch() saat tombol "View Foto Rumah" ditekan. Ini bikin
     * halaman detail lebih ringan & hemat kuota kalau foto tidak dibuka.
     */
    public function foto(Pelanggan $pelanggan)
    {
        return response()->json([
            'url' => $pelanggan->foto_rumah ? asset('storage/'.$pelanggan->foto_rumah) : null,
            'diambil_saat' => 'Foto ini diambil saat pendaftaran pelanggan.',
        ]);
    }

    /**
     * Pegawai mencatat kunjungan ke rumah pelanggan.
     */
    public function catatKunjungan(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'status' => ['required', 'in:tagihan_dibayar,belum_bayar'],
            'catatan' => ['nullable', 'string'],
        ]);

        Kunjungan::create([
            'pelanggan_id' => $pelanggan->id,
            'pegawai_id' => $request->user()->id,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'waktu_kunjungan' => now(),
        ]);

        return back()->with('status', 'Kunjungan berhasil dicatat.');
    }

    /**
     * Pegawai mengonfirmasi pembayaran pelanggan lunas.
     */
    public function konfirmasiPembayaran(Request $request, Pelanggan $pelanggan)
    {
        $tagihan = $pelanggan->pembayarans()->where('status', 'belum_bayar')->latest()->first();

        if ($tagihan) {
            $tagihan->update([
                'status' => 'lunas',
                'dibayar_at' => now(),
            ]);
        }

        return back()->with('status', 'Pembayaran berhasil dikonfirmasi.');
    }
}
