<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
                $table->string('nomor_cicilan', 13)
                    ->nullable()
                    ->after('id')
                    ->unique('cicil_emas_transactions_nomor_cicilan_unique');
            }
        });

        if (Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
            $timezone = config('app.timezone', 'UTC');
            $counters = [];

            DB::table('cicil_emas_transactions')
                ->select('id', 'created_at')
                ->whereNull('nomor_cicilan')
                ->orderBy('created_at')
                ->orderBy('id')
                ->cursor()
                ->each(function ($row) use (&$counters, $timezone) {
                    if (! $row->created_at) {
                        return;
                    }

                    $date = Carbon::parse($row->created_at)->setTimezone($timezone);
                    $prefix = 'GE03'.$date->format('ymd');
                    $counters[$prefix] = ($counters[$prefix] ?? 0) + 1;
                    $sequence = str_pad((string) $counters[$prefix], 3, '0', STR_PAD_LEFT);

                    DB::table('cicil_emas_transactions')
                        ->where('id', $row->id)
                        ->update(['nomor_cicilan' => $prefix.$sequence]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('cicil_emas_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
                $table->dropUnique('cicil_emas_transactions_nomor_cicilan_unique');
                $table->dropColumn('nomor_cicilan');
            }
        });
    }
};
