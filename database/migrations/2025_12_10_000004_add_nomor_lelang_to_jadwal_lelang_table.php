<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_lelang', function (Blueprint $table) {
            if (! Schema::hasColumn('jadwal_lelang', 'nomor_lelang')) {
                $table->string('nomor_lelang', 13)->nullable()->unique()->after('transaksi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lelang', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal_lelang', 'nomor_lelang')) {
                $table->dropUnique('jadwal_lelang_nomor_lelang_unique');
                $table->dropColumn('nomor_lelang');
            }
        });
    }
};
