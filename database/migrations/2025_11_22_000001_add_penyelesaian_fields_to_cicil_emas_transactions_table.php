<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_market_price')) {
                $table->double('penyelesaian_market_price')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_purchase_price')) {
                $table->double('penyelesaian_purchase_price')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_penalty_amount')) {
                $table->double('penyelesaian_penalty_amount')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_down_payment')) {
                $table->double('penyelesaian_down_payment')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_pokok_bersih')) {
                $table->double('penyelesaian_pokok_bersih')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_total_margin')) {
                $table->double('penyelesaian_total_margin')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_total_harga_jual')) {
                $table->double('penyelesaian_total_harga_jual')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_surplus_defisit')) {
                $table->double('penyelesaian_surplus_defisit')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_kewajiban_pengembalian')) {
                $table->double('penyelesaian_kewajiban_pengembalian')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_keterangan')) {
                $table->text('penyelesaian_keterangan')->nullable();
            }

            if (! Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_completed_at')) {
                $table->timestamp('penyelesaian_completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_completed_at')) {
                $table->dropColumn('penyelesaian_completed_at');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_keterangan')) {
                $table->dropColumn('penyelesaian_keterangan');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_kewajiban_pengembalian')) {
                $table->dropColumn('penyelesaian_kewajiban_pengembalian');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_surplus_defisit')) {
                $table->dropColumn('penyelesaian_surplus_defisit');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_total_harga_jual')) {
                $table->dropColumn('penyelesaian_total_harga_jual');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_total_margin')) {
                $table->dropColumn('penyelesaian_total_margin');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_pokok_bersih')) {
                $table->dropColumn('penyelesaian_pokok_bersih');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_down_payment')) {
                $table->dropColumn('penyelesaian_down_payment');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_penalty_amount')) {
                $table->dropColumn('penyelesaian_penalty_amount');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_purchase_price')) {
                $table->dropColumn('penyelesaian_purchase_price');
            }

            if (Schema::hasColumn('cicil_emas_transactions', 'penyelesaian_market_price')) {
                $table->dropColumn('penyelesaian_market_price');
            }
        });
    }
};

