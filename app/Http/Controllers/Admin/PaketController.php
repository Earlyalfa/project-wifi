<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Notification;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::latest()->paginate(10);
        return view('admin.paket.index', compact('pakets'));
    }

    public function create()
    {
        return view('admin.paket.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'harga'      => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'deskripsi'  => ['nullable', 'string', 'max:1000'],
            'status'     => ['required', 'in:aktif,nonaktif'],
        ]);

        Paket::create($validated);

        // Notifikasi
        Notification::create([
            'user_id' => 1,
            'type'    => 'paket',
            'icon'    => 'signal',
            'color'   => 'emerald',
            'message' => 'Paket baru ditambahkan: ' . $validated['nama'] . ' (Rp ' . number_format($validated['harga'], 0, ',', '.') . ')',
            'url'     => route('admin.paket.index'),
        ]);

        return redirect()->route('admin.paket.index')
            ->with('status', 'Paket berhasil ditambahkan.');
    }

    public function edit(Paket $paket)
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, Paket $paket)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'harga'      => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'deskripsi'  => ['nullable', 'string', 'max:1000'],
            'status'     => ['required', 'in:aktif,nonaktif'],
        ]);

        $paket->update($validated);

        return redirect()->route('admin.paket.index')
            ->with('status', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();

        return redirect()->route('admin.paket.index')
            ->with('status', 'Paket berhasil dihapus.');
    }
}

