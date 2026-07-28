<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Notification;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class GangguanController extends Controller
{
    public function index(Request $request)
    {
        $query = Gangguan::with('pelanggan');

        // Filter search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_tiket', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%")
                         ->orWhere('alamat', 'like', "%{$search}%");
                  })
                  ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->status && in_array($request->status, ['menunggu', 'diproses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $gangguans = $query->latest('tanggal_lapor')->paginate(10);

        // Statistik
        $totalMenunggu = Gangguan::where('status', 'menunggu')->count();
        $totalDiproses = Gangguan::where('status', 'diproses')->count();
        $totalSelesai  = Gangguan::where('status', 'selesai')->count();
        $totalSemua    = Gangguan::count();

        return view('pegawai.gangguan.index', compact(
            'gangguans', 'totalMenunggu', 'totalDiproses', 'totalSelesai', 'totalSemua'
        ));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();
        $noTiket = Gangguan::generateNoTiket();
        return view('pegawai.gangguan.create', compact('pelanggans', 'noTiket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggans,id'],
            'keluhan'      => ['required', 'string', 'max:1000'],
        ]);

        $validated['no_tiket']     = Gangguan::generateNoTiket();
        $validated['tanggal_lapor'] = now();
        $validated['status']        = 'menunggu';

$gangguan = Gangguan::create($validated);

        // Notifikasi untuk pegawai
        $pelanggan = Pelanggan::find($validated['pelanggan_id']);
        Notification::notifyPegawai(
            'gangguan',
            'Pengaduan baru: ' . $pelanggan->nama . ' - ' . $validated['keluhan'],
            route('pegawai.gangguan.show', $gangguan),
            'message-square-warning',
            'amber'
        );

        return redirect()->route('pegawai.gangguan.index')
            ->with('status', 'Pengaduan gangguan berhasil diajukan.');
    }

    public function show(Gangguan $gangguan)
    {
        $gangguan->load('pelanggan');
        return view('pegawai.gangguan.show', compact('gangguan'));
    }

public function updateStatus(Request $request, Gangguan $gangguan)
    {
        $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai'],
        ]);

        $gangguan->update(['status' => $request->status]);

        // Notifikasi untuk admin
        $pelanggan = $gangguan->pelanggan;
        if (! $pelanggan) $pelanggan = Pelanggan::find($gangguan->pelanggan_id);

        Notification::create([
            'user_id' => 1,
            'type'    => 'gangguan',
            'icon'    => 'message-square-warning',
            'color'   => ($request->status === 'selesai' ? 'emerald' : ($request->status === 'diproses' ? 'blue' : 'amber')),
            'message' => 'Status pengaduan ' . ($pelanggan->nama ?? '#'.$gangguan->id) . ' → ' . ucfirst($request->status),
            'url'     => route('pegawai.gangguan.show', $gangguan),
        ]);

        return redirect()->route('pegawai.gangguan.index')
            ->with('status', 'Status pengaduan berhasil diperbarui.');
    }

    public function destroy(Gangguan $gangguan)
    {
        $gangguan->delete();

        return redirect()->route('pegawai.gangguan.index')
            ->with('status', 'Pengaduan berhasil dihapus.');
    }
}

