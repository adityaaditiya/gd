<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Penyelesaian Hasil Lelang')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Penyelesaian Hasil Lelang'))]); ?>
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Penyelesaian Hasil Lelang')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Kelola sisa hasil lelang yang menjadi hak nasabah atau sisa piutang yang harus ditagih.')); ?>

            </p>
            <!-- <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100">
                <ul class="list-inside list-disc space-y-1">
                    <li><?php echo e(__('Kas keluar untuk SURPLUS hanya dicatat ketika status pembayaran nasabah diubah menjadi “Sudah Diambil” atau “Dialihkan ke Dana Sosial”.')); ?></li>
                    <li><?php echo e(__('Kas masuk untuk DEFISIT dicatat ketika status pembayaran nasabah diubah menjadi “Sudah Lunas”.')); ?></li>
                </ul>
            </div> -->
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-md border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="rounded-md border border-rose-200 bg-rose-50 p-4 text-rose-800 dark:border-rose-900 dark:bg-rose-950 dark:text-rose-200">
                <ul class="list-inside list-disc space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="GET" class="grid grid-cols-1 gap-4 rounded-lg border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-4">
            <div class="flex flex-col gap-1 md:col-span-2">
                <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Cari No. SBG / Nasabah / Barang')); ?></label>
                <input
                    id="search"
                    name="search"
                    type="search"
                    value="<?php echo e($search); ?>"
                    class="form-input w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                    placeholder="<?php echo e(__('Masukkan kata kunci...')); ?>"
                >
            </div>
            <div class="flex flex-col gap-1">
                <label for="status_pembayaran" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Status Pembayaran Nasabah')); ?></label>
                <select id="status_pembayaran" name="status_pembayaran" class="form-select w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <option value=""><?php echo e(__('Semua Status')); ?></option>
                    <?php $__currentLoopData = array_merge($surplusStatuses, $defisitStatuses); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option); ?>" <?php if($statusPembayaran === $option): echo 'selected'; endif; ?>><?php echo e(__($option)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label for="per_page" class="text-sm font-medium text-neutral-700 dark:text-neutral-300"><?php echo e(__('Data per halaman')); ?></label>
                <select id="per_page" name="per_page" class="form-select w-full rounded-md border-neutral-300 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                    <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($size); ?>" <?php if($perPage === $size): echo 'selected'; endif; ?>><?php echo e($size); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="md:col-span-4 flex items-center justify-end gap-2">
                <button type="reset" class="rounded-md border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800" onclick="window.location='<?php echo e(route('gadai.penyelesaian-hasil-lelang')); ?>'">
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
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nomor Lelang')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kontrak & Nasabah')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Barang')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Status Hasil')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tanggal Ambil')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tanggal Pembayaran')); ?></th>
                        <th scope="col" class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Status Pembayaran Nasabah')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    <?php $__empty_1 = true; $__currentLoopData = $jadwalLelang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $transaksi = $jadwal->transaksi;
                            $nasabah = $transaksi?->nasabah;
                            $barang = $jadwal->barang;
                            $resultType = (float) $jadwal->distribusi_nasabah > 0 ? 'surplus' : 'defisit';
                            $statusOptions = $resultType === 'surplus' ? $surplusStatuses : $defisitStatuses;
                            $hasilBersih = $resultType === 'surplus' ? $jadwal->distribusi_nasabah : $jadwal->piutang_sisa;
                            $ambilStatuses = ['Sudah Diambil', 'Dialihkan ke Dana Sosial'];
                            $shouldShowTanggalAmbil = $resultType === 'surplus' && in_array($jadwal->status_pembayaran_nasabah, $ambilStatuses, true);
                            $shouldShowTanggalPembayaran = $resultType === 'defisit' && $jadwal->status_pembayaran_nasabah === 'Sudah Lunas';
                        ?>
                        <tr class="align-top">
                            <td class="px-4 py-3 text-sm font-semibold text-neutral-800 dark:text-neutral-100"><?php echo e($jadwal->nomor_lelang ?? '—'); ?></td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <div class="font-semibold"><?php echo e($transaksi?->no_sbg ?? __('Tanpa Nomor SBG')); ?></div>
                                <div class="text-neutral-600 dark:text-neutral-400"><?php echo e($nasabah?->nama ?? __('Nasabah tidak ditemukan')); ?></div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Tanggal Lelang Selesai')); ?>:
                                    <span class="font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(optional($jadwal->tanggal_selesai)->translatedFormat('d F Y') ?? __('Belum tercatat')); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <div class="font-medium"><?php echo e($barang?->jenis_barang ?? __('Barang tidak ditemukan')); ?></div>
                                <div class="text-neutral-600 dark:text-neutral-400"><?php echo e($barang?->merek); ?></div>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Petugas')); ?>: <?php echo e($jadwal->petugas ?? __('Belum ditetapkan')); ?></div>
                            </td>
                            <td class="mt-2 text-xs text-neutral-800 dark:text-neutral-100">
                                <div class="inline-flex items-center gap-2 rounded-full mt-4 px-1 py-1 text-center text-xs font-semibold <?php echo e($resultType === 'surplus' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-200'); ?>">
                                    <?php echo e($resultType === 'surplus' ? __('(Kelebihan Uang) SURPLUS') : __('(Kekurangan Bayar) DEFISIT')); ?>

                                </div>
                                <div class="mt-2 items-center text-sm text-center font-semibold">
                                    Rp. <?php echo e(number_format((float) $hasilBersih, 0, ',', '.')); ?>

                                </div>
                                <!-- <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e($resultType === 'surplus'
                                        ? __('Saldo kas belum keluar sampai status pembayaran berubah menjadi Sudah Diambil atau Dialihkan ke Dana Sosial.')
                                        : __('Gunakan status pembayaran untuk memantau piutang yang masih harus dilunasi nasabah.')); ?>

                                </div> -->
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <?php if($shouldShowTanggalAmbil): ?>
                                    <?php echo e(optional($jadwal->tanggal_ambil)->translatedFormat('d F Y') ?? '—'); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <?php if($shouldShowTanggalPembayaran): ?>
                                    <?php echo e(optional($jadwal->tanggal_pembayaran)->translatedFormat('d F Y') ?? '—'); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-800 dark:text-neutral-100">
                                <form method="POST" action="<?php echo e(route('gadai.penyelesaian-hasil-lelang.update', $jadwal)); ?>" class="space-y-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <label class="flex flex-col gap-1 text-xs font-medium">
                                        <!-- <span><?php echo e(__('Status Pembayaran Nasabah')); ?></span> -->
                                        <select name="status_pembayaran_nasabah" class="form-select w-full rounded-md border-neutral-300 text-sm dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($option); ?>" <?php if($jadwal->status_pembayaran_nasabah === $option): echo 'selected'; endif; ?>><?php echo e(__($option)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </label>
                                    <button type="submit" class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <?php echo e(__('Simpan Status')); ?>

                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tidak ada data surplus atau defisit hasil lelang yang perlu ditindaklanjuti.')); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div>
            <?php echo e($jadwalLelang->appends(request()->query())->links()); ?>

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
<?php /**PATH /var/www/gd/resources/views/gadai/penyelesaian-hasil-lelang.blade.php ENDPATH**/ ?>