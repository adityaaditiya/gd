<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Penyelesaian Cicilan')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Penyelesaian Cicilan'))]); ?>
    <?php
        /** @var \Illuminate\Support\Collection<int, \App\Models\CicilEmasTransaction> $transactions */
        $today = $today ?? \Illuminate\Support\Carbon::now()->startOfDay();
    ?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Penyelesaian Cicilan')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Nasabah Gagal Bayar Cicilan Emas lebih dari 30 hari.')); ?>

            </p>
            <!-- <p class="text-xs text-neutral-500 dark:text-neutral-400">
                <?php echo e(__('Tunggakan minimal 30 hari dari angsuran terlama yang belum dibayar. Daftar diperbarui otomatis berdasarkan jadwal angsuran.')); ?>

            </p> -->
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-500/10 dark:text-red-200">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <section class="flex flex-col gap-4 rounded-xl border border-amber-200 bg-white p-5 shadow-sm dark:border-amber-400/30 dark:bg-neutral-900">
            <!-- <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-amber-500"><?php echo e(__('Deteksi Tunggakan')); ?></span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Daftar cicilan dengan angsuran terlama belum dibayar (> 30 hari)')); ?></h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Pilih transaksi yang akan diselesaikan. Pastikan penilaian harga pasar emas terbaru dan keterangan dicatat sebelum status berubah menjadi SELESAI.')); ?>

                </p>
            </header> -->

            <?php if($transactions->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                    <div class="space-y-1">
                        <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100"><?php echo e(__('Tidak ada tunggakan lebih dari 30 hari')); ?></p>
                        <p class="text-sm"><?php echo e(__('Semua cicilan aktif berada dalam batas waktu pembayaran. Tabel ini akan terisi otomatis jika ada keterlambatan baru.')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="overflow-hidden rounded-lg border border-neutral-200 shadow-sm dark:border-neutral-700">
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left"><?php echo e(__('Nomor Cicilan')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-left"><?php echo e(__('Nasabah')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Tunggakan Terlama')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Akumulasi Denda')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Total Kewajiban Bersih')); ?></th>
                                    <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Aksi')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900">
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $oldestUnpaid = $transaction->installments
                                            ->filter(function ($installment) {
                                                $paidAmount = (float) ($installment->paid_amount ?? 0);

                                                return $installment->paid_at === null || $paidAmount + 0.001 < (float) $installment->amount;
                                            })
                                            ->sortBy('due_date')
                                            ->first();

                                        $totalPenalty = $transaction->installments->sum(function ($installment) use ($today, $lateFeePercentagePerDay) {
                                            if ($installment->paid_at !== null) {
                                                return (float) ($installment->penalty_amount ?? 0);
                                            }

                                            if (! $installment->due_date || ! $installment->due_date->isPast()) {
                                                return 0.0;
                                            }

                                            $penaltyRate = $installment->penalty_rate ?: $lateFeePercentagePerDay;
                                            $daysLate = $installment->due_date->diffInDays($today);

                                            return round((float) $installment->amount * ($penaltyRate / 100) * $daysLate, 2);
                                        });
                                        $sisaUtang = $transaction->installments->sum(function ($installment) {
                                            $paidAmount = (float) ($installment->paid_amount ?? 0);

                                            return max(0, (float) $installment->amount - $paidAmount);
                                        });
                                        $pokokPembiayaanBersih = max(0, (float) $transaction->harga_emas - (float) $transaction->estimasi_uang_muka);
                                        $totalHargaJual = $pokokPembiayaanBersih + (float) $transaction->margin_amount;
                                        $totalKewajibanBersih = $sisaUtang + $totalPenalty;
                                    ?>
                                    <tr>
                                        <td class="px-4 py-3 align-top text-neutral-800 dark:text-neutral-100">
                                            <div class="flex flex-col">
                                                <span class="font-mono text-sm font-semibold"><?php echo e($transaction->nomor_cicilan); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Paket')); ?> <?php echo e($transaction->option_label ?? '—'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-top text-neutral-800 dark:text-neutral-100">
                                            <div class="flex flex-col">
                                                <span class="font-semibold"><?php echo e($transaction->nasabah->nama ?? __('Tidak diketahui')); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($transaction->nasabah->kode_member ?? $transaction->nasabah->telepon ?? '—'); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-top text-center text-neutral-800 dark:text-neutral-100">
                                            <?php if($oldestUnpaid): ?>
                                                <div class="flex flex-col items-center gap-0.5">
                                                    <span class="font-semibold text-red-600 dark:text-red-400"><?php echo e(optional($oldestUnpaid->due_date)->translatedFormat('d M Y')); ?></span>
                                                    <span class="text-[11px] text-neutral-500 dark:text-neutral-400"><?php echo e(__('Cicilan ke-:sequence', ['sequence' => $oldestUnpaid->sequence])); ?></span>
                                                    <span class="text-[11px] text-neutral-500 dark:text-neutral-400"><?php echo e(__('> 30 hari')); ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-sm text-neutral-500 dark:text-neutral-400">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 align-top text-right text-neutral-800 dark:text-neutral-100">
                                            <?php echo e(number_format($totalPenalty, 0, ',', '.')); ?>

                                        </td>
                                        <td class="px-4 py-3 align-top text-right text-neutral-800 dark:text-neutral-100">
                                            <?php echo e(number_format($totalKewajibanBersih, 0, ',', '.')); ?>

                                        </td>
                                        <td class="px-4 py-3 align-top text-center text-neutral-800 dark:text-neutral-100">
                                            <details class="group inline-block w-full">
                                                <summary class="inline-flex cursor-pointer items-center justify-center gap-1 rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-xs font-semibold text-neutral-700 shadow-sm transition hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-amber-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                                    <?php echo e(__('Selesaikan Cicilan')); ?>

                                                </summary>
                                                <div class="mt-3 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-left shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                                                    <form method="POST" action="<?php echo e(route('cicil-emas.penyelesaian-cicilan.store')); ?>" class="grid gap-3 md:grid-cols-2" data-penyelesaian-form data-sisa-utang="<?php echo e($sisaUtang); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="transaction_id" value="<?php echo e($transaction->id); ?>">

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nomor Cicilan')); ?></label>
                                                            <input type="text" readonly value="<?php echo e($transaction->nomor_cicilan); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nama Nasabah')); ?></label>
                                                            <input type="text" readonly value="<?php echo e($transaction->nasabah->nama ?? ''); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tanggal Tunggakan Tertua')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(optional($oldestUnpaid?->due_date)->translatedFormat('d M Y')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label for="penilaian_harga_pasar_emas_<?php echo e($transaction->id); ?>" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Penilaian Harga Pasar Emas')); ?></label>
                                                            <input id="penilaian_harga_pasar_emas_<?php echo e($transaction->id); ?>" name="penilaian_harga_pasar_emas" type="number" step="0.01" required class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 focus:border-amber-400 focus:outline-none focus:ring-amber-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" placeholder="<?php echo e(__('Masukkan nilai pasar')); ?>" data-market-price>
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label for="harga_beli_emas_<?php echo e($transaction->id); ?>" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Harga Beli Nasabah')); ?></label>
                                                            <input id="harga_beli_emas_<?php echo e($transaction->id); ?>" name="harga_beli_emas" type="number" step="0.01" required value="<?php echo e($transaction->harga_emas); ?>" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 focus:border-amber-400 focus:outline-none focus:ring-amber-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label for="nominal_denda_<?php echo e($transaction->id); ?>" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nominal Denda (Penalty Amount)')); ?></label>
                                                            <input id="nominal_denda_<?php echo e($transaction->id); ?>" name="nominal_denda" type="number" step="0.01" required value="<?php echo e($totalPenalty); ?>" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 focus:border-amber-400 focus:outline-none focus:ring-amber-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" data-penalty-input>
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label for="uang_muka_<?php echo e($transaction->id); ?>" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Uang Muka (DP)')); ?></label>
                                                            <input id="uang_muka_<?php echo e($transaction->id); ?>" name="uang_muka" type="number" step="0.01" required value="<?php echo e($transaction->estimasi_uang_muka); ?>" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 focus:border-amber-400 focus:outline-none focus:ring-amber-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Pokok Pembiayaan Bersih')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(number_format($pokokPembiayaanBersih, 0, ',', '.')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Nominal Margin')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(number_format($transaction->margin_amount, 0, ',', '.')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Harga Jual Nasabah (THJ)')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(number_format($totalHargaJual, 0, ',', '.')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Sisa Utang (belum termasuk denda)')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(number_format($sisaUtang, 0, ',', '.')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Kewajiban Bersih')); ?></label>
                                                            <input type="text" readonly value="<?php echo e(number_format($totalKewajibanBersih, 0, ',', '.')); ?>" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" data-total-kewajiban>
                                                            <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Otomatis dihitung: Sisa Utang + Nominal Denda.')); ?></p>
                                                        </div>

                                                        <div class="flex flex-col gap-1">
                                                            <label class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Proses Netting (Pelunasan)')); ?></label>
                                                            <input type="text" readonly value="-" class="w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-700 focus:outline-none dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" data-netting-result>
                                                            <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Bandingkan penilaian harga emas pasar dengan kewajiban bersih untuk mengetahui surplus/defisit.')); ?></p>
                                                        </div>

                                                       

                                                        <div class="flex flex-col gap-1 md:col-span-2">
                                                            <label for="keterangan_<?php echo e($transaction->id); ?>" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Keterangan')); ?></label>
                                                            <textarea id="keterangan_<?php echo e($transaction->id); ?>" name="keterangan" rows="2" class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 focus:border-amber-400 focus:outline-none focus:ring-amber-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100" placeholder="<?php echo e(__('Catat penjelasan surplus/defisit atau kewajiban pengembalian surplus')); ?>"></textarea>
                                                        </div>

                                                        <div class="md:col-span-2 flex items-center justify-between rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 dark:bg-amber-500/10 dark:text-black">
                                                            <div class="flex flex-col">
                                                                <span class="font-semibold"><?php echo e(__('Status Transaksi akan menjadi SELESAI')); ?></span>
                                                                <span><?php echo e(__('Pencatatan kewajiban pengembalian surplus dilakukan otomatis berdasarkan perhitungan surplus/defisit.')); ?></span>
                                                            </div>
                                                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400">
                                                                <?php echo e(__('Simpan Penyelesaian')); ?>

                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </details>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            (() => {
                const formatNumber = (value) =>
                    new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);

                document.querySelectorAll('[data-penyelesaian-form]').forEach((form) => {
                    const outstanding = parseFloat(form.dataset.sisaUtang ?? '0') || 0;
                    const marketInput = form.querySelector('[data-market-price]');
                    const penaltyInput = form.querySelector('[data-penalty-input]');
                    const totalField = form.querySelector('[data-total-kewajiban]');
                    const nettingField = form.querySelector('[data-netting-result]');

                    const updateCalculations = () => {
                        const penalty = parseFloat(penaltyInput?.value ?? '0') || 0;
                        const total = outstanding + penalty;

                        if (totalField) {
                            totalField.value = formatNumber(total);
                        }

                        if (nettingField) {
                            const marketPrice = parseFloat(marketInput?.value ?? '0') || 0;
                            const difference = marketPrice - total;
                            const status = difference >= 0 ? 'Surplus' : 'Defisit';

                            nettingField.value = `${formatNumber(difference)} (${status})`;
                        }
                    };

                    marketInput?.addEventListener('input', updateCalculations);
                    penaltyInput?.addEventListener('input', updateCalculations);

                    updateCalculations();
                });
            })();
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>

<?php /**PATH /var/www/geka/gd/resources/views/cicil-emas/penyelesaian-cicilan.blade.php ENDPATH**/ ?>