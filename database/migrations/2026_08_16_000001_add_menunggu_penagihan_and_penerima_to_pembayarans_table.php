<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status 'menunggu_penagihan' ke enum status
        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'menunggu_penagihan', 'lunas', 'ditolak') DEFAULT 'belum_bayar'");

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('diterima_oleh')->nullable()->after('dibayar_at');
            $table->foreignId('diterima_oleh_id')->nullable()->after('diterima_oleh')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diterima_oleh_id');
            $table->dropColumn(['diterima_oleh']);
        });

        DB::statement("ALTER TABLE pembayarans MODIFY COLUMN status ENUM('belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak') DEFAULT 'belum_bayar'");
    }
};

