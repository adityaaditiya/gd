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
            $table->foreignId('cicil_emas_transaction_id')
                ->nullable()
                ->after('transaksi_gadai_id')
                ->constrained('cicil_emas_transactions')
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
            $table->dropConstrainedForeignId('cicil_emas_transaction_id');
        });
    }
};
