<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'nomor_pelunasan')) {
                $table->string('nomor_pelunasan')->nullable()->unique()->after('nomor_cicilan');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'tanggal_pelunasan')) {
                $table->dateTime('tanggal_pelunasan')->nullable()->after('nomor_pelunasan');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'biaya_ongkos_kirim')) {
                $table->decimal('biaya_ongkos_kirim', 15, 2)->nullable()->after('tanggal_pelunasan');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'pelunasan_dipercepat')) {
                $table->boolean('pelunasan_dipercepat')->default(false)->after('biaya_ongkos_kirim');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'pelunasan_dipercepat')) {
                $table->dropColumn('pelunasan_dipercepat');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'biaya_ongkos_kirim')) {
                $table->dropColumn('biaya_ongkos_kirim');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'tanggal_pelunasan')) {
                $table->dropColumn('tanggal_pelunasan');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'nomor_pelunasan')) {
                $table->dropColumn('nomor_pelunasan');
            }
        });
    }
};
