<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'dibatalkan_pada')) {
                $table->timestamp('dibatalkan_pada')->nullable()->after('option_label');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable()->after('dibatalkan_pada');
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'dibatalkan_oleh')) {
                $table->foreignId('dibatalkan_oleh')
                    ->nullable()
                    ->after('alasan_pembatalan')
                    ->constrained('users')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'dibatalkan_oleh')) {
                $table->dropForeign(['dibatalkan_oleh']);
                $table->dropColumn('dibatalkan_oleh');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'alasan_pembatalan')) {
                $table->dropColumn('alasan_pembatalan');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'dibatalkan_pada')) {
                $table->dropColumn('dibatalkan_pada');
            }
        });
    }
};
