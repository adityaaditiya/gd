<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE transaksi_gadai MODIFY status_transaksi ENUM('Aktif','Lunas','Perpanjang','Lelang','Batal') DEFAULT 'Aktif'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TYPE status_transaksi ADD VALUE IF NOT EXISTS 'Batal'");
        }

        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->timestamp('tanggal_batal')->nullable()->after('status_transaksi');
            $table->text('alasan_batal')->nullable()->after('tanggal_batal');
            $table->foreignId('pegawai_pembatal_id')
                ->nullable()
                ->after('alasan_batal')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropForeign(['pegawai_pembatal_id']);
            $table->dropColumn(['pegawai_pembatal_id', 'alasan_batal', 'tanggal_batal']);
        });

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE transaksi_gadai MODIFY status_transaksi ENUM('Aktif','Lunas','Perpanjang','Lelang') DEFAULT 'Aktif'");
        }
    }
};
