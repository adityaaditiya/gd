<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'administrasi')) {
                $table->decimal('administrasi', 15, 2)
                    ->default(0)
                    ->after('margin_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'administrasi')) {
                $table->dropColumn('administrasi');
            }
        });
    }
};
