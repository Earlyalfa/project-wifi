<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                'tagihan'           => null,
                'riwayatPembayaran' => collect([]),
                'totalPembayaran'   => 0,
                'pelanggan'         => null,
                'qrisMerchant'      => '-',
                'qrisImage'         => null,
            ]);
        }

        // Ambil tagihan yang belum dibayar (terbaru)
        // Termasuk status 'menunggu_penagihan' untuk kompatibilitas data lama
        $tagihan = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->whereIn('status', ['belum_bayar', 'menunggu_penagihan'])
            ->latest()
            ->first();

        // Total pembayaran = nominal tagihan (QRIS tidak menggunakan kode unik)
        $totalPembayaran = $tagihan ? $tagihan->jumlah : 0;

        // Riwayat pembayaran (semua data) - termasuk yang menunggu verifikasi
        $riwayatPembayaran = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        // Data QRIS dari tabel settings
        $qrisMerchant = Setting::getValue('qris_merchant_name', 'WiFiPay');
        $qrisImage    = Setting::getValue('qris_image', null);

        return view('pelanggan.pembayaran', [
            'tagihan'           => $tagihan,
            'riwayatPembayaran' => $riwayatPembayaran,
            'totalPembayaran'   => $totalPembayaran,
            'pelanggan'         => $pelanggan,
            'qrisMerchant'      => $qrisMerchant,
            'qrisImage'         => $qrisImage,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        if (!$pelanggan) {
            return redirect()->route('pelanggan.pembayaran')
                ->with('error', 'Data pelanggan tidak ditemukan.');
        }

        // Validasi
        $validated = $request->validate([
            'tagihan_id'       => ['required', 'exists:pembayarans,id'],
            'tanggal_bayar'    => ['required', 'date'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'bukti_bayar'      => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'catatan'          => ['nullable', 'string', 'max:500'],
            'konfirmasi'       => ['required', 'accepted'],
        ]);

        // Cari tagihan
        $tagihan = Pembayaran::where('id', $validated['tagihan_id'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status', 'belum_bayar')
            ->firstOrFail();

        // Upload file bukti pembayaran
        $path = $request->file('bukti_bayar')->store('bukti-pembayaran', 'public');

        // Update tagihan — QRIS tanpa kode unik, total bayar = nominal tagihan
        $tagihan->update([
            'total_bayar'       => $tagihan->jumlah,
            'status'            => 'menunggu_verifikasi',
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'bukti_bayar'       => $path,
            'catatan'           => $validated['catatan'] ?? null,
            'tanggal_bayar'     => $validated['tanggal_bayar'],
            'dibayar_at'        => now(),
        ]);

        // Redirect dengan SweetAlert2
        return redirect()->route('pelanggan.pembayaran')
            ->with('success', 'Pembayaran berhasil dikirim! Status pembayaran Anda sekarang "Menunggu Verifikasi". Silakan tunggu konfirmasi dari admin.');
    }
}

