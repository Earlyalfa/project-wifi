<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained()->cascadeOnDelete();
            $table->string('periode'); // contoh: "Mei 2024"
            $table->unsignedInteger('jumlah');
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->date('jatuh_tempo')->nullable();
            $table->string('metode_pembayaran')->nullable(); // Transfer Bank, dll
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
