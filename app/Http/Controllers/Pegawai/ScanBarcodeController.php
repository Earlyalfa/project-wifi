<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Notification;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanBarcodeController extends Controller
{
    public function create()
    {
        return view('pegawai.scan-barcode');
    }

    /**
     * AJAX: Cari pelanggan berdasarkan kode (dari QR scan atau input manual).
     * Return JSON data pelanggan + tagihan.
     */
    public function cariPelanggan(Request $request)
    {
        $request->validate([
            'kode' => ['required', 'string'],
        ]);

        $pelanggan = Pelanggan::where('kode', $request->kode)->first();

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Kode pelanggan tidak ditemukan.',
            ], 404);
        }

        // Jika hanya butuh ID (untuk konfirmasi payment)
        if ($request->boolean('get_id')) {
            return response()->json([
                'success' => true,
                'pelanggan_id' => $pelanggan->id,
            ]);
        }

        // Ambil tagihan yang belum bayar (prioritas) atau menunggu verifikasi
        $tagihan = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
            ->latest()
            ->first();

        // Riwayat pembayaran (3 terakhir)
        $riwayatBayar = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($p) {
                return [
                    'periode' => $p->periode,
                    'jumlah' => $p->jumlah,
                    'total_bayar' => $p->total_bayar ?? $p->jumlah,
                    'status' => $p->status,
                    'status_label' => match ($p->status) {
                        'lunas' => 'Lunas',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'ditolak' => 'Ditolak',
                        default => 'Belum Bayar',
                    },
                    'tanggal_bayar' => $p->dibayar_at ? $p->dibayar_at->format('d/m/Y') : ($p->tanggal_bayar ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') : '-'),
                ];
            });

        return response()->json([
            'success' => true,
            'pelanggan_id' => $pelanggan->id,
            'pelanggan' => [
                'kode' => $pelanggan->kode,
                'nama' => $pelanggan->nama,
                'alamat' => $pelanggan->alamat ?? '-',
                'no_hp' => $pelanggan->no_hp ?? '-',
                'paket' => $pelanggan->paket ?? '-',
                'status' => $pelanggan->status,
                'foto_rumah' => $pelanggan->foto_rumah ? asset('storage/' . $pelanggan->foto_rumah) : null,
            ],
            'tagihan' => $tagihan ? [
                'id' => $tagihan->id,
                'periode' => $tagihan->periode,
                'jumlah' => $tagihan->jumlah,
                'total_bayar' => $tagihan->total_bayar ?? $tagihan->jumlah,
                'jatuh_tempo' => $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : '-',
                'status' => $tagihan->status,
                'status_label' => match ($tagihan->status) {
                    'lunas' => 'Lunas',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'ditolak' => 'Ditolak',
                    default => 'Belum Lunas',
                },
                'status_color' => match ($tagihan->status) {
                    'lunas' => 'emerald',
                    'menunggu_verifikasi' => 'amber',
                    'ditolak' => 'rose',
                    default => 'rose',
                },
            ] : null,
            'riwayat_pembayaran' => $riwayatBayar,
        ]);
    }

    /**
     * AJAX: Konfirmasi pembayaran tunai oleh pegawai.
     */
    public function konfirmasiBayar(Request $request)
    {
        $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'tagihan_id' => ['required', 'exists:pembayarans,id'],
            'nominal_diterima' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);

        $tagihan = Pembayaran::where('id', $request->tagihan_id)
            ->where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
            ->firstOrFail();

        // Update status tagihan menjadi lunas
        $tagihan->update([
            'status' => 'lunas',
            'metode_pembayaran' => 'Tunai',
            'tanggal_bayar' => now(),
            'dibayar_at' => now(),
            'catatan' => $request->catatan ? ($tagihan->catatan ? $tagihan->catatan . ' | ' . $request->catatan : $request->catatan) : $tagihan->catatan,
        ]);

        // Catat kunjungan pegawai
        Kunjungan::create([
            'pelanggan_id' => $pelanggan->id,
            'pegawai_id' => Auth::id(),
            'status' => 'tagihan_dibayar',
            'catatan' => 'Pembayaran tunai dikonfirmasi oleh pegawai. Nominal diterima: Rp ' . number_format($request->nominal_diterima, 0, ',', '.') . ($request->catatan ? ' - ' . $request->catatan : ''),
            'waktu_kunjungan' => now(),
        ]);

        // Notifikasi untuk admin
        Notification::create([
            'user_id' => 1,
            'type' => 'pembayaran',
            'icon' => 'credit-card',
            'color' => 'emerald',
            'message' => 'Pembayaran tunai dikonfirmasi: ' . $pelanggan->nama . ' - Rp ' . number_format($tagihan->jumlah, 0, ',', '.'),
            'url' => route('pegawai.pelanggan.show', $pelanggan),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi! Status tagihan sekarang Lunas.',
            'tagihan' => [
                'id' => $tagihan->id,
                'status' => 'lunas',
                'status_label' => 'Lunas',
                'status_color' => 'emerald',
                'jumlah' => $tagihan->jumlah,
                'periode' => $tagihan->periode,
            ],
        ]);
    }
}
