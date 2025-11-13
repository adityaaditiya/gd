<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cicil_emas_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained('cicil_emas_transactions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kode_barcode')->nullable();
            $table->string('nama_barang');
            $table->string('kode_intern')->nullable();
            $table->string('kode_group')->nullable();
            $table->decimal('berat', 10, 3)->default(0);
            $table->decimal('harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cicil_emas_transaction_items');
    }
};
