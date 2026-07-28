<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        return view('pelanggan.profile', compact('user', 'pelanggan'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $pelanggan = Pelanggan::where('user_id', $user->id)->first();

        // Validasi
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ];

        $validated = $request->validate($rules);

        // Update user (name & email)
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update pelanggan jika ada
        if ($pelanggan) {
            $pelangganData = [
                'nama'   => $validated['name'],
                'no_hp'  => $validated['no_hp'] ?? $pelanggan->no_hp,
                'alamat' => $validated['alamat'] ?? $pelanggan->alamat,
            ];

            // Upload foto profil
            if ($request->hasFile('foto_profil')) {
                $request->validate([
                    'foto_profil' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
                ]);

                // Hapus foto lama jika ada
                if ($pelanggan->foto_profil && Storage::disk('public')->exists($pelanggan->foto_profil)) {
                    Storage::disk('public')->delete($pelanggan->foto_profil);
                }

                $path = $request->file('foto_profil')->store('foto_profil', 'public');
                $pelangganData['foto_profil'] = $path;
            }

            $pelanggan->update($pelangganData);
        }

        return redirect()->route('pelanggan.profile')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('pelanggan.profile')
            ->with('status', 'Kata sandi berhasil diperbarui.');
    }
}

