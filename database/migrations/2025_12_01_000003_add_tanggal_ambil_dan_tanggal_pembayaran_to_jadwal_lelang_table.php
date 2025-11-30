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
            if (! Schema::hasColumn('jadwal_lelang', 'tanggal_ambil')) {
                $table->date('tanggal_ambil')->nullable()->after('status_pembayaran_nasabah');
            }

            if (! Schema::hasColumn('jadwal_lelang', 'tanggal_pembayaran')) {
                $table->date('tanggal_pembayaran')->nullable()->after('tanggal_ambil');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lelang', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal_lelang', 'tanggal_pembayaran')) {
                $table->dropColumn('tanggal_pembayaran');
            }

            if (Schema::hasColumn('jadwal_lelang', 'tanggal_ambil')) {
                $table->dropColumn('tanggal_ambil');
            }
        });
    }
};
