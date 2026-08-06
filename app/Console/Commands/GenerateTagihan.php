<?php

namespace App\Console\Commands;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateTagihan extends Command
{
    protected $signature = 'tagihan:generate';

    protected $description = 'Buat tagihan bulanan otomatis untuk pelanggan aktif yang belum memiliki tagihan pada periode berjalan';

    public function handle(): int
    {
        Carbon::setLocale('id');

        $periode = Carbon::now()->translatedFormat('F Y');
        $bulan   = (int) Carbon::now()->format('m');
        $tahun   = (int) Carbon::now()->format('Y');

        $pelanggans = Pelanggan::where('status', 'aktif')->get();

        $dibuat   = 0;
        $dilewati = 0;

        foreach ($pelanggans as $pelanggan) {
            // Lewati jika sudah ada tagihan untuk periode berjalan
            $sudahAda = Pembayaran::where('pelanggan_id', $pelanggan->id)
                ->where('periode', $periode)
                ->exists();

            if ($sudahAda) {
                $dilewati++;
                continue;
            }

            // Ambil nominal dari paket WiFi pelanggan
            $paket  = Paket::where('nama', $pelanggan->paket)->first();
            $jumlah = $paket ? (int) $paket->harga : 0;

            // Jatuh tempo mengikuti tanggal tagihan_jatuh_tempo pelanggan.
            // Jika tagihan_jatuh_tempo belum diatur (NULL), jatuh_tempo dibiarkan NULL
            // (tidak membuat tanggal otomatis).
            $hariJatuhTempo = $pelanggan->tagihan_jatuh_tempo;
            $jatuhTempo     = null;
            if ($hariJatuhTempo) {
                $maxHari    = Carbon::create($tahun, $bulan)->daysInMonth;
                $hari       = min((int) $hariJatuhTempo, $maxHari);
                $jatuhTempo = Carbon::create($tahun, $bulan, $hari);
            }

            Pembayaran::create([
                'pelanggan_id' => $pelanggan->id,
                'periode'      => $periode,
                'jumlah'       => $jumlah,
                'status'       => 'belum_bayar',
                'jatuh_tempo'  => $jatuhTempo,
            ]);

            $dibuat++;
        }

        $this->info("Tagihan periode {$periode}: {$dibuat} dibuat, {$dilewati} sudah ada.");

        return Command::SUCCESS;
    }
}

