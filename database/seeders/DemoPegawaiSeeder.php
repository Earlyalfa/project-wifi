<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh
        $pegawai = User::firstOrCreate(
            ['email' => 'pegawai@wifipay.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'pegawai',
            ]
        );

        $dataPelanggan = [
            ['kode' => 'PLG001', 'nama' => 'Andi Setiawan', 'alamat' => 'Jl. Mawar No.10, RT 03/RW 02, Bandung', 'no_hp' => '08123456789'],
            ['kode' => 'PLG002', 'nama' => 'Siti Nurhaliza', 'alamat' => 'Jl. Kenanga No. 5, Bandung', 'no_hp' => '081298765432'],
            ['kode' => 'PLG003', 'nama' => 'Budi Santoso', 'alamat' => 'Jl. Melati No. 8, Bandung', 'no_hp' => '081311112222'],
            ['kode' => 'PLG004', 'nama' => 'Dewi Lestari', 'alamat' => 'Jl. Anggrek No. 3, Bandung', 'no_hp' => '085744443333'],
        ];

        // Riwayat pembayaran 3 bulan terakhir per pelanggan (bulan terbaru = belum bayar)
        $riwayat = [
            ['periode' => 'Mei 2024', 'jatuh_tempo' => '2024-05-25', 'status' => 'belum_bayar', 'dibayar_at' => null],
            ['periode' => 'Apr 2024', 'jatuh_tempo' => '2024-04-25', 'status' => 'belum_bayar', 'dibayar_at' => null],
            ['periode' => 'Mar 2024', 'jatuh_tempo' => '2024-03-25', 'status' => 'lunas', 'dibayar_at' => '2024-03-24'],
            ['periode' => 'Feb 2024', 'jatuh_tempo' => '2024-02-25', 'status' => 'lunas', 'dibayar_at' => '2024-02-23'],
        ];

        foreach ($dataPelanggan as $data) {
            $pelanggan = Pelanggan::firstOrCreate(['kode' => $data['kode']], $data + ['status' => 'aktif']);

            foreach ($riwayat as $r) {
                Pembayaran::firstOrCreate(
                    ['pelanggan_id' => $pelanggan->id, 'periode' => $r['periode']],
                    [
                        'jumlah' => 200000,
                        'status' => $r['status'],
                        'jatuh_tempo' => $r['jatuh_tempo'],
                        'metode_pembayaran' => 'Transfer Bank',
                        'dibayar_at' => $r['dibayar_at'],
                    ]
                );
            }

            Kunjungan::firstOrCreate(
                ['pelanggan_id' => $pelanggan->id, 'pegawai_id' => $pegawai->id],
                [
                    'status' => 'belum_bayar',
                    'catatan' => null,
                    'waktu_kunjungan' => now(),
                ]
            );
        }
    }
}
