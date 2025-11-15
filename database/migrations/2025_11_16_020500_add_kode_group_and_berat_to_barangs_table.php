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
        if (!Schema::hasTable('barangs')) {
            return;
        }

        $shouldAddKodeBaki = !Schema::hasColumn('barangs', 'kode_baki');
        $shouldAddBerat = !Schema::hasColumn('barangs', 'berat');

        if (! $shouldAddKodeBaki && ! $shouldAddBerat) {
            return;
        }

        Schema::table('barangs', function (Blueprint $table) use ($shouldAddKodeBaki, $shouldAddBerat) {
            if ($shouldAddKodeBaki) {
                $table->string('kode_baki')->default('')->after('kode_intern')->index();
            }

            if ($shouldAddBerat) {
                $table->decimal('berat', 8, 3)->default(0)->after('kode_baki');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('barangs')) {
            return;
        }

        if (Schema::hasColumn('barangs', 'berat')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('berat');
            });
        }

        if (Schema::hasColumn('barangs', 'kode_baki')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('kode_baki');
            });
        }
    }
};
