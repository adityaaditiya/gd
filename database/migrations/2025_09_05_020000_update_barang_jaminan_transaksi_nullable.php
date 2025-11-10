<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_jaminan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
        });

        DB::statement('ALTER TABLE barang_jaminan MODIFY transaksi_id BIGINT UNSIGNED NULL');

        Schema::table('barang_jaminan', function (Blueprint $table) {
            $table->foreign('transaksi_id')
                ->references('transaksi_id')
                ->on('transaksi_gadai')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('barang_jaminan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
        });

        if (DB::table('barang_jaminan')->whereNull('transaksi_id')->exists()) {
            throw new RuntimeException('Tidak dapat mengembalikan kolom transaksi_id menjadi wajib selama masih ada barang tanpa kontrak.');
        }
        DB::statement('ALTER TABLE barang_jaminan MODIFY transaksi_id BIGINT UNSIGNED NOT NULL');

        Schema::table('barang_jaminan', function (Blueprint $table) {
            $table->foreign('transaksi_id')
                ->references('transaksi_id')
                ->on('transaksi_gadai')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};
