<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Role ada di $fillable agar admin bisa mengubah role user.
     * Route updateRole sudah dilindungi middleware 'role:admin'.
     * RegisterController tidak mengirim input role, jadi user biasa tidak bisa mengubah role sendiri.
     */
protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'provider',
        'provider_id',
        'status',
        'foto_profil',
        'no_hp',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}

