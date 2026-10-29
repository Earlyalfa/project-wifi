<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->foreignId('paket_id')
                ->nullable()
                ->after('user_id')
                ->constrained('pakets')
                ->nullOnDelete();
        });

        DB::table('pelanggans')
            ->whereNotNull('paket')
            ->orderBy('id')
            ->chunkById(100, function ($pelanggans) {
                foreach ($pelanggans as $pelanggan) {
                    $paketId = DB::table('pakets')
                        ->where('nama', $pelanggan->paket)
                        ->orderBy('id')
                        ->value('id');

                    if ($paketId) {
                        DB::table('pelanggans')
                            ->where('id', $pelanggan->id)
                            ->update(['paket_id' => $paketId]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->dropForeign(['paket_id']);
            $table->dropColumn('paket_id');
        });
    }
};