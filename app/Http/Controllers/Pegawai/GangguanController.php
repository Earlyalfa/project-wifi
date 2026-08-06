<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Notification;
use App\Models\Pelanggan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class GangguanController extends Controller
{
public function index(Request $request)
    {
        // Sinkronkan pengaduan lama dari tabel pengaduans yang belum punya record di gangguans,
        // agar pengaduan pelanggan (lama & baru) selalu muncul di halaman pegawai.
        $this->syncPengaduanKeGangguan();

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

    /**
     * Sinkronisasi pengaduan dari tabel pengaduans ke gangguans.
     * Pengaduan yang belum punya record gangguans akan dibuatkan record baru.
     */
private function syncPengaduanKeGangguan()
    {
        // Ambil pengaduan yang belum memiliki record gangguans
        $sudahDisinkron = Gangguan::whereNotNull('pengaduan_id')->pluck('pengaduan_id');

        $pengaduans = Pengaduan::with('user')
            ->whereNotIn('id', $sudahDisinkron)
            ->orderBy('created_at')
            ->get();

        foreach ($pengaduans as $pengaduan) {
            // Cari pelanggan terkait pengaduan (via user yang mengirim)
            $pelanggan = Pelanggan::where('user_id', $pengaduan->user_id)->first();
            if (! $pelanggan) {
                $pelanggan = Pelanggan::where('nama', $pengaduan->user->name ?? '')->first();
            }

            // Jika tidak ada pelanggan, buat otomatis dari user pengaduan
            if (! $pelanggan && $pengaduan->user) {
                $last = Pelanggan::latest()->first();
                $lastNumber = $last ? (int) substr($last->kode, 3) : 0;
                $kode = 'CS-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                $pelanggan = Pelanggan::create([
                    'user_id'  => $pengaduan->user_id,
                    'kode'     => $kode,
                    'nama'     => $pengaduan->user->name,
                    'email'    => $pengaduan->user->email,
                    'alamat'   => $pengaduan->user->alamat ?? null,
                    'no_hp'    => $pengaduan->user->no_hp ?? null,
                    'status'   => 'aktif',
                ]);
            }

            if (! $pelanggan) {
                continue; // skip jika tidak dapat pelanggan
            }

            $statusGangguan = $this->mapStatus($pengaduan->status);

            Gangguan::create([
                'no_tiket'      => Gangguan::generateNoTiket(),
                'pelanggan_id'  => $pelanggan->id,
                'pengaduan_id'  => $pengaduan->id,
                'keluhan'       => $pengaduan->judul_keluhan . ' — ' . $pengaduan->deskripsi,
                'tanggal_lapor' => $pengaduan->created_at,
                'status'        => $statusGangguan,
            ]);
        }
    }

    /**
     * Map status pengaduan (kapital) ke status gangguan (lowercase).
     */
    private function mapStatus(string $status): string
    {
        return match (strtolower($status)) {
            'menunggu' => 'menunggu',
            'diproses' => 'diproses',
            'selesai'  => 'selesai',
            default    => 'menunggu',
        };
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

        // Sinkronkan status ke tabel pengaduans (halaman admin)
        if ($gangguan->pengaduan_id) {
            $statusMap = [
                'menunggu' => 'Menunggu',
                'diproses' => 'Diproses',
                'selesai'  => 'Selesai',
            ];
            Pengaduan::where('id', $gangguan->pengaduan_id)
                ->update(['status' => $statusMap[$request->status]]);
        }

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

