<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Laporan Pembatalan Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Laporan Pembatalan Gadai'))]); ?>
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Laporan Pembatalan Gadai')); ?></h1>
            <!-- <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Daftar kontrak gadai yang dibatalkan lengkap dengan alasan dan petugas pembatal.')); ?>

            </p> -->
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Daftar lengkap kontrak gadai yang dibatalkan.')); ?>

            </p>
        </div>

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <form method="GET" action="<?php echo e(route('laporan.batal-gadai')); ?>" class="w-full max-w-md">
                <label for="search-batal" class="sr-only"><?php echo e(__('Cari transaksi')); ?></label>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <!-- <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-neutral-400 dark:text-neutral-500">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z" />
                            </svg>
                        </span> -->
                        <input
                            id="search-batal"
                            name="search"
                            type="search"
                            value="<?php echo e($search ?? ''); ?>"
                            placeholder="<?php echo e(__('Cari No. SBG, Nama, atau Kontak Nasabah…')); ?>"
                            class="w-full rounded-lg border border-neutral-200 bg-white py-2 pl-9 pr-3 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                        />
                    </div>
                    <?php if(!empty($search)): ?>
                        <a
                            href="<?php echo e(route('laporan.batal-gadai')); ?>"
                            class="inline-flex items-center rounded-lg border border-neutral-200 px-3 py-2 text-xs font-semibold text-neutral-600 transition hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                        >
                            <?php echo e(__('Reset')); ?>

                        </a>
                    <?php endif; ?>
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg border border-emerald-600 bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:bg-emerald-500 dark:hover:border-emerald-300 dark:hover:bg-emerald-400"
                    >
                        <?php echo e(__('Cari')); ?>

                    </button>
                </div>
            </form>

            <!-- <div class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Total :count transaksi batal.', ['count' => number_format($transaksiBatal->total(), 0, ',', '.')])); ?>

            </div> -->
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('No. SBG')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Barang Jaminan')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Pinjaman')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tanggal Batal')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Alasan Batal')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Petugas')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiBatal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->no_sbg); ?>

                                <div class="text-xs font-normal text-neutral-500 dark:text-neutral-300">
                                    <?php echo e(__('Tanggal Gadai: :date', ['date' => optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—'])); ?>

                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white"><?php echo e($transaksi->nasabah?->nama ?? '—'); ?></span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e($transaksi->nasabah?->kode_member ?? ''); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php if($transaksi->barangJaminan->isEmpty()): ?>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e(__('Tidak ada data barang.')); ?></span>
                                <?php else: ?>
                                    <ul class="space-y-1">
                                        <?php $__currentLoopData = $transaksi->barangJaminan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="rounded-lg bg-neutral-50 px-3 py-2 text-xs text-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                                <div class="font-semibold text-neutral-900 dark:text-white"><?php echo e($barang->jenis_barang); ?> — <?php echo e($barang->merek); ?></div>
                                                <div>Rp <?php echo e(number_format((float) $barang->nilai_taksiran, 0, ',', '.')); ?></div>
                                                <div class="text-[11px] text-neutral-500 dark:text-neutral-300"><?php echo e(__('Kelengkapan:')); ?> <?php echo e($barang->kelengkapan ?? '—'); ?></div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format((float) $transaksi->uang_pinjaman, 0, ',', '.')); ?></div>
                                <?php if((float) $transaksi->biaya_admin > 0): ?>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e(__('Biaya admin: Rp :amount', ['amount' => number_format((float) $transaksi->biaya_admin, 0, ',', '.')])); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3"><?php echo e(optional($transaksi->tanggal_batal)->format('d M Y H:i') ?? '—'); ?></td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <?php echo e($transaksi->alasan_batal ?? '—'); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <?php echo e($transaksi->pembatal?->name ?? '—'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                <?php echo e(__('Belum ada transaksi gadai yang dibatalkan.')); ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pagination','data' => ['paginator' => $transaksiBatal,'perPageOptions' => $perPageOptions,'filters' => request()->except('page')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($transaksiBatal),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->except('page'))]); ?>
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
        </div>
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
<?php /**PATH /var/www/gd/resources/views/laporan/batal-gadai.blade.php ENDPATH**/ ?>