<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

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
        $shouldAddSku = ! Schema::hasColumn('barangs', 'sku');

        if (! $shouldAddKadar && ! $shouldAddSku) {
            return;
        }

        Schema::table('barangs', function (Blueprint $table) use ($shouldAddKadar, $shouldAddSku) {
            if ($shouldAddKadar) {
                $table->decimal('kadar', 5, 2)->nullable()->after('harga');
            }

            if ($shouldAddSku) {
                $table->string('sku')->nullable()->after('kadar');
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
            if (Schema::hasColumn('barangs', 'sku')) {
                $table->dropColumn('sku');
            }

            if (Schema::hasColumn('barangs', 'kadar')) {
                $table->dropColumn('kadar');
            }
        });
    }
};
