<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            $table->decimal('pokok_pembiayaan', 15, 2)->default(0)->after('estimasi_uang_muka');
            $table->decimal('margin_percentage', 5, 2)->default(0)->after('pokok_pembiayaan');
            $table->decimal('margin_amount', 15, 2)->default(0)->after('margin_percentage');
            $table->decimal('total_pembiayaan', 15, 2)->default(0)->after('margin_amount');
        });

        DB::table('cicil_emas_transactions')->select('id', 'harga_emas', 'estimasi_uang_muka', 'tenor_bulan', 'besaran_angsuran')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $principal = max((float) $row->harga_emas - (float) $row->estimasi_uang_muka, 0);
                $totalInstallments = max((float) $row->besaran_angsuran * (int) $row->tenor_bulan, 0);
                $marginAmount = max($totalInstallments - $principal, 0);
                $marginPercentage = $principal > 0 ? round(($marginAmount / $principal) * 100, 2) : 0;
                $totalPembiayaan = max($principal + $marginAmount, 0);

                DB::table('cicil_emas_transactions')
                    ->where('id', $row->id)
                    ->update([
                        'pokok_pembiayaan' => round($principal, 2),
                        'margin_percentage' => round($marginPercentage, 2),
                        'margin_amount' => round($marginAmount, 2),
                        'total_pembiayaan' => round($totalPembiayaan, 2),
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'pokok_pembiayaan',
                'margin_percentage',
                'margin_amount',
                'total_pembiayaan',
            ]);
        });
    }
};
