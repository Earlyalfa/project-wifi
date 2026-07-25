<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     * Kolom "role" default-nya "pelanggan".
     * Admin bisa mengubah value ini langsung lewat phpMyAdmin
     * menjadi "admin" atau "pegawai" agar user diarahkan ke dashboard yang sesuai.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pelanggan', 'admin', 'pegawai'])
                  ->default('pelanggan')
                  ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
