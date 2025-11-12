<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perpanjangan_gadai', function (Blueprint $table) {
            $table->timestamp('dibatalkan_pada')->nullable()->after('catatan');
            $table->foreignId('dibatalkan_oleh')
                ->nullable()
                ->after('dibatalkan_pada')
                ->constrained('users')
                ->nullOnDelete();
            $table->text('alasan_pembatalan')->nullable()->after('dibatalkan_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('perpanjangan_gadai', function (Blueprint $table) {
            $table->dropColumn('alasan_pembatalan');
            $table->dropConstrainedForeignId('dibatalkan_oleh');
            $table->dropColumn('dibatalkan_pada');
        });
    }
};
