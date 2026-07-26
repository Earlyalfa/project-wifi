@extends('layouts.guest')

@section('title', 'Log In')

@section('content')
    <h2 class="text-xl font-bold text-slate-800 mb-6">Log In</h2>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Form Login Manual --}}
    <form action="{{ url('/login') }}" method="POST" class="flex flex-col gap-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required autofocus>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Masukkan kata sandi"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required>
        </div>

        <div class="text-right -mt-2">
            <a href="#" class="text-xs text-indigo-500 hover:underline">Lupa kata sandi?</a>
        </div>

        <button type="submit"
                class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-full transition mt-2">
            Masuk
        </button>

    {{-- Pemisah --}}
    <div class="flex items-center gap-3 mb-4">
        <div class="flex-1 h-px bg-slate-200"></div>
        <span class="text-xs text-slate-400 font-medium">Atau masuk dengan</span>
        <div class="flex-1 h-px bg-slate-200"></div>
    </div>

    {{-- Login dengan Google --}}
    <a href="{{ route('login.google') }}"
       class="w-full flex items-center justify-center gap-3 border border-slate-200 rounded-full px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition mb-4">
        <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Google
    </a>
    
        <p class="text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-500 font-semibold hover:underline">Daftar</a>
        </p>
    </form>
@endsection
