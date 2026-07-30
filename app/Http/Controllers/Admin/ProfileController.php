<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return view('admin.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp'   => ['nullable', 'string', 'max:20'],
            'alamat'  => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            $request->validate([
                'foto_profil' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            ]);

            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')->store('foto_profil_admin', 'public');
            $user->update(['foto_profil' => $path]);
        }

        return redirect()->route('admin.profile.show')
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

        return redirect()->route('admin.profile.show')
            ->with('status', 'Kata sandi berhasil diperbarui.');
    }
}
