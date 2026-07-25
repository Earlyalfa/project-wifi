<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['tagihan_dibayar', 'belum_bayar'])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamp('waktu_kunjungan')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
