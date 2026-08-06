<?php

use Illuminate\Support\Facades\Schedule;

// Pembuatan tagihan bulanan otomatis — dijalankan setiap hari
// agar pelanggan yang belum memiliki tagihan pada periode berjalan langsung dibuatkan.
Schedule::command('tagihan:generate')->daily();

