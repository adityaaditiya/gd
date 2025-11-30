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
            if (! Schema::hasColumn('jadwal_lelang', 'status_pembayaran_nasabah')) {
                $table->string('status_pembayaran_nasabah')->nullable()->after('piutang_sisa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lelang', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal_lelang', 'status_pembayaran_nasabah')) {
                $table->dropColumn('status_pembayaran_nasabah');
            }
        });
    }
};
