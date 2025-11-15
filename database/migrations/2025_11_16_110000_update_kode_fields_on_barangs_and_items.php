<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('barangs', 'sku') && ! Schema::hasColumn('barangs', 'kode_group')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->renameColumn('sku', 'kode_group');
            });
        }

        if (! Schema::hasColumn('barangs', 'kode_jenis')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->string('kode_jenis')->default('')->after('kode_baki');
            });
        }

        if (Schema::hasTable('master_skus') && Schema::hasColumn('master_skus', 'sku') && ! Schema::hasColumn('master_skus', 'kode_group')) {
            Schema::table('master_skus', function (Blueprint $table) {
                $table->renameColumn('sku', 'kode_group');
            });
        }

        if (Schema::hasTable('cicil_emas_transaction_items')) {
            if (Schema::hasColumn('cicil_emas_transaction_items', 'kode_group') && ! Schema::hasColumn('cicil_emas_transaction_items', 'kode_baki')) {
                Schema::table('cicil_emas_transaction_items', function (Blueprint $table) {
                    $table->renameColumn('kode_group', 'kode_baki');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_skus') && Schema::hasColumn('master_skus', 'kode_group') && ! Schema::hasColumn('master_skus', 'sku')) {
            Schema::table('master_skus', function (Blueprint $table) {
                $table->renameColumn('kode_group', 'sku');
            });
        }

        if (Schema::hasColumn('barangs', 'kode_jenis')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->dropColumn('kode_jenis');
            });
        }

        if (! Schema::hasColumn('barangs', 'sku') && Schema::hasColumn('barangs', 'kode_group')) {
            Schema::table('barangs', function (Blueprint $table) {
                $table->renameColumn('kode_group', 'sku');
            });
        }

        if (Schema::hasTable('cicil_emas_transaction_items')) {
            if (! Schema::hasColumn('cicil_emas_transaction_items', 'kode_group') && Schema::hasColumn('cicil_emas_transaction_items', 'kode_baki')) {
                Schema::table('cicil_emas_transaction_items', function (Blueprint $table) {
                    $table->renameColumn('kode_baki', 'kode_group');
                });
            }
        }
    }
};
