<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Gangguan;
use App\Models\Kunjungan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    private function findPelangganForUser(User $user): ?Pelanggan
    {
        return Pelanggan::where('user_id', $user->id)->first()
            ?? Pelanggan::where('email', $user->email)->first()
            ?? Pelanggan::where('nama', $user->name)
                ->whereNotNull('foto_rumah')
                ->latest()
                ->first()
            ?? Pelanggan::where('nama', $user->name)->latest()->first();
    }

    public function show(User $user)
    {
        $pelanggan = null;
        $tagihanBelumBayar = collect();
        $qrCodeBase64 = null;

        if ($user->role === 'pelanggan') {
            $pelanggan = $this->findPelangganForUser($user);

            if ($pelanggan) {
                $pelanggan->load(['pembayarans' => fn ($query) => $query->latest()]);
                $tagihanBelumBayar = $pelanggan->pembayarans
                    ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi']);

                $qrCodeSvg = (string) QrCode::format('svg')
                    ->size(150)
                    ->margin(2)
                    ->color(26, 26, 46)
                    ->generate($pelanggan->kode);
                $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            }
        }

        $view = $user->role === 'pelanggan'
            ? 'admin.users.show-customer-layout'
            : 'admin.users.show';

        return view($view, compact('user', 'pelanggan', 'tagihanBelumBayar', 'qrCodeBase64'));
    }

    public function edit(User $user)
    {
        $pelanggan = $this->findPelangganForUser($user);

        if ($user->role === 'pelanggan') {
            $paketList = Paket::where('status', 'aktif')->orderBy('nama')->get();

            return view('admin.users.edit-customer', compact('user', 'pelanggan', 'paketList'));
        }

        $paketList = Paket::select('nama')->where('status', 'aktif')->orderBy('nama')->pluck('nama');
        return view('admin.users.edit', compact('user', 'pelanggan', 'paketList'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'status'               => ['required', 'in:aktif,nonaktif'],
            'no_hp'                => ['nullable', 'string', 'max:20'],
            'alamat'               => ['nullable', 'string', 'max:500'],
            'paket'                => ['nullable', 'string', 'max:100'],
            'paket_custom'         => ['nullable', 'string', 'max:100'],
            'tagihan_jatuh_tempo'  => ['nullable', 'integer', 'between:1,31'],
            'foto_rumah'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $pelanggan = $this->findPelangganForUser($user);

        $user->update([
            'name'   => $validated['name'],
            'status' => $validated['status'],
            'no_hp'  => $validated['no_hp'] ?? $user->no_hp,
            'alamat' => $validated['alamat'] ?? $user->alamat,
        ]);

        // Determine final paket
        if (($validated['paket'] ?? '') === 'custom') {
            $validated['paket'] = $validated['paket_custom'] ?: ($pelanggan?->paket);
        }
        unset($validated['paket_custom']);
        $paket = ! empty($validated['paket'])
            ? Paket::where('nama', $validated['paket'])->orderBy('id')->first()
            : null;

        // Update or create the customer's linked record.
        if (! $pelanggan) {
            $pelanggan = new Pelanggan();
            $pelanggan->kode = $this->generatePelangganKode();
            $pelanggan->user_id = $user->id;
        }

        $pelanggan->nama                 = $validated['name'];
        $pelanggan->alamat               = $validated['alamat'] ?? $pelanggan->alamat;
        $pelanggan->no_hp                = $validated['no_hp'] ?? $pelanggan->no_hp;
        $pelanggan->paket                = $validated['paket'] ?? $pelanggan->paket;
        if (($validated['paket'] ?? null) !== null) {
            $pelanggan->paket_id = $paket?->id;
        }
        $pelanggan->tagihan_jatuh_tempo  = $validated['tagihan_jatuh_tempo'] ?? $pelanggan->tagihan_jatuh_tempo;
        $pelanggan->status               = $validated['status'];

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
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'             => ['required', Rules\Password::defaults()],
            'no_hp'                => ['nullable', 'string', 'max:20'],
            'alamat'               => ['nullable', 'string', 'max:500'],
            'paket'                => ['nullable', 'string', 'max:100'],
            'paket_custom'         => ['nullable', 'string', 'max:100'],
            'tagihan_jatuh_tempo'  => ['nullable', 'integer', 'between:1,31'],
            'foto_rumah'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'role'                 => ['required', 'in:admin,pegawai,pelanggan'],
            'status'               => ['required', 'in:aktif,nonaktif'],
        ]);

        // Determine final paket
        if ($validated['paket'] === 'custom' && !empty($validated['paket_custom'])) {
            $validated['paket'] = $validated['paket_custom'];
        } elseif (($validated['paket'] ?? null) === 'custom') {
            $validated['paket'] = null;
        }
        unset($validated['paket_custom']);
        $paket = ! empty($validated['paket'])
            ? Paket::where('nama', $validated['paket'])->orderBy('id')->first()
            : null;

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
            'user_id'              => $user->id,
            'paket_id'             => $paket?->id,
            'kode'                 => $this->generatePelangganKode(),
            'nama'                 => $validated['name'],
            'paket'                => $validated['paket'] ?? null,
            'tagihan_jatuh_tempo'  => $validated['tagihan_jatuh_tempo'] ?? null,
            'alamat'               => $validated['alamat'] ?? null,
            'no_hp'                => $validated['no_hp'] ?? null,
            'foto_rumah'           => $fotoPath,
            'status'               => $validated['status'],
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

    public function destroyUser(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return redirect()->route('admin.users')
                ->with('error', 'Akun admin yang sedang digunakan tidak dapat dihapus.');
        }

        $deleted = DB::transaction(function () use ($user) {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);

            $hasCustomerData = Pelanggan::query()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->orWhere('nama', $user->name);
                })
                ->exists();
            $hasComplaintHistory = Pengaduan::where('user_id', $user->id)->exists();
            $hasVisitHistory = Kunjungan::where('pegawai_id', $user->id)->exists();
            $hasPaymentHistory = Pembayaran::where('diterima_oleh_id', $user->id)->exists();
            $hasNotifications = Notification::where('user_id', $user->id)->exists();

            if ($hasCustomerData || $hasComplaintHistory || $hasVisitHistory || $hasPaymentHistory || $hasNotifications) {
                $user->update(['status' => 'nonaktif']);

                return false;
            }

            $user->delete();

            return true;
        });

        return redirect()->route('admin.users')->with(
            'status',
            $deleted
                ? 'Pengguna berhasil dihapus.'
                : 'Pengguna memiliki data pelanggan atau riwayat terkait. Akun dinonaktifkan agar data tetap tersimpan.'
        );
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

