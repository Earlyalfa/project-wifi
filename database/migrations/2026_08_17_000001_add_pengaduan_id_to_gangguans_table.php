<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gangguans', function (Blueprint $table) {
            $table->foreignId('pengaduan_id')->nullable()->after('id')->constrained('pengaduans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gangguans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pengaduan_id');
        });
    }
};

