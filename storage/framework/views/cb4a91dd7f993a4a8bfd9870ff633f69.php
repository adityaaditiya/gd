<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Laporan Saldo Kas')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Laporan Saldo Kas'))]); ?>
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Laporan Saldo Kas')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Pantau mutasi kas masuk dan keluar termasuk hasil distribusi lelang dalam periode tertentu.')); ?>

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
                <label for="tipe" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Tipe Mutasi')); ?></label>
                <select id="tipe" name="tipe" class="form-select rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value=""><?php echo e(__('Semua Tipe')); ?></option>
                    <?php $__currentLoopData = $tipeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option); ?>" <?php if(($filters['tipe'] ?? null) === $option): echo 'selected'; endif; ?>><?php echo e(__($option)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Cari Referensi / Keterangan')); ?></label>
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
                <button type="reset" onclick="window.location='<?php echo e(route('laporan.saldo-kas')); ?>'" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800">
                    <?php echo e(__('Atur Ulang')); ?>

                </button>
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <?php echo e(__('Terapkan')); ?>

                </button>
            </div>
        </form>

        <div class="grid gap-3 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-3">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Masuk')); ?></span>
                <span class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format((float) $totalMasuk, 0, ',', '.')); ?></span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Keluar')); ?></span>
                <span class="text-xl font-semibold text-rose-600 dark:text-rose-300">Rp <?php echo e(number_format((float) $totalKeluar, 0, ',', '.')); ?></span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Saldo Bersih Periode')); ?></span>
                <span class="text-xl font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format((float) $saldo, 0, ',', '.')); ?></span>
            </div>
        </div>   

        <div class="overflow-x-auto rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tanggal')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Referensi')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Keterangan')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Sumber')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Masuk')); ?></th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-neutral-600 dark:text-neutral-300"><?php echo e(__('Keluar')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    <?php $__empty_1 = true; $__currentLoopData = $mutasiKas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mutasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="text-sm text-neutral-800 dark:text-neutral-100">
                            <td class="px-4 py-3"><?php echo e(optional($mutasi->tanggal)->translatedFormat('d F Y')); ?></td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?php echo e($mutasi->referensi); ?></div>
                                <?php if($mutasi->jadwalLelang): ?>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($mutasi->jadwalLelang->transaksi?->no_sbg); ?> — <?php echo e($mutasi->jadwalLelang->barang?->jenis_barang); ?></div>
                                <?php elseif($mutasi->transaksiGadai): ?>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($mutasi->transaksiGadai->no_sbg); ?> — <?php echo e($mutasi->transaksiGadai->nasabah?->nama); ?></div>
                                <?php elseif($mutasi->cicilEmasTransaction): ?>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($mutasi->cicilEmasTransaction->nomor_cicilan); ?> — <?php echo e($mutasi->cicilEmasTransaction->nasabah?->nama); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?php echo e($mutasi->keterangan ?? '—'); ?></td>
                            <td class="px-4 py-3"><?php echo e($mutasi->sumber ?? '—'); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo e($mutasi->tipe === 'masuk' ? 'Rp ' . number_format((float) $mutasi->jumlah, 0, ',', '.') : '—'); ?></td>
                            <td class="px-4 py-3 text-right"><?php echo e($mutasi->tipe === 'keluar' ? 'Rp ' . number_format((float) $mutasi->jumlah, 0, ',', '.') : '—'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Belum ada data mutasi kas pada periode ini.')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginale43a0c64c2ca4729d3c5b59fae0856df = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale43a0c64c2ca4729d3c5b59fae0856df = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table-pagination','data' => ['paginator' => $mutasiKas,'perPageOptions' => $perPageOptions,'filters' => $filters]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table-pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mutasiKas),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'filters' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters)]); ?>
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
<?php /**PATH /var/www/geka/gd/resources/views/laporan/saldo-kas.blade.php ENDPATH**/ ?>