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
        Schema::table('kas_mutasi', function (Blueprint $table) {
            $table->foreignId('transaksi_gadai_id')
                ->nullable()
                ->after('id')
                ->constrained('transaksi_gadai', 'transaksi_id')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_mutasi', function (Blueprint $table) {
            $table->dropConstrainedForeignId('transaksi_gadai_id');
        });
    }
};
