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
        if (! Schema::hasTable('barangs')) {
            return;
        }

        $shouldAddKadar = ! Schema::hasColumn('barangs', 'kadar');
        $shouldAddKodeGroup = ! Schema::hasColumn('barangs', 'kode_group');

        if (! $shouldAddKadar && ! $shouldAddKodeGroup) {
            return;
        }

        Schema::table('barangs', function (Blueprint $table) use ($shouldAddKadar, $shouldAddKodeGroup) {
            if ($shouldAddKadar) {
                $table->decimal('kadar', 5, 2)->nullable()->after('harga');
            }

            if ($shouldAddKodeGroup) {
                $table->string('kode_group')->nullable()->after('kadar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('barangs')) {
            return;
        }

        Schema::table('barangs', function (Blueprint $table) {
            if (Schema::hasColumn('barangs', 'kode_group')) {
                $table->dropColumn('kode_group');
            }

            if (Schema::hasColumn('barangs', 'kadar')) {
                $table->dropColumn('kadar');
            }
        });
    }
};
