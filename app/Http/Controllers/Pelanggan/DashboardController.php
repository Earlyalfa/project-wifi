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

        // Cari data pelanggan berdasarkan user_id
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        // Jika tidak ada data pelanggan, tampilkan data default
        if (!$pelanggan) {
            return view('pelanggan.dashboard', [
                'paket'              => '-',
                'tagihanBulanIni'    => 0,
                'jatuhTempo'         => null,
                'statusPengaduan'    => '-',
                'riwayatPembayaran'  => collect([]),
                'pengaduanTerbaru'   => collect([]),
                'namaPelanggan'      => $user->name,
                'noHp'               => '-',
                'email'              => $user->email,
                'alamat'             => '-',
                'tanggalAktif'       => $user->created_at,
                'totalTagihan'       => 0,
                'statusAkun'         => 'aktif',
                'pelanggan'          => null,
            ]);
        }

        // Data pelanggan
        $paket       = $pelanggan->paket ?? '-';
        $nama        = $pelanggan->nama;
        $noHp        = $pelanggan->no_hp ?? '-';
        $alamat      = $pelanggan->alamat ?? '-';
        $tanggalAktif = $pelanggan->created_at;
        $statusAkun  = $pelanggan->status ?? 'aktif';

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
            'statusPengaduan'   => $statusPengaduan,
            'riwayatPembayaran' => $riwayatPembayaran,
            'pengaduanTerbaru'  => $pengaduanTerbaru,
            'namaPelanggan'     => $nama,
            'noHp'              => $noHp,
            'email'             => $user->email,
            'alamat'            => $alamat,
            'tanggalAktif'      => $tanggalAktif,
            'totalTagihan'      => $totalTagihan,
            'statusAkun'        => $statusAkun,
            'pelanggan'         => $pelanggan,
        ]);
    }
}
