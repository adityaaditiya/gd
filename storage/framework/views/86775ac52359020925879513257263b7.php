<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Laporan Cicil Emas')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Laporan Cicil Emas'))]); ?>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Laporan Cicil Emas')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Susun laporan audit internal atas kinerja portofolio cicilan emas lengkap dengan filter periode dan status pembayaran.')); ?>

            </p>
        </div>

        <section class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <form method="get" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex flex-col gap-2">
                    <label for="start_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Periode Mulai')); ?></label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo e($filters['start_date'] ?? ''); ?>" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="end_date" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Periode Selesai')); ?></label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo e($filters['end_date'] ?? ''); ?>" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100" />
                </div>
                <div class="flex flex-col gap-2">
                    <label for="status" class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Status Pembayaran')); ?></label>
                    <select id="status" name="status" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value=""><?php echo e(__('Semua Status')); ?></option>
                        <option value="Aktif" <?php if(($filters['status'] ?? null) === 'Aktif'): echo 'selected'; endif; ?>><?php echo e(__('Aktif')); ?></option>
                        <option value="Menunggak" <?php if(($filters['status'] ?? null) === 'Menunggak'): echo 'selected'; endif; ?>><?php echo e(__('Menunggak')); ?></option>
                        <option value="Lunas" <?php if(($filters['status'] ?? null) === 'Lunas'): echo 'selected'; endif; ?>><?php echo e(__('Lunas')); ?></option>
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"><?php echo e(__('Terapkan Filter')); ?></button>
                    <?php if(!empty(array_filter($filters ?? []))): ?>
                        <a href="<?php echo e(route('laporan.cicil-emas')); ?>" class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800"><?php echo e(__('Reset')); ?></a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <section class="flex flex-col gap-4">
            <span></span>
            <header class="flex items-center justify-between">
                <!-- <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Detail Transaksi Cicil Emas')); ?></h2> -->
                <p class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Gunakan tabel berikut untuk keperluan audit dan rekonsiliasi pembayaran.')); ?></p>
            </header>

            <?php if(($insights->count() ?? 0) === 0): ?>
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white"><?php echo e(__('Tidak ada data untuk filter yang dipilih.')); ?></h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Ubah periode atau status untuk melihat portofolio cicilan lainnya.')); ?></p>
                </div>
            <?php else: ?>
                <div class="overflow-hidden rounded-xl border border-neutral-200 shadow-sm dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-800">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tanggal Transaksi')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Nasabah')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Paket Emas')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tenor')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Nilai Emas Awal')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Pokok Pembiayaan')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Margin')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Administrasi')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Total Pembiayaan')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Saldo Pembiayaan Tersisa')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Total Dibayar')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Total Denda')); ?></th>
                                <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Status')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                            <?php $__currentLoopData = $insights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $transaction = $insight['model'];
                                    $nasabah = $transaction->nasabah;
                                    $barang = $insight['barang'];
                                    $statusClass = [
                                        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-200',
                                        'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200',
                                    ][$insight['status_style'] ?? 'info'] ?? 'bg-neutral-200 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200';
                                    $items = collect($insight['items'] ?? []);
                                    $primaryItem = $items->first();
                                ?>
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300"><?php echo e($transaction->created_at?->translatedFormat('d M Y H:i')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-neutral-900 dark:text-neutral-100"><?php echo e($nasabah?->nama ?? __('Nasabah tidak ditemukan')); ?></span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Kode: :kode', ['kode' => $nasabah?->kode_member ?? '—'])); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <div class="flex flex-col">
                                            <?php if($items->count() === 1): ?>
                                                <span><?php echo e($primaryItem['nama_barang'] ?? $transaction->pabrikan); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) ($primaryItem['berat'] ?? $transaction->berat_gram), 3, ',', '.')); ?> gr · <?php echo e($primaryItem['kode'] ?? $transaction->kadar); ?></span>
                                            <?php elseif($items->count() > 1): ?>
                                                <span><?php echo e(__(':count barang', ['count' => $items->count()])); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format($transaction->berat_gram, 3, ',', '.')); ?> gr · <?php echo e($transaction->kadar); ?></span>
                                                <ul class="mt-1 list-disc space-y-1 ps-4 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                    <?php $__currentLoopData = $items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($item['nama_barang'] ?? __('Barang')); ?> • <?php echo e(number_format((float) ($item['berat'] ?? 0), 3, ',', '.')); ?> gr • <?php echo e($item['kode'] ?? '—'); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($items->count() > 3): ?>
                                                        <li>+ <?php echo e($items->count() - 3); ?> <?php echo e(__('barang lainnya')); ?></li>
                                                    <?php endif; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span><?php echo e($transaction->pabrikan); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format($transaction->berat_gram, 3, ',', '.')); ?> gr · <?php echo e($transaction->kadar); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300"><?php echo e($transaction->tenor_bulan); ?> <?php echo e(__('bulan')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($transaction->harga_emas, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['principal_without_margin'] ?? 0, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">
                                        <?php echo e(number_format($insight['margin_percentage'] ?? 0, 2, ',', '.')); ?>% • Rp <?php echo e(number_format($insight['margin_amount'] ?? 0, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['administrasi'] ?? 0, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['total_financed'] ?? 0, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['outstanding_principal'], 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['total_paid'], 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($insight['total_penalty'], 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusClass); ?>"><?php echo e(__($insight['status'])); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if (isset($component)) { $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pagination','data' => ['paginator' => $insights,'perPageOptions' => $perPageOptions,'filters' => $filters]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($insights),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df)): ?>
<?php $attributes = $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df; ?>
<?php unset($__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale43a0c64c2ca4729d3c5b59fae0856df)): ?>
<?php $component = $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df; ?>
<?php unset($__componentOriginale43a0c64c2ca4729d3c5b59fae0856df); ?>
<?php endif; ?>
            <?php endif; ?>
        </section>

        <section class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-fuchsia-500"><?php echo e(__('Ringkasan Audit')); ?></span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Snapshot Portofolio & Risiko')); ?></h2>
                </div>
                <span class="rounded-full bg-fuchsia-100 px-3 py-1 text-xs font-semibold text-fuchsia-700 dark:bg-fuchsia-900/40 dark:text-fuchsia-200">
                    <?php echo e(number_format($metrics['total_transactions'] ?? 0, 0, ',', '.')); ?> <?php echo e(__('transaksi tercakup')); ?>

                </span>
            </header>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Pembiayaan')); ?></dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-neutral-200">Rp <?php echo e(number_format($metrics['total_financed'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Pokok Tercatat')); ?></dt>
                    <dd class="text-xl font-semibold text-amber-900 dark:text-amber-600">Rp <?php echo e(number_format($metrics['total_principal'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Margin Terjadwal')); ?></dt>
                    <dd class="text-xl font-semibold text-purple-600 dark:text-purple-300">Rp <?php echo e(number_format($metrics['total_margin'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Administrasi')); ?></dt>
                    <dd class="text-xl font-semibold text-rose-700 dark:text-rose-400">Rp <?php echo e(number_format($metrics['total_administration'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Saldo Pembiayaan Tersisa')); ?></dt>
                    <dd class="text-xl font-semibold text-amber-600 dark:text-amber-300">Rp <?php echo e(number_format($metrics['total_outstanding'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Pembayaran')); ?></dt>
                    <dd class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format($metrics['total_paid'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Denda Tercatat')); ?></dt>
                    <dd class="text-xl font-semibold text-rose-600 dark:text-rose-300">Rp <?php echo e(number_format($metrics['total_penalty'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
            </dl>

            <div class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Distribusi Status Pembayaran')); ?></h3>
                    <div class="mt-4 grid gap-3">
                        <?php
                            $statusColors = [
                                'Aktif' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                                'Menunggak' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
                                'Lunas' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                            ];
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $statusBuckets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between rounded-lg border border-dashed border-neutral-200 px-4 py-3 dark:border-neutral-700">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100"><?php echo e(__($status)); ?></span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Jumlah: :count transaksi', ['count' => number_format($count, 0, ',', '.')])); ?></span>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusColors[$status] ?? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200'); ?>"><?php echo e(__('Status')); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Belum ada data status cicilan pada filter ini.')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Indikator Risiko')); ?></h3>
                    <dl class="mt-4 space-y-3 text-sm text-neutral-600 dark:text-neutral-300">
                        <div class="flex items-center justify-between">
                            <dt><?php echo e(__('Rasio Menunggak')); ?></dt>
                            <dd class="font-semibold text-rose-600 dark:text-rose-300"><?php echo e(number_format($metrics['late_ratio'] ?? 0, 2, ',', '.')); ?>%</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt><?php echo e(__('Rata-rata Penyelesaian')); ?></dt>
                            <dd class="font-semibold text-blue-600 dark:text-blue-300"><?php echo e(number_format($metrics['average_completion'] ?? 0, 2, ',', '.')); ?>%</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt><?php echo e(__('Total Transaksi diaudit')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-neutral-100"><?php echo e(number_format($metrics['total_transactions'] ?? 0, 0, ',', '.')); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        
    </div>
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
<?php /**PATH /var/www/geka/gd/resources/views/laporan/cicil-emas.blade.php ENDPATH**/ ?>