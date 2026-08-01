<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->tinyInteger('tagihan_jatuh_tempo')->unsigned()->nullable()->after('paket')->comment('Tanggal jatuh tempo tagihan per pelanggan (1-31)');
        });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropColumn('tagihan_jatuh_tempo');
        });
    }
};

