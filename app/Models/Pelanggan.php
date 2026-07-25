<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', 'nama', 'paket', 'alamat', 'no_hp', 'foto_rumah', 'status',
    ];

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class)->latest('waktu_kunjungan');
    }

    /**
     * Tagihan yang statusnya masih "belum_bayar", terbaru dulu.
     */
    public function tagihanBelumBayar()
    {
        return $this->hasMany(Pembayaran::class)
            ->where('status', 'belum_bayar')
            ->latest();
    }
}
