<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpanjangan_gadai', function (Blueprint $table) {
            $table->id('perpanjangan_id');
            $table->unsignedBigInteger('transaksi_gadai_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->dateTime('tanggal_perpanjangan');
            $table->date('tanggal_mulai_baru');
            $table->date('tanggal_jatuh_tempo_baru');
            $table->integer('tenor_sebelumnya');
            $table->integer('tenor_baru');
            $table->decimal('bunga_dibayar', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->decimal('biaya_titip', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('pokok_pinjaman', 15, 2);
            $table->date('tanggal_mulai_sebelumnya');
            $table->date('tanggal_jatuh_tempo_sebelumnya')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('transaksi_gadai_id')
                ->references('transaksi_id')
                ->on('transaksi_gadai')
                ->cascadeOnDelete();

            $table->foreign('pegawai_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();

            $table->index('tanggal_perpanjangan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan_gadai');
    }
};
