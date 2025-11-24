<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_perhitungan_gadai', function (Blueprint $table) {
            $table->string('skema_bunga')->default('harian')->after('tarif_bunga_harian');
            $table->decimal('tarif_bunga_per_periode', 8, 5)->nullable()->after('skema_bunga');
            $table->unsignedInteger('periode_hari')->nullable()->after('tarif_bunga_per_periode');

            $table->decimal('tarif_bunga_harian', 8, 5)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('master_perhitungan_gadai', function (Blueprint $table) {
            $table->decimal('tarif_bunga_harian', 8, 5)->nullable(false)->change();
            $table->dropColumn(['skema_bunga', 'tarif_bunga_per_periode', 'periode_hari']);
        });
    }
};
