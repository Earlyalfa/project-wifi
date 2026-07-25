<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id', 'periode', 'jumlah', 'status',
        'jatuh_tempo', 'metode_pembayaran', 'dibayar_at',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'dibayar_at' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
