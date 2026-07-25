@extends('layouts.guest')

@section('title', 'Selamat Datang')

@section('content')
    <div class="flex flex-col gap-4 max-w-xs mx-auto w-full">

        <a href="{{ route('login') }}"
           class="w-full text-center bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-full transition">
            Masuk
        </a>

        <div class="flex items-center gap-3 my-1">
            <div class="flex-1 h-px bg-slate-300"></div>
            <span class="text-xs text-slate-400 font-medium">ATAU</span>
            <div class="flex-1 h-px bg-slate-300"></div>
        </div>

        <a href="{{ route('register') }}"
           class="w-full text-center bg-white border-2 border-indigo-500 text-indigo-600 font-semibold py-3 rounded-full transition hover:bg-indigo-50">
            Daftar
        </a>

    </div>
@endsection