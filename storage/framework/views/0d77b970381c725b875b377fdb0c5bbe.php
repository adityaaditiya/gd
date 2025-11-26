<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Laporan Lelang')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Laporan Lelang'))]); ?>
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Laporan Lelang')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Rekap seluruh jadwal dan hasil lelang berikut distribusi dana ke perusahaan maupun nasabah.')); ?>

            </p>
        </div>

        <form method="GET" class="grid grid-cols-1 gap-4 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-5">
            <div class="flex flex-col gap-1">
                <label for="tanggal_dari" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Tanggal Dari')); ?></label>
                <input id="tanggal_dari" name="tanggal_dari" type="date" value="<?php echo e($filters['tanggal_dari'] ?? ''); ?>" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label for="tanggal_sampai" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Tanggal Sampai')); ?></label>
                <input id="tanggal_sampai" name="tanggal_sampai" type="date" value="<?php echo e($filters['tanggal_sampai'] ?? ''); ?>" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
            </div>
            <div class="flex flex-col gap-1">
                <label for="status" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Status Jadwal')); ?></label>
                <select id="status" name="status" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value=""><?php echo e(__('Semua Status')); ?></option>
                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option); ?>" <?php if(($filters['status'] ?? null) === $option): echo 'selected'; endif; ?>><?php echo e(__($option)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Cari No. SBG / Barang')); ?></label>
                <input id="search" name="search" type="search" value="<?php echo e($filters['search'] ?? ''); ?>" class="form-input rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white" placeholder="<?php echo e(__('Masukkan kata kunci')); ?>">
            </div>
            <!-- <div class="flex flex-col gap-1">
                <label for="per_page" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Data per halaman')); ?></label>
                <select id="per_page" name="per_page" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <?php $__currentLoopData = [10, 25, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php if(($filters['per_page'] ?? 25) == $size): echo 'selected'; endif; ?>><?php echo e($size); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div> -->
            <div class="md:col-span-5 flex items-center justify-end gap-2">
                <button type="reset" onclick="window.location='<?php echo e(route('laporan.lelang')); ?>'" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">
                    <?php echo e(__('Atur Ulang')); ?>

                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <?php echo e(__('Terapkan')); ?>

                </button>
            </div>
        </form>

        
        <div class="overflow-x-auto rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tanggal Rencana')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Kontrak / Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Barang')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Status')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Harga Laku')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Distribusi Perusahaan')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Distribusi Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Piutang Sisa')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwalLelang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="text-sm text-neutral-800 dark:text-neutral-100">
                            <td class="px-4 py-3"><?php echo e(optional($jadwal->tanggal_rencana)->translatedFormat('d F Y') ?? __('Belum dijadwalkan')); ?></td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?php echo e($jadwal->transaksi?->no_sbg ?? '—'); ?></div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($jadwal->transaksi?->nasabah?->nama ?? __('Nasabah tidak ditemukan')); ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?php echo e($jadwal->barang?->jenis_barang ?? __('Tidak ada data barang')); ?></div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($jadwal->barang?->merek); ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"><?php echo e(__($jadwal->status)); ?></span>
                                <?php if($jadwal->hasil_status === 'laku'): ?>
                                    <div class="mt-1 text-xs text-emerald-600 dark:text-emerald-300"><?php echo e(__('Laku pada :tanggal', ['tanggal' => optional($jadwal->tanggal_selesai)->translatedFormat('d F Y')])); ?></div>
                                <?php elseif($jadwal->hasil_status === 'tidak_laku'): ?>
                                    <div class="mt-1 text-xs text-rose-600 dark:text-rose-300"><?php echo e(__('Belum laku, jadwalkan ulang')); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right"><?php echo e($jadwal->harga_laku !== null ? 'Rp ' . number_format((float) $jadwal->harga_laku, 0, ',', '.') : '—'); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo e($jadwal->distribusi_perusahaan !== null ? 'Rp ' . number_format((float) $jadwal->distribusi_perusahaan, 0, ',', '.') : '—'); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo e($jadwal->distribusi_nasabah !== null ? 'Rp ' . number_format((float) $jadwal->distribusi_nasabah, 0, ',', '.') : '—'); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo e($jadwal->piutang_sisa !== null ? 'Rp ' . number_format((float) $jadwal->piutang_sisa, 0, ',', '.') : '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Belum ada data lelang yang sesuai filter.')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pagination','data' => ['paginator' => $jadwalLelang,'perPageOptions' => $perPageOptions,'filters' => request()->except('page')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($jadwalLelang),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->except('page'))]); ?>
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
        
        <div class="grid gap-3 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-4">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Harga Laku')); ?></span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $summary['total_harga_laku'], 0, ',', '.')); ?></span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Biaya Lelang')); ?></span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $summary['total_biaya_lelang'], 0, ',', '.')); ?></span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Distribusi ke Perusahaan')); ?></span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $summary['total_distribusi_perusahaan'], 0, ',', '.')); ?></span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Distribusi ke Nasabah / Piutang')); ?></span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $summary['total_distribusi_nasabah'], 0, ',', '.')); ?> / Rp <?php echo e(number_format((float) $summary['total_piutang_sisa'], 0, ',', '.')); ?></span>
            </div>
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
<?php /**PATH /var/www/geka/gd/resources/views/laporan/lelang.blade.php ENDPATH**/ ?>