<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id', 'periode', 'jumlah', 'kode_unik', 'total_bayar', 'status',
        'jatuh_tempo', 'metode_pembayaran', 'bukti_bayar', 'catatan', 'tanggal_bayar', 'dibayar_at',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'dibayar_at' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
