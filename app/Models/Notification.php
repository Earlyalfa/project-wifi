<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'icon',
        'color',
        'message',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a notification for all pegawai/admin users.
     */
    public static function notifyPegawai(string $type, string $message, ?string $url = null, string $icon = 'bell', string $color = 'indigo')
    {
        $users = User::whereIn('role', ['pegawai', 'admin'])->get();
        foreach ($users as $user) {
            static::create([
                'user_id' => $user->id,
                'type'    => $type,
                'icon'    => $icon,
                'color'   => $color,
                'message' => $message,
                'url'     => $url,
            ]);
        }
    }
}

