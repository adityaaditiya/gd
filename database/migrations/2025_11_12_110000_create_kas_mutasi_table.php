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
        Schema::create('kas_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_lelang_id')
                ->nullable()
                ->constrained('jadwal_lelang')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('referensi');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->decimal('jumlah', 15, 2);
            $table->string('sumber')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_mutasi');
    }
};
