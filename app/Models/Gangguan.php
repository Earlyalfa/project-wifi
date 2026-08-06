<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gangguan extends Model
{
    use HasFactory;

protected $fillable = [
        'no_tiket',
        'pelanggan_id',
        'pengaduan_id',
        'keluhan',
        'tanggal_lapor',
        'status',
    ];

    protected $casts = [
        'tanggal_lapor' => 'datetime',
    ];

public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    /**
     * Generate nomor tiket otomatis (TKT-XXXX).
     */
    public static function generateNoTiket()
    {
        $last = static::latest()->first();
        if (! $last) {
            return 'TKT-0001';
        }
        $lastNumber = (int) substr($last->no_tiket, 4);
        return 'TKT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}

