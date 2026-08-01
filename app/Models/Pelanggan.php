<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

protected $fillable = [
        'user_id', 'kode', 'nama', 'email', 'paket', 'alamat', 'no_hp', 'foto_rumah', 'foto_profil', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
