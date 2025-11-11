<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->decimal('uang_cair', 15, 2)->nullable()->after('uang_pinjaman');
        });

        DB::table('transaksi_gadai')
            ->select(['transaksi_id', 'uang_pinjaman', 'biaya_admin', 'premi'])
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    $principal = (float) ($row->uang_pinjaman ?? 0);
                    $admin = (float) ($row->biaya_admin ?? 0);
                    $premi = (float) ($row->premi ?? 0);

                    $net = max(0, $principal - ($admin + $premi));

                    DB::table('transaksi_gadai')
                        ->where('transaksi_id', $row->transaksi_id)
                        ->update([
                            'uang_cair' => number_format($net, 2, '.', ''),
                        ]);
                }
            }, 'transaksi_id');
    }

    public function down(): void
    {
        Schema::table('transaksi_gadai', function (Blueprint $table) {
            $table->dropColumn('uang_cair');
        });
    }
};
