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
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->unsignedInteger('tenor_hari')->default(0)->after('jatuh_tempo_awal');
            $table->decimal('tarif_bunga_harian', 5, 4)->default(0.0015)->after('tenor_hari');
            $table->decimal('total_bunga', 15, 2)->default(0)->after('tarif_bunga_harian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropColumn(['tenor_hari', 'tarif_bunga_harian', 'total_bunga']);
        });
    }
};
