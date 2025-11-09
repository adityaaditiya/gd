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
        Schema::create('transaksi_gadai', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->string('no_sbg')->unique();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pegawai_kasir_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('tanggal_gadai');
            $table->date('jatuh_tempo_awal');
            $table->unsignedDecimal('uang_pinjaman', 15, 2);
            $table->unsignedDecimal('biaya_admin', 15, 2)->default(0);
            $table->enum('status_transaksi', ['Aktif', 'Lunas', 'Perpanjang', 'Lelang'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_gadai');
    }
};
