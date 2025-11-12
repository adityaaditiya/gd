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

        $shouldAddKodeGroup = !Schema::hasColumn('barangs', 'kode_group');
        $shouldAddBerat = !Schema::hasColumn('barangs', 'berat');

        if (! $shouldAddKodeGroup && ! $shouldAddBerat) {
            return;
        }

        Schema::table('barangs', function (Blueprint $table) use ($shouldAddKodeGroup, $shouldAddBerat) {
            if ($shouldAddKodeGroup) {
                $table->string('kode_group')->default('')->after('kode_intern')->index();
            }

            if ($shouldAddBerat) {
                $table->decimal('berat', 8, 3)->default(0)->after('kode_group');
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

        if (Schema::hasColumn('barangs', 'kode_group')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('kode_group');
            });
        }
    }
};
