<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Schema;
use Illuminate\\Support\\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
            Schema::table('cicil_emas_transactions', function (Blueprint $table) {
                $table->string('nomor_cicilan', 13)->unique()->after('id');
            });
        }

        if (! Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
            return;
        }

        $counters = [];

        DB::table('cicil_emas_transactions')
            ->orderBy('created_at')
            ->orderBy('id')
            ->chunk(100, function ($rows) use (&$counters) {
                foreach ($rows as $row) {
                    if (! empty($row->nomor_cicilan)) {
                        continue;
                    }

                    $createdAt = $row->created_at ? Carbon::parse($row->created_at) : Carbon::now();
                    $dateKey = $createdAt->toDateString();
                    $sequence = ($counters[$dateKey] ?? 0) + 1;
                    $counters[$dateKey] = $sequence;

                    $number = sprintf('GE03%s%03d', $createdAt->format('ymd'), $sequence);

                    DB::table('cicil_emas_transactions')
                        ->where('id', $row->id)
                        ->update(['nomor_cicilan' => $number]);
                }
            });
    }

    public function down(): void
    {
        if (Schema::hasColumn('cicil_emas_transactions', 'nomor_cicilan')) {
            Schema::table('cicil_emas_transactions', function (Blueprint $table) {
                $table->dropUnique('cicil_emas_transactions_nomor_cicilan_unique');
                $table->dropColumn('nomor_cicilan');
            });
        }
    }
};
