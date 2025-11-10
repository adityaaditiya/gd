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
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->decimal('premi', 15, 2)->default(0)->after('biaya_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropColumn('premi');
        });
    }
};
