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
        // Hanya admin yang boleh menambah pelanggan
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menambahkan pelanggan.');
        }

        $kode = $this->generateKode();
        $paketList = \App\Models\Paket::where('status', 'aktif')->get();
        return view('pegawai.pelanggan.create', compact('kode', 'paketList'));
    }

public function store(Request $request)
    {
        // Hanya admin yang boleh menambah pelanggan
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk menambahkan pelanggan.');
        }

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255'],
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

        $pelanggan = Pelanggan::create($validated);

        // Notifikasi untuk admin
        Notification::create([
            'user_id' => 1,
            'type'    => 'pelanggan',
            'icon'    => 'user-plus',
            'color'   => 'emerald',
            'message' => 'Pelanggan baru: ' . $pelanggan->nama . ' (' . $pelanggan->kode . ')',
            'url'     => route('pegawai.pelanggan.show', $pelanggan),
        ]);

        return redirect()->route('pegawai.pelanggan.index')
            ->with('status', 'Pelanggan berhasil ditambahkan.');
    }

public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['pembayarans' => function ($q) {
            $q->latest();
        }, 'kunjungans.pegawai', 'user']);

        $tagihanTerbaru = $pelanggan->pembayarans->first();

        return view('pegawai.pelanggan.detail', compact('pelanggan', 'tagihanTerbaru'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        $paketList = \App\Models\Paket::where('status', 'aktif')->get();
        return view('pegawai.pelanggan.edit', compact('pelanggan', 'paketList'));
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
            if ($pelanggan->foto_rumah) {
                Storage::disk('public')->delete($pelanggan->foto_rumah);
            }
            $validated['foto_rumah'] = $request->file('foto_rumah')->store('foto-rumah', 'public');
        }

        $pelanggan->update($validated);

        Notification::create([
            'user_id' => 1,
            'type'    => 'pelanggan',
            'icon'    => 'pencil',
            'color'   => 'indigo',
            'message' => 'Data pelanggan diubah: ' . $pelanggan->nama . ' (' . $pelanggan->kode . ')',
            'url'     => route('pegawai.pelanggan.show', $pelanggan),
        ]);

        return redirect()->route('pegawai.pelanggan.index')
            ->with('status', 'Pelanggan berhasil diperbarui.');
    }

    public function foto(Pelanggan $pelanggan)
    {
        return response()->json([
            'url' => $pelanggan->foto_rumah ? asset('storage/'.$pelanggan->foto_rumah) : null,
            'diambil_saat' => 'Foto ini diambil saat pendaftaran pelanggan.',
        ]);
    }

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

        Notification::create([
            'user_id' => 1,
            'type'    => 'kunjungan',
            'icon'    => 'user-check',
            'color'   => 'blue',
            'message' => 'Kunjungan ke ' . $pelanggan->nama . ' (' . ($request->status === 'tagihan_dibayar' ? 'Lunas' : 'Belum bayar') . ')',
            'url'     => route('pegawai.pelanggan.show', $pelanggan),
        ]);

        return back()->with('status', 'Kunjungan berhasil dicatat.');
    }

    public function konfirmasiPembayaran(Request $request, Pelanggan $pelanggan)
    {
        $tagihan = $pelanggan->pembayarans()->where('status', 'belum_bayar')->latest()->first();

        if ($tagihan) {
            $tagihan->update([
                'status' => 'lunas',
                'dibayar_at' => now(),
            ]);

            Notification::create([
                'user_id' => 1,
                'type'    => 'pembayaran',
                'icon'    => 'credit-card',
                'color'   => 'emerald',
                'message' => 'Pembayaran dikonfirmasi: ' . $pelanggan->nama . ' - Rp ' . number_format($tagihan->jumlah, 0, ',', '.'),
                'url'     => route('pegawai.pelanggan.show', $pelanggan),
            ]);
        }

        return back()->with('status', 'Pembayaran berhasil dikonfirmasi.');
    }
}
