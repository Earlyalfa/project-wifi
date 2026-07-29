<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum status
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak') DEFAULT 'belum_bayar'");

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->integer('kode_unik')->nullable()->after('jumlah');
            $table->integer('total_bayar')->nullable()->after('kode_unik');
            $table->string('bukti_bayar')->nullable()->after('metode_pembayaran');
            $table->text('catatan')->nullable()->after('bukti_bayar');
            $table->date('tanggal_bayar')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('belum_bayar', 'lunas') DEFAULT 'belum_bayar'");

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['kode_unik', 'total_bayar', 'bukti_bayar', 'catatan', 'tanggal_bayar']);
        });
    }
};

