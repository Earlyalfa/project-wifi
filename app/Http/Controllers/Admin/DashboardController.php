<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Gangguan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = today();

        // Safe query — wrap dengan try-catch biar ga error kalau tabel belum ada
        $safeCount = function ($model, $where = []) {
            try {
                return $model::when($where, fn($q) => $q->where($where))->count();
            } catch (\Exception $e) {
                return 0;
            }
        };

        $safeSum = function ($model, $where = []) {
            try {
                return $model::when($where, fn($q) => $q->where($where))->sum('jumlah');
            } catch (\Exception $e) {
                return 0;
            }
        };

        $safeGet = function ($model, $with = [], $where = []) {
            try {
                return $model::with($with)
                    ->when($where, fn($q) => $q->where($where))
                    ->latest()
                    ->take(10)
                    ->get();
            } catch (\Exception $e) {
                return collect();
            }
        };

        // Card Statistik
        $totalPengguna       = $safeCount(User::class);
        $pegawaiAktif        = $safeCount(User::class, ['role' => 'pegawai']);
        $paketWifi           = Pelanggan::whereNotNull('paket')->distinct('paket')->count('paket');
        $tagihanBelumDibayar = $safeCount(Pembayaran::class, ['status' => 'belum_bayar']);
        $pendapatanBulanIni  = $safeSum(Pembayaran::class, [
            ['status', 'lunas'],
            [DB::raw('MONTH(dibayar_at)'), '=', $today->month],
            [DB::raw('YEAR(dibayar_at)'), '=', $today->year],
        ]);
        $pengaduanAktif      = $safeCount(Gangguan::class);

        // Grafik 6 bulan terakhir
        $grafikBulanan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = $today->copy()->subMonths($i);
            $total = $safeSum(Pembayaran::class, [
                ['status', 'lunas'],
                [DB::raw('MONTH(dibayar_at)'), '=', $date->month],
                [DB::raw('YEAR(dibayar_at)'), '=', $date->year],
            ]);
            $grafikBulanan->push([
                'bulan' => $date->locale('id')->isoFormat('MMM YYYY'),
                'total' => (int) $total,
            ]);
        }

        // Pengguna Terbaru (dengan role bisa diubah)
        try {
            $penggunaTerbaru = User::latest()->take(10)->get();
        } catch (\Exception $e) {
            $penggunaTerbaru = collect();
        }

        // Pembayaran Terbaru
        $pembayaranTerbaru = $safeGet(Pembayaran::class, ['pelanggan']);

        // Pengaduan Terbaru
        $pengaduanTerbaru = $safeGet(Gangguan::class, ['pelanggan']);

        return view('admin.dashboard', compact(
            'totalPengguna',
            'pegawaiAktif',
            'paketWifi',
            'tagihanBelumDibayar',
            'pendapatanBulanIni',
            'pengaduanAktif',
            'grafikBulanan',
            'penggunaTerbaru',
            'pembayaranTerbaru',
            'pengaduanTerbaru',
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Cari data pelanggan berdasarkan nama (fallback)
        $pelanggan = Pelanggan::where('nama', $user->name)->first();
        return view('admin.users.show', compact('user', 'pelanggan'));
    }

    public function edit(User $user)
    {
        $pelanggan = Pelanggan::where('nama', $user->name)->first();
        $paketList = Paket::select('nama')->where('status', 'aktif')->orderBy('nama')->pluck('nama');
        return view('admin.users.edit', compact('user', 'pelanggan', 'paketList'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'status'      => ['required', 'in:aktif,nonaktif'],
            'no_hp'       => ['nullable', 'string', 'max:20'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'paket'       => ['nullable', 'string', 'max:100'],
            'paket_custom'=> ['nullable', 'string', 'max:100'],
            'foto_rumah'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Update User
        $user->update([
            'name'   => $validated['name'],
            'status' => $validated['status'],
        ]);

        // Determine final paket
        if (($validated['paket'] ?? '') === 'custom' && !empty($validated['paket_custom'])) {
            $validated['paket'] = $validated['paket_custom'];
        }
        unset($validated['paket_custom']);

        // Update or create Pelanggan record
        $pelanggan = Pelanggan::where('nama', $user->getOriginal('name'))->first();
        if (! $pelanggan) {
            $pelanggan = new Pelanggan();
            $pelanggan->kode = $this->generatePelangganKode();
        }

        $pelanggan->nama   = $validated['name'];
        $pelanggan->alamat = $validated['alamat'] ?? $pelanggan->alamat;
        $pelanggan->no_hp  = $validated['no_hp'] ?? $pelanggan->no_hp;
        $pelanggan->paket  = $validated['paket'] ?? $pelanggan->paket;
        $pelanggan->status = $validated['status'];

        if ($request->hasFile('foto_rumah')) {
            // Hapus foto lama jika ada
            if ($pelanggan->foto_rumah) {
                Storage::disk('public')->delete($pelanggan->foto_rumah);
            }
            $pelanggan->foto_rumah = $request->file('foto_rumah')->store('foto-rumah', 'public');
        }

        $pelanggan->save();

        return redirect()->route('admin.users.show', $user)
            ->with('status', 'Data pengguna berhasil diperbarui.');
    }

    public function create()
    {
        $paketList = Paket::select('nama')->where('status', 'aktif')->orderBy('nama')->pluck('nama');
        return view('admin.users.create', compact('paketList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', Rules\Password::defaults()],
            'no_hp'       => ['nullable', 'string', 'max:20'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'paket'       => ['nullable', 'string', 'max:100'],
            'paket_custom'=> ['nullable', 'string', 'max:100'],
            'foto_rumah'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'role'        => ['required', 'in:admin,pegawai,pelanggan'],
            'status'      => ['required', 'in:aktif,nonaktif'],
        ]);

        // Determine final paket
        if ($validated['paket'] === 'custom' && !empty($validated['paket_custom'])) {
            $validated['paket'] = $validated['paket_custom'];
        }
        unset($validated['paket_custom']);

        // Create User
        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'],
            'status'            => $validated['status'],
            'email_verified_at' => now(),
        ]);

        // Create Pelanggan record (always create, since every user is a potential customer)
        $fotoPath = null;
        if ($request->hasFile('foto_rumah')) {
            $fotoPath = $request->file('foto_rumah')->store('foto-rumah', 'public');
        }

        $pelanggan = Pelanggan::create([
            'kode'       => $this->generatePelangganKode(),
            'nama'       => $validated['name'],
            'paket'      => $validated['paket'] ?? null,
            'alamat'     => $validated['alamat'] ?? null,
            'no_hp'      => $validated['no_hp'] ?? null,
            'foto_rumah' => $fotoPath,
            'status'     => $validated['status'],
        ]);

        // Notifikasi untuk admin
        Notification::create([
            'user_id' => 1,
            'type'    => 'pelanggan',
            'icon'    => 'user-plus',
            'color'   => 'indigo',
            'message' => 'Admin menambahkan pengguna: ' . $user->name . ' (' . $pelanggan->kode . ')',
            'url'     => route('admin.users'),
        ]);

        return redirect()->route('admin.users')
            ->with('status', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Generate kode pelanggan baru (CS-XXXX).
     */
    private function generatePelangganKode()
    {
        $last = Pelanggan::latest()->first();
        if (! $last) {
            return 'CS-0001';
        }
        $lastNumber = (int) substr($last->kode, 3);
        return 'CS-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,pegawai,pelanggan'],
        ]);

        // Prevent admin from changing own role
        if ($user->id === $request->user()->id) {
            return back()->with('status', 'Tidak dapat mengubah role sendiri.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('status', 'Role pengguna berhasil diubah menjadi ' . ucfirst($request->role) . '.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $user->update(['status' => $request->status]);

        $statusLabel = $request->status === 'aktif' ? 'Aktif' : 'Nonaktif';

        return back()->with('status', 'Status pengguna berhasil diubah menjadi ' . $statusLabel . '.');
    }
}

