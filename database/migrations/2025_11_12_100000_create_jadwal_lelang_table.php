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
        Schema::create('jadwal_lelang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                ->constrained('barang_jaminan', 'barang_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('transaksi_id')
                ->constrained('transaksi_gadai', 'transaksi_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('tanggal_rencana')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('petugas')->nullable();
            $table->decimal('harga_limit', 15, 2)->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->nullable();
            $table->enum('status', ['Siap Lelang', 'Selesai', 'Butuh Penjadwalan Ulang'])->default('Siap Lelang');
            $table->text('catatan')->nullable();
            $table->enum('hasil_status', ['laku', 'tidak_laku'])->nullable();
            $table->decimal('harga_laku', 15, 2)->nullable();
            $table->decimal('biaya_lelang', 15, 2)->nullable();
            $table->decimal('distribusi_perusahaan', 15, 2)->nullable();
            $table->decimal('distribusi_nasabah', 15, 2)->nullable();
            $table->decimal('piutang_sisa', 15, 2)->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_lelang');
    }
};
