<?php

namespace App\Console\Commands;

use App\Models\JadwalLelang;
use App\Models\TransaksiGadai;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FlagLelangCandidates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lelang:flag-candidates {--dry-run : Tampilkan perubahan tanpa menyimpannya}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menandai transaksi jatuh tempo sebagai siap lelang dan membuat jadwal awal untuk setiap barang jaminan.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();
        $dryRun = $this->option('dry-run');
        $updatedContracts = 0;
        $createdSchedules = 0;

        $query = TransaksiGadai::query()
            ->whereIn('status_transaksi', ['Aktif', 'Perpanjang'])
            ->whereDate('jatuh_tempo_awal', '<', $today)
            ->with('barangJaminan');

        $query->chunkById(50, function ($transaksis) use (&$updatedContracts, &$createdSchedules, $today, $dryRun) {
            foreach ($transaksis as $transaksi) {
                DB::transaction(function () use ($transaksi, $today, $dryRun, &$updatedContracts, &$createdSchedules) {
                    $needsStatusUpdate = $transaksi->status_transaksi !== 'Siap Lelang';

                    if ($needsStatusUpdate) {
                        $updatedContracts++;

                        if (!$dryRun) {
                            $transaksi->forceFill([
                                'status_transaksi' => 'Siap Lelang',
                            ])->saveQuietly();
                        }
                    }

                    foreach ($transaksi->barangJaminan as $barang) {
                        $hasActiveSchedule = $barang->jadwalLelang()
                            ->where('status', 'Siap Lelang')
                            ->exists();

                        if ($hasActiveSchedule) {
                            continue;
                        }

                        $createdSchedules++;

                        if ($dryRun) {
                            continue;
                        }

                        JadwalLelang::create([
                            'barang_id' => $barang->barang_id,
                            'transaksi_id' => $transaksi->transaksi_id,
                            'tanggal_rencana' => $today->copy()->addDays(7),
                            'lokasi' => 'Gudang Pusat',
                            'petugas' => null,
                            'harga_limit' => $barang->nilai_taksiran,
                            'estimasi_biaya' => 0,
                            'status' => 'Siap Lelang',
                        ]);
                    }
                });
            }
        }, 'transaksi_id');

        $this->info(sprintf('Transaksi diperbarui: %d | Jadwal baru: %d', $updatedContracts, $createdSchedules));

        if ($dryRun) {
            $this->warn('Perintah dijalankan dalam mode dry-run, tidak ada perubahan yang disimpan.');
        }

        return self::SUCCESS;
    }
}
