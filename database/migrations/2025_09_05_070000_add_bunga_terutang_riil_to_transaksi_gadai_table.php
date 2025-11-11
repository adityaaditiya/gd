<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->decimal('bunga_terutang_riil', 10, 2)->default(0)->after('total_bunga');
        });

        DB::statement(<<<'SQL'
            UPDATE transaksi_gadai
            SET bunga_terutang_riil = CASE
                WHEN status_transaksi = 'Batal' OR tanggal_gadai IS NULL OR uang_pinjaman IS NULL THEN 0
                ELSE ROUND(
                    uang_pinjaman *
                    (CASE WHEN tarif_bunga_harian IS NULL OR tarif_bunga_harian <= 0 THEN 0.0015 ELSE tarif_bunga_harian END) *
                    GREATEST(1, DATEDIFF(COALESCE(DATE(tanggal_pelunasan), CURRENT_DATE), tanggal_gadai) + 1),
                    2
                )
            END
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropColumn('bunga_terutang_riil');
        });
    }
};
