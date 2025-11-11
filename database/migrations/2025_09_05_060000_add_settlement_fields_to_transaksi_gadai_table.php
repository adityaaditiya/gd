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
            $table->timestamp('tanggal_pelunasan')->nullable()->after('pegawai_pembatal_id');
            $table->decimal('pokok_dibayar', 15, 2)->default(0)->after('tanggal_pelunasan');
            $table->decimal('bunga_dibayar', 15, 2)->default(0)->after('pokok_dibayar');
            $table->decimal('biaya_lain_dibayar', 15, 2)->default(0)->after('bunga_dibayar');
            $table->decimal('total_pelunasan', 15, 2)->default(0)->after('biaya_lain_dibayar');
            $table->string('metode_pembayaran', 100)->nullable()->after('total_pelunasan');
            $table->text('catatan_pelunasan')->nullable()->after('metode_pembayaran');
            $table->foreignId('pegawai_pelunasan_id')
                ->nullable()
                ->after('catatan_pelunasan')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropForeign(['pegawai_pelunasan_id']);
            $table->dropColumn([
                'pegawai_pelunasan_id',
                'catatan_pelunasan',
                'metode_pembayaran',
                'total_pelunasan',
                'biaya_lain_dibayar',
                'bunga_dibayar',
                'pokok_dibayar',
                'tanggal_pelunasan',
            ]);
        });
    }
};
