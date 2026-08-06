<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Notification;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /**
     * Daftar semua pengaduan dari pelanggan.
     */
    public function index(Request $request)
    {
        $query = Pengaduan::with('user');

        // Filter search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_keluhan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('jenis_gangguan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status
        if ($request->status && in_array($request->status, ['Menunggu', 'Diproses', 'Selesai'])) {
            $query->where('status', $request->status);
        }

        // Filter jenis gangguan
        if ($request->jenis) {
            $query->where('jenis_gangguan', $request->jenis);
        }

        $pengaduans = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        $totalMenunggu = Pengaduan::where('status', 'Menunggu')->count();
        $totalDiproses = Pengaduan::where('status', 'Diproses')->count();
        $totalSelesai  = Pengaduan::where('status', 'Selesai')->count();
        $totalSemua    = Pengaduan::count();

        // Daftar jenis gangguan untuk filter
        $jenisList = Pengaduan::select('jenis_gangguan')->distinct()->orderBy('jenis_gangguan')->pluck('jenis_gangguan');

        return view('admin.pengaduan.index', compact(
            'pengaduans', 'totalMenunggu', 'totalDiproses', 'totalSelesai', 'totalSemua', 'jenisList'
        ));
    }

    /**
     * Detail pengaduan.
     */
    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load('user');
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Update status pengaduan (Menunggu → Diproses → Selesai).
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status'     => ['required', 'in:Menunggu,Diproses,Selesai'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

$pengaduan->update([
            'status'     => $request->status,
            'keterangan' => $request->keterangan ?? $pengaduan->keterangan,
        ]);

        // Sinkronkan status ke tabel gangguans (halaman pegawai)
        $statusMap = [
            'Menunggu' => 'menunggu',
            'Diproses' => 'diproses',
            'Selesai'  => 'selesai',
        ];
        Gangguan::where('pengaduan_id', $pengaduan->id)
            ->update(['status' => $statusMap[$request->status]]);

        // Notifikasi ke pelanggan
        Notification::create([
            'user_id' => $pengaduan->user_id,
            'type'    => 'pengaduan',
            'icon'    => match($request->status) {
                'Selesai' => 'check-circle',
                'Diproses' => 'loader',
                default   => 'clock',
            },
            'color'   => match($request->status) {
                'Selesai' => 'emerald',
                'Diproses' => 'blue',
                default   => 'amber',
            },
            'message' => 'Status pengaduan "' . $pengaduan->judul_keluhan . '" telah diubah menjadi ' . $request->status . ($request->keterangan ? '. Keterangan: ' . $request->keterangan : ''),
            'url'     => route('pelanggan.pengaduan.show', $pengaduan),
        ]);

        // Notifikasi ke admin
        Notification::create([
            'user_id' => 1,
            'type'    => 'pengaduan',
            'icon'    => 'message-square-warning',
            'color'   => match($request->status) {
                'Selesai' => 'emerald',
                'Diproses' => 'blue',
                default   => 'amber',
            },
            'message' => 'Pengaduan "' . $pengaduan->judul_keluhan . '" → ' . $request->status,
            'url'     => route('admin.pengaduan.show', $pengaduan),
        ]);

        return redirect()->route('admin.pengaduan.index')
            ->with('status', 'Status pengaduan berhasil diperbarui menjadi ' . $request->status . '.');
    }
}
