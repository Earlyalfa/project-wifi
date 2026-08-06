<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cari data pelanggan berdasarkan user_id, fallback ke nama (jika user_id belum diisi)
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();
        if (!$pelanggan) {
            $pelanggan = Pelanggan::where('nama', $user->name)->first();
        }

        // Jika tidak ada data pelanggan sama sekali, tampilkan data akun user sebagai fallback
        if (!$pelanggan) {
            return view('pelanggan.dashboard', [
                'paket'              => '-',
                'tagihanBulanIni'    => 0,
                'jatuhTempo'         => null,
                'jatuhTempoText'     => 'Belum diatur',
                'statusPengaduan'    => '-',
                'riwayatPembayaran'  => collect([]),
                'pengaduanTerbaru'   => collect([]),
                'namaPelanggan'      => $user->name,
                'idPelanggan'        => '-',
                'noHp'               => $user->no_hp ?? '-',
                'email'              => $user->email,
                'alamat'             => $user->alamat ?? '-',
                'tanggalAktif'       => $user->created_at,
                'totalTagihan'       => 0,
                'statusAkun'         => $user->status ?? 'aktif',
                'pelanggan'          => null,
            ]);
        }

        // Data pelanggan (fallback ke data akun user jika field kosong/null)
        $paket       = $pelanggan->paket ?: '-';
        $nama        = $pelanggan->nama ?: $user->name;
        $idPelanggan = $pelanggan->kode ?: '-';
        $noHp        = $pelanggan->no_hp ?: ($user->no_hp ?: '-');
        $alamat      = $pelanggan->alamat ?: ($user->alamat ?: '-');
        $email       = $pelanggan->email ?: $user->email;
        $tanggalAktif = $pelanggan->created_at ?? $user->created_at;
        $statusAkun  = $pelanggan->status ?: ($user->status ?: 'aktif');

        // Tagihan bulan ini (belum bayar)
        $tagihanBulanIni = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'belum_bayar')
            ->sum('jumlah');

        // Jatuh tempo terdekat
        $jatuhTempo = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'belum_bayar')
            ->whereNotNull('jatuh_tempo')
            ->orderBy('jatuh_tempo', 'asc')
            ->value('jatuh_tempo');

        // Teks jatuh tempo: "Setiap tanggal X" — utamakan dari setting pelanggan (tagihan_jatuh_tempo),
        // fallback ke hari dari jatuh tempo terdekat, fallback terakhir "Belum diatur".
        $jatuhTempoText = 'Belum diatur';
        if ($pelanggan->tagihan_jatuh_tempo) {
            $jatuhTempoText = 'Setiap tanggal ' . (int) $pelanggan->tagihan_jatuh_tempo;
        } elseif ($jatuhTempo) {
            $hari = \Carbon\Carbon::parse($jatuhTempo)->day;
            $jatuhTempoText = 'Setiap tanggal ' . $hari;
        }

        // Status pengaduan terbaru
        $pengaduanLatest = Gangguan::where('pelanggan_id', $pelanggan->id)
            ->latest('tanggal_lapor')
            ->first();
        $statusPengaduan = $pengaduanLatest ? ucfirst($pengaduanLatest->status) : '-';

        // Riwayat pembayaran (semua)
        $riwayatPembayaran = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        // Pengaduan terbaru (5 data)
        $pengaduanTerbaru = Gangguan::where('pelanggan_id', $pelanggan->id)
            ->latest('tanggal_lapor')
            ->take(5)
            ->get();

        // Total tagihan (semua status)
        $totalTagihan = Pembayaran::where('pelanggan_id', $pelanggan->id)
            ->where('status', 'belum_bayar')
            ->sum('jumlah');

        return view('pelanggan.dashboard', [
            'paket'             => $paket,
            'tagihanBulanIni'   => $tagihanBulanIni,
            'jatuhTempo'        => $jatuhTempo,
            'jatuhTempoText'    => $jatuhTempoText,
            'statusPengaduan'   => $statusPengaduan,
            'riwayatPembayaran' => $riwayatPembayaran,
            'pengaduanTerbaru'  => $pengaduanTerbaru,
            'namaPelanggan'     => $nama,
            'idPelanggan'       => $idPelanggan,
            'noHp'              => $noHp,
            'email'             => $email,
            'alamat'            => $alamat,
            'tanggalAktif'      => $tanggalAktif,
            'totalTagihan'      => $totalTagihan,
            'statusAkun'        => $statusAkun,
            'pelanggan'         => $pelanggan,
        ]);
    }
}
