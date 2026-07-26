<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Notification;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    /**
     * Generate kode pelanggan baru (CS-XXXX).
     */
    private function generateKode()
    {
        $last = Pelanggan::latest()->first();
        if (! $last) {
            return 'CS-0001';
        }
        $lastNumber = (int) substr($last->kode, 3);
        return 'CS-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function index(Request $request)
    {
        $query = Pelanggan::query();

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter paket
        if ($request->paket) {
            $query->where('paket', $request->paket);
        }

        $pelanggans = $query->latest()->paginate(10);

        // Load payment status & gangguan status for each
        $pelanggans->load(['pembayarans' => function ($q) {
            $q->latest()->limit(1);
        }]);

        // Get gangguan status for all displayed pelanggan
        $gangguanStatuses = \App\Models\Gangguan::whereIn('pelanggan_id', $pelanggans->pluck('id'))
            ->select('pelanggan_id', 'status')
            ->latest('tanggal_lapor')
            ->get()
            ->keyBy('pelanggan_id');

        // Statistik
        $totalAktif    = Pelanggan::where('status', 'aktif')->count();
        $totalNonaktif = Pelanggan::where('status', 'nonaktif')->count();
        $totalPelanggan = Pelanggan::count();
        $totalGangguan  = \App\Models\Gangguan::whereIn('status', ['menunggu', 'diproses'])->count();

        // Daftar paket untuk filter dropdown
        $paketList = Pelanggan::select('paket')->whereNotNull('paket')->distinct()->orderBy('paket')->pluck('paket');

        return view('pegawai.pelanggan.index', compact(
            'pelanggans', 'totalAktif', 'totalNonaktif', 'totalPelanggan', 'totalGangguan',
            'gangguanStatuses', 'paketList'
        ));
    }

    public function create()
    {
        $kode = $this->generateKode();
        return view('pegawai.pelanggan.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'paket'      => ['nullable', 'string', 'max:100'],
            'alamat'     => ['nullable', 'string', 'max:500'],
            'no_hp'      => ['nullable', 'string', 'max:20'],
            'status'     => ['required', 'in:aktif,nonaktif'],
            'foto_rumah' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $validated['kode'] = $this->generateKode();

        if ($request->hasFile('foto_rumah')) {
            $validated['foto_rumah'] = $request->file('foto_rumah')->store('foto-rumah', 'public');
        }

        Pelanggan::create($validated);

        return redirect()->route('pegawai.pelanggan.index')
            ->with('status', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pegawai.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'paket'      => ['nullable', 'string', 'max:100'],
            'alamat'     => ['nullable', 'string', 'max:500'],
            'no_hp'      => ['nullable', 'string', 'max:20'],
            'status'     => ['required', 'in:aktif,nonaktif'],
            'foto_rumah' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto_rumah')) {
            // Hapus foto lama jika ada
            if ($pelanggan->foto_rumah) {
                Storage::disk('public')->delete($pelanggan->foto_rumah);
            }
            $validated['foto_rumah'] = $request->file('foto_rumah')->store('foto-rumah', 'public');
        }

        $pelanggan->update($validated);

        return redirect()->route('pegawai.pelanggan.index')
            ->with('status', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Hapus foto jika ada
        if ($pelanggan->foto_rumah) {
            Storage::disk('public')->delete($pelanggan->foto_rumah);
        }

        $pelanggan->delete();

        return redirect()->route('pegawai.pelanggan.index')
            ->with('status', 'Pelanggan berhasil dihapus.');
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
