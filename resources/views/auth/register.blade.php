@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <h2 class="text-xl font-bold text-slate-800 mb-6">Buat Akun</h2>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST" class="flex flex-col gap-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required autofocus>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="Masukkan kata sandi"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Masukkan ulang kata sandi"
                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-full transition mt-2">
            Daftar
        </button>

        <p class="text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-500 font-semibold hover:underline">Masuk</a>
        </p>
    </form>
@endsection
