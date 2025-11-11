<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi_gadai MODIFY status_transaksi ENUM('Aktif','Lunas','Perpanjang','Siap Lelang','Lelang','Batal') DEFAULT 'Aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi_gadai MODIFY status_transaksi ENUM('Aktif','Lunas','Perpanjang','Lelang','Batal') DEFAULT 'Aktif'");
    }
};
