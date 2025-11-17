<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_perhitungan_gadai', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('range_awal', 15, 2);
            $table->decimal('range_akhir', 15, 2);
            $table->decimal('tarif_bunga_harian', 8, 5);
            $table->unsignedInteger('tenor_hari');
            $table->unsignedInteger('jatuh_tempo_awal');
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_perhitungan_gadai');
    }
};
