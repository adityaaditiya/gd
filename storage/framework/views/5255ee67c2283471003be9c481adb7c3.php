<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Laporan Perpanjangan Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Laporan Perpanjangan Gadai'))]); ?>
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Laporan Perpanjangan Gadai')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Pantau seluruh perpanjangan kontrak gadai termasuk tenor baru dan biaya yang diterima kas.')); ?>

            </p>
        </div>

        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <form method="GET" action="<?php echo e(route('laporan.perpanjangan-gadai')); ?>" class="grid w-full gap-3 sm:grid-cols-2 lg:max-w-3xl lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <label for="search-perpanjangan" class="sr-only"><?php echo e(__('Cari No. SBG atau nama nasabah')); ?></label>
                    <div class="relative">
                        <!-- <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-neutral-400 dark:text-neutral-500">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.5 5.5a7.5 7.5 0 0011.15 11.15z" />
                            </svg>
                        </span> -->
                        <input
                            id="search-perpanjangan"
                            name="search"
                            type="search"
                            value="<?php echo e($search ?? ''); ?>"
                            placeholder="<?php echo e(__('    Cari No. SBG atau nama nasabah…')); ?>"
                            class="w-full rounded-lg border border-neutral-200 bg-white py-2 pl-10 pr-3 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                        />
                    </div>
                </div>
                <div>
                    <label for="tanggal-dari" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tanggal dari')); ?></label>
                    <input
                        id="tanggal-dari"
                        name="tanggal_dari"
                        type="date"
                        value="<?php echo e($tanggalDari ?? ''); ?>"
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    />
                </div>
                <div>
                    <label for="tanggal-sampai" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tanggal sampai')); ?></label>
                    <input
                        id="tanggal-sampai"
                        name="tanggal_sampai"
                        type="date"
                        value="<?php echo e($tanggalSampai ?? ''); ?>"
                        class="w-full rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    />
                </div>
                
                <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-4">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9.75L8.25 12l3 2.25M12.75 9.75l3 2.25-3 2.25" />
                        </svg>
                        <span><?php echo e(__('Terapkan Filter')); ?></span>
                    </button>
                    <?php if(!empty($search) || !empty($tanggalDari) || !empty($tanggalSampai)): ?>
                        <a
                            href="<?php echo e(route('laporan.perpanjangan-gadai')); ?>"
                            class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            <span><?php echo e(__('Reset')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tanggal Perpanjangan')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('No. SBG & Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tenor')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Biaya Perpanjangan')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Jadwal Baru')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Petugas')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $isCancelled = $item->dibatalkan_pada !== null;
                        ?>
                        <tr class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70',
                            'bg-red-50/80 dark:bg-red-500/10' => $isCancelled,
                        ]); ?>">
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-semibold text-neutral-900 dark:text-white"><?php echo e(optional($item->tanggal_perpanjangan)->format('d M Y H:i') ?? '—'); ?></div>
                                <div><?php echo e(__('Dicatat pada: :date', ['date' => $item->created_at?->format('d M Y H:i') ?? '—'])); ?></div>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px]">
                                    <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center rounded-full px-2 py-0.5 font-semibold uppercase tracking-wide',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200' => !$isCancelled,
                                        'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-200' => $isCancelled,
                                    ]); ?>">
                                        <?php echo e($isCancelled ? __('Dibatalkan') : __('Aktif')); ?>

                                    </span>
                                    <?php if($isCancelled): ?>
                                        <span class="text-red-700 dark:text-red-200">
                                            <?php echo e(__('Dibatalkan :date oleh :user', [
                                                'date' => optional($item->dibatalkan_pada)->format('d M Y H:i') ?? '—',
                                                'user' => $item->pembatal?->name ?? __('Tidak diketahui'),
                                            ])); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-neutral-900 dark:text-white"><?php echo e($item->transaksi?->no_sbg ?? '—'); ?></div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e($item->transaksi?->nasabah?->nama ?? '—'); ?></div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e(__('Pokok: Rp :amount', ['amount' => number_format((float) $item->pokok_pinjaman, 0, ',', '.')])); ?></div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div><?php echo e(__('Sebelumnya: :days hari', ['days' => $item->tenor_sebelumnya])); ?></div>
                                <div><?php echo e(__('Baru: :days hari', ['days' => $item->tenor_baru])); ?></div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $item->total_bayar, 0, ',', '.')); ?></div>
                                <dl class="mt-2 space-y-1">
                                    <div class="flex justify-between gap-3">
                                        <dt><?php echo e(__('Bunga')); ?></dt>
                                        <dd>Rp <?php echo e(number_format((float) $item->bunga_dibayar, 0, ',', '.')); ?></dd>
                                    </div>
                                    <?php if((float) $item->biaya_admin > 0): ?>
                                        <div class="flex justify-between gap-3">
                                            <dt><?php echo e(__('Admin')); ?></dt>
                                            <dd>Rp <?php echo e(number_format((float) $item->biaya_admin, 0, ',', '.')); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                    <?php if((float) $item->biaya_titip > 0): ?>
                                        <div class="flex justify-between gap-3">
                                            <dt><?php echo e(__('Titip')); ?></dt>
                                            <dd>Rp <?php echo e(number_format((float) $item->biaya_titip, 0, ',', '.')); ?></dd>
                                        </div>
                                    <?php endif; ?>
                                </dl>
                                <?php if(!empty($item->catatan)): ?>
                                    <div class="mt-2 rounded-lg bg-neutral-50 px-3 py-2 text-[11px] text-neutral-600 dark:bg-neutral-900 dark:text-neutral-300">
                                        <?php echo e($item->catatan); ?>

                                    </div>
                                <?php endif; ?>
                                <?php if($isCancelled): ?>
                                    <div class="mt-3 rounded-lg bg-red-100 px-3 py-2 text-[11px] text-red-700 dark:bg-red-500/20 dark:text-red-100">
                                        <p>
                                            <?php echo e(__('Mutasi kas dibatalkan pada :date oleh :user.', [
                                                'date' => optional($item->dibatalkan_pada)->format('d M Y H:i') ?? '—',
                                                'user' => $item->pembatal?->name ?? __('Tidak diketahui'),
                                            ])); ?>

                                        </p>
                                        <?php if(!empty($item->alasan_pembatalan)): ?>
                                            <p class="mt-1 italic">“<?php echo e($item->alasan_pembatalan); ?>”</p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div><?php echo e(__('Mulai: :date', ['date' => optional($item->tanggal_mulai_baru)->format('d M Y') ?? '—'])); ?></div>
                                <div><?php echo e(__('Jatuh tempo: :date', ['date' => optional($item->tanggal_jatuh_tempo_baru)->format('d M Y') ?? '—'])); ?></div>
                            </td>
                            <td class="px-4 py-3 text-xs text-neutral-600 dark:text-neutral-300">
                                <div class="font-medium text-neutral-900 dark:text-white"><?php echo e($item->petugas?->name ?? '—'); ?></div>
                                <div><?php echo e(__('Cabang/Kasir awal: :name', ['name' => $item->transaksi?->kasir?->name ?? '—'])); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                <?php echo e(__('Belum ada perpanjangan yang tercatat.')); ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pagination','data' => ['paginator' => $riwayat,'perPageOptions' => $perPageOptions,'filters' => request()->except('page')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($riwayat),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->except('page'))]); ?>
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
<?php /**PATH /var/www/geka/gd/resources/views/laporan/perpanjangan-gadai.blade.php ENDPATH**/ ?>