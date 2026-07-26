<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('pegawai.settings.index');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'notif_gangguan'   => ['boolean'],
            'notif_pembayaran' => ['boolean'],
        ]);

        // Simpan preferensi ke database user atau session
        // Untuk sementara pakai session
        session([
            'settings.notif_gangguan'   => $request->boolean('notif_gangguan'),
            'settings.notif_pembayaran' => $request->boolean('notif_pembayaran'),
        ]);

        return redirect()->route('pegawai.settings')
            ->with('status', 'Pengaturan berhasil disimpan.');
    }
}
