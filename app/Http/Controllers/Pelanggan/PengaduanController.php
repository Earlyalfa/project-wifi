<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pengaduans = Pengaduan::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('pelanggan.pengaduan', compact('pengaduans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_gangguan' => 'required|string',
            'judul_keluhan'  => 'required|string|max:100',
            'deskripsi'      => 'required|string',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'user_id'        => Auth::id(),
            'jenis_gangguan' => $request->jenis_gangguan,
            'judul_keluhan'  => $request->judul_keluhan,
            'deskripsi'      => $request->deskripsi,
            'status'         => 'Menunggu',
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create($data);

        return redirect()->route('pelanggan.pengaduan')
            ->with('success', 'Pengaduan berhasil dikirim!');
    }

    public function show(Pengaduan $pengaduan)
    {
        // Pastikan hanya pemiliknya yang bisa lihat
        if ($pengaduan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('pelanggan.pengaduan-detail', compact('pengaduan'));
    }

    /**
     * Ambil notifikasi untuk pelanggan (via AJAX).
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id'         => $n->id,
                    'icon'       => $n->icon ?? 'bell',
                    'color'      => $n->color ?? 'indigo',
                    'message'    => $n->message,
                    'url'        => $n->url,
                    'is_read'    => $n->is_read,
                    'time_ago'   => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'unread_count'  => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Tandai satu notifikasi sudah dibaca.
     */
    public function markRead($id)
    {
        $notif = Notification::where('id', $id)->where('user_id', Auth::id())->first();
        if ($notif) {
            $notif->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sudah dibaca.
     */
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}

