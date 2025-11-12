<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicil_emas_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('package_id');
            $table->string('pabrikan');
            $table->decimal('berat_gram', 8, 3);
            $table->string('kadar');
            $table->decimal('harga_emas', 15, 2);
            $table->decimal('dp_percentage', 5, 2);
            $table->decimal('estimasi_uang_muka', 15, 2);
            $table->unsignedSmallInteger('tenor_bulan');
            $table->decimal('besaran_angsuran', 15, 2);
            $table->string('option_id');
            $table->string('option_label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicil_emas_transactions');
    }
};
