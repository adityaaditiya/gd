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
        Schema::create('barang_jaminan', function (Blueprint $table) {
            $table->id('barang_id');
            $table->foreignId('transaksi_id')->constrained('transaksi_gadai', 'transaksi_id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('pegawai_penaksir_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('jenis_barang');
            $table->string('merek');
            $table->unsignedTinyInteger('usia_barang_thn')->nullable();
            $table->unsignedDecimal('hps', 15, 2);
            $table->unsignedDecimal('nilai_taksiran', 15, 2);
            $table->text('kondisi_fisik')->nullable();
            $table->string('foto_1')->nullable();
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();
            $table->string('foto_4')->nullable();
            $table->string('foto_5')->nullable();
            $table->string('foto_6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_jaminan');
    }
};
