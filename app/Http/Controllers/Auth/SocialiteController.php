<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect ke Google untuk login pegawai.
     */
    public function redirectToGooglePegawai()
    {
        session(['login_intended' => 'pegawai']);
        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect ke Google untuk login user umum.
     */
    public function redirectToGoogle()
    {
        session(['login_intended' => 'user']);
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login Google gagal. Silakan coba lagi.',
            ]);
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('email', $googleUser->email)->first();

        if (! $user) {
            // User baru — buat akun
            $user = User::create([
                'name'              => $googleUser->name ?? $googleUser->nickname ?? 'User',
                'email'             => $googleUser->email,
                'password'          => Hash::make(Str::random(32)), // password random, tidak dipakai
                'provider'          => 'google',
                'provider_id'       => $googleUser->id,
                'role'              => 'pelanggan', // default pelanggan
                'email_verified_at' => now(),       // Google sudah verifikasi email
            ]);
        } else {
            // Update provider_id kalau belum ada
            if (! $user->provider_id) {
                $user->update([
                    'provider'    => 'google',
                    'provider_id' => $googleUser->id,
                ]);
            }
        }

        // Cek intended route
        $intended = session('login_intended', 'user');

        if ($intended === 'pegawai') {
            // Pegawai: harus role pegawai/admin
            if (! in_array($user->role, ['pegawai', 'admin'], true)) {
                return redirect()->route('pegawai.login')
                    ->withErrors(['username' => 'Akun Google ini bukan akun pegawai.']);
            }
            Auth::login($user);
            return redirect()->intended(route('pegawai.dashboard'));
        }

        // User umum
        Auth::login($user);
        return redirect()->intended(route('dashboard'));
    }
}

