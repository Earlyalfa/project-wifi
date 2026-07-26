<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('pegawai.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'], // diisi email user
            'password' => ['required'],
        ]);

        $attempt = [
            'email' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        if (! Auth::attempt($attempt, $request->boolean('remember'))) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
        }

        $user = Auth::user();

        // Pastikan yang login lewat pintu pegawai memang role-nya pegawai/admin
        if (! in_array($user->role, ['pegawai', 'admin'], true)) {
            Auth::logout();
            return back()->withErrors([
                'username' => 'Akun ini bukan akun pegawai. Silakan login lewat halaman pelanggan.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('pegawai.dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

return redirect('/');
    }
}