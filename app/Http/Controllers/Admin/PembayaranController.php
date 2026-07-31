<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Daftar semua pembayaran dengan filter.
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with('pelanggan');

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter periode (bulan)
        if ($request->periode) {
            $query->where('periode', 'like', "%{$request->periode}%");
        }

        // Pencarian berdasarkan nama pelanggan atau kode
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('pelanggan', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $pembayarans = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        $totalPembayaran     = Pembayaran::count();
        $totalMenunggu       = Pembayaran::where('status', 'menunggu_verifikasi')->count();
        $totalLunas          = Pembayaran::where('status', 'lunas')->count();
        $totalDitolak        = Pembayaran::where('status', 'ditolak')->count();
        $totalBelumBayar     = Pembayaran::where('status', 'belum_bayar')->count();

        // Total nominal
        $totalNominalLunas   = Pembayaran::where('status', 'lunas')->sum('jumlah');
        $totalNominalPending = Pembayaran::where('status', 'menunggu_verifikasi')->sum('jumlah');

        return view('admin.pembayaran.index', compact(
            'pembayarans',
            'totalPembayaran',
            'totalMenunggu',
            'totalLunas',
            'totalDitolak',
            'totalBelumBayar',
            'totalNominalLunas',
            'totalNominalPending'
        ));
    }

    /**
     * Detail pembayaran.
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('pelanggan');
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi pembayaran (setujui / tolak).
     */
    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'catatan_verifikasi' => ['nullable', 'string', 'max:500'],
        ]);

        if ($pembayaran->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Status pembayaran tidak valid untuk diverifikasi.');
        }

        if ($request->aksi === 'setujui') {
            $pembayaran->update([
                'status'      => 'lunas',
                'dibayar_at'  => now(),
                'catatan'     => $pembayaran->catatan
                    ? $pembayaran->catatan . ' | Verifikasi: ' . ($request->catatan_verifikasi ?? 'Disetujui')
                    : ($request->catatan_verifikasi ?? 'Disetujui'),
            ]);

            // Notifikasi ke pelanggan
            if ($pembayaran->pelanggan && $pembayaran->pelanggan->user_id) {
                Notification::create([
                    'user_id' => $pembayaran->pelanggan->user_id,
                    'type'    => 'pembayaran',
                    'icon'    => 'check-circle',
                    'color'   => 'emerald',
                    'message' => 'Pembayaran periode ' . $pembayaran->periode . ' telah diverifikasi dan dinyatakan LUNAS oleh Admin.',
                    'url'     => route('pelanggan.pembayaran'),
                ]);
            }

            return redirect()->route('admin.pembayaran.index')
                ->with('status', 'Pembayaran berhasil disetujui! Status sekarang: LUNAS.');
        } else {
            $pembayaran->update([
                'status'  => 'ditolak',
                'catatan' => $pembayaran->catatan
                    ? $pembayaran->catatan . ' | Ditolak: ' . ($request->catatan_verifikasi ?? 'Tidak valid')
                    : ($request->catatan_verifikasi ?? 'Tidak valid'),
            ]);

            // Notifikasi ke pelanggan
            if ($pembayaran->pelanggan && $pembayaran->pelanggan->user_id) {
                Notification::create([
                    'user_id' => $pembayaran->pelanggan->user_id,
                    'type'    => 'pembayaran',
                    'icon'    => 'x-circle',
                    'color'   => 'rose',
                    'message' => 'Pembayaran periode ' . $pembayaran->periode . ' ditolak oleh Admin.' . ($request->catatan_verifikasi ? ' Alasan: ' . $request->catatan_verifikasi : ''),
                    'url'     => route('pelanggan.pembayaran'),
                ]);
            }

            return redirect()->route('admin.pembayaran.index')
                ->with('status', 'Pembayaran ditolak.');
        }
    }
}
