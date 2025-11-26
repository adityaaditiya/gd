<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Lihat Barang Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Lihat Barang Gadai'))]); ?>
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Data Barang Jaminan')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Pantau seluruh barang elektronik yang digadaikan lengkap dengan detail kontrak, petugas, dan estimasi nilai.')); ?>

            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-black-700 shadow-sm dark:border-black-500/60 dark:bg-black-500/10 dark:text-black">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form
                method="GET"
                action="<?php echo e(route('gadai.lihat-barang-gadai')); ?>"
                class="flex flex-wrap items-center gap-2 text-sm"
            >
                <input type="hidden" name="per_page" value="<?php echo e($perPage); ?>">
                <label for="status" class="text-neutral-600 dark:text-neutral-300"><?php echo e(__('Filter Status')); ?></label>
                <select
                    id="status"
                    name="status"
                    class="rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    onchange="this.form.requestSubmit()"
                >
                    <option value=""><?php echo e(__('Semua Status')); ?></option>
                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option); ?>" <?php if($statusFilter === $option): echo 'selected'; endif; ?>>
                            <?php echo e(__($option)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <label for="search" class="text-neutral-600 dark:text-neutral-300"><?php echo e(__('Cari Data')); ?></label>
                <div class="flex items-center gap-2">
                    <input
                        type="search"
                        id="search"
                        name="search"
                        value="<?php echo e($searchQuery); ?>"
                        placeholder="<?php echo e(__('Cari SBG, nasabah, atau barang')); ?>"
                        class="w-56 rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-800 px-3 py-2 font-semibold text-neutral-900 transition hover:bg-emerald-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                    >
                        <?php echo e(__('Cari')); ?>

                    </button>
                </div>

                <?php if($statusFilter || $searchQuery !== ''): ?>
                    <a
                        href="<?php echo e(route('gadai.lihat-barang-gadai', ['per_page' => $perPage])); ?>"
                        class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-3 py-2 font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-500 dark:text-neutral-300 dark:hover:bg-neutral-700/60"
                    >
                        <?php echo e(__('Reset')); ?>

                    </a>
                <?php endif; ?>
            </form>
            <a
                href="<?php echo e(route('gadai.barang-jaminan.create')); ?>"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
            >
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span><?php echo e(__('Tambah Data')); ?></span>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Aksi')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('No. SBG')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Jenis Barang')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Merek')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tahun Pembuatan')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Harga Pasar Setempat')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Nilai Taksiran')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Uang Pinjaman')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tenor (Hari)')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Bunga Terhitung')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Status')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Petugas')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Tanggal Gadai')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Kondisi Fisik')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Kelengkapan')); ?></th>
                        <th scope="col" class="px-4 py-3"><?php echo e(__('Foto')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    <?php $__empty_1 = true; $__currentLoopData = $barangJaminan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3">
                                <?php
                                    $statusTransaksi = $barang->transaksi?->status_transaksi;
                                ?>
                                <?php if(in_array($statusTransaksi, ['Aktif', 'Lunas'], true)): ?>
                                    <span class="text-xs font-medium text-neutral-400 dark:text-neutral-500"><?php echo e(__('Tidak tersedia')); ?></span>
                                <?php else: ?>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a
                                            href="<?php echo e(route('gadai.barang-jaminan.edit', $barang)); ?>"
                                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 px-3 py-1 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-400 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                                        >
                                            <?php echo e(__('Ubah')); ?>

                                        </a>
                                        <form
                                            method="POST"
                                            action="<?php echo e(route('gadai.barang-jaminan.destroy', $barang)); ?>"
                                            onsubmit="return confirm('<?php echo e(__('Apakah Anda yakin ingin menghapus data ini?')); ?>');"
                                        >
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg border border-red-500 px-3 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500 dark:border-red-400 dark:text-red-300 dark:hover:bg-red-500/10"
                                            >
                                                <?php echo e(__('Hapus')); ?>

                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white"><?php echo e($barang->transaksi?->no_sbg ?? '—'); ?></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white"><?php echo e($barang->transaksi?->nasabah?->nama ?? '—'); ?></span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e($barang->transaksi?->nasabah?->kode_member ?? ''); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3"><?php echo e($barang->jenis_barang); ?></td>
                            <td class="px-4 py-3"><?php echo e($barang->merek); ?></td>
                            <td class="px-4 py-3"><?php echo e($barang->usia_barang_thn ? $barang->usia_barang_thn . ' ' . __('th') : '—'); ?></td>
                            <td class="whitespace-nowrap px-4 py-3">Rp <?php echo e(number_format((float) $barang->hps, 0, ',', '.')); ?></td>
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format((float) $barang->nilai_taksiran, 0, ',', '.')); ?></td>
                            <td class="whitespace-nowrap px-4 py-3">Rp <?php echo e(number_format((float) ($barang->transaksi?->uang_pinjaman ?? 0), 0, ',', '.')); ?></td>
                            <td class="whitespace-nowrap px-4 py-3"><?php echo e($barang->transaksi?->tenor_hari ? $barang->transaksi->tenor_hari . ' ' . __('hari') : '—'); ?></td>
                            <td class="whitespace-nowrap px-4 py-3">Rp <?php echo e(number_format((float) ($barang->transaksi?->total_bunga ?? 0), 0, ',', '.')); ?></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:bg-black-700/60 dark:text-black-100">
                                    <?php echo e(__($barang->transaksi?->status_transaksi ?? 'Belum Aktif')); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e(__('Kasir:')); ?> <?php echo e($barang->transaksi?->kasir?->name ?? '—'); ?></span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300"><?php echo e(__('Penaksir:')); ?> <?php echo e($barang->penaksir?->name ?? '—'); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3"><?php echo e(optional($barang->transaksi?->tanggal_gadai)->format('d M Y') ?? '—'); ?></td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <div class="max-w-xs whitespace-pre-line"><?php echo e($barang->kondisi_fisik ?? '—'); ?></div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-300">
                                <div class="max-w-xs whitespace-pre-line"><?php echo e($barang->kelengkapan ?? '—'); ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <?php
                                    $photos = collect([
                                        $barang->foto_1,
                                        $barang->foto_2,
                                        $barang->foto_3,
                                        $barang->foto_4,
                                        $barang->foto_5,
                                        $barang->foto_6,
                                    ])
                                        ->filter()
                                        ->map(function ($path) {
                                            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) {
                                                return $path;
                                            }

                                            return \Illuminate\Support\Facades\Storage::url($path);
                                        });
                                ?>
                                <?php if($photos->isEmpty()): ?>
                                    <span class="text-xs text-neutral-500 dark:text-black-300"><?php echo e(__('Tidak ada foto')); ?></span>
                                <?php else: ?>
                                    <div class="flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a
                                                href="<?php echo e($path); ?>"
                                                target="_blank"
                                                class="inline-flex size-9 items-center justify-center rounded-lg bg-neutral-100 text-xs font-semibold text-neutral-600 transition hover:bg-emerald-100 hover:text-emerald-700 dark:bg-neutral-700/60 dark:text-black-200 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-300"
                                            >
                                                <?php echo e(__('Foto')); ?> <?php echo e($index + 1); ?>

                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="17" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                <?php echo e(__('Belum ada data barang jaminan yang tersimpan.')); ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($component)) { $__componentOriginal3737ad32e5647ce8eb52cb7dd73df4f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3737ad32e5647ce8eb52cb7dd73df4f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.pagination-controls','data' => ['paginator' => $barangJaminan,'perPage' => $perPage,'perPageOptions' => $perPageOptions,'formAction' => route('gadai.lihat-barang-gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table.pagination-controls'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($barangJaminan),'per-page' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPage),'per-page-options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perPageOptions),'form-action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('gadai.lihat-barang-gadai'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3737ad32e5647ce8eb52cb7dd73df4f6)): ?>
<?php $attributes = $__attributesOriginal3737ad32e5647ce8eb52cb7dd73df4f6; ?>
<?php unset($__attributesOriginal3737ad32e5647ce8eb52cb7dd73df4f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3737ad32e5647ce8eb52cb7dd73df4f6)): ?>
<?php $component = $__componentOriginal3737ad32e5647ce8eb52cb7dd73df4f6; ?>
<?php unset($__componentOriginal3737ad32e5647ce8eb52cb7dd73df4f6); ?>
<?php endif; ?>
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
<?php /**PATH /var/www/gd/resources/views/gadai/lihat-barang-gadai.blade.php ENDPATH**/ ?>