<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('Nota Transaksi Gadai')); ?></title>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        @media print {
            body {
                margin: 0;
                background: #ffffff;
            }
        }
    </style>
</head>
<body class="bg-neutral-100 text-neutral-900">

    <div class="min-h-screen flex items-start justify-center py-8">
        <div class="w-full max-w-3xl overflow-hidden border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="border-b border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Nomor SBG')); ?></p>
                        <p class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e($transaksi->no_sbg); ?></p>
                    </div>
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        <p><?php echo e(__('Tanggal Gadai')); ?>:
                            <span class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e(optional($transaksi->tanggal_gadai)->translatedFormat('d F Y')); ?>

                            </span>
                        </p>
                        <p><?php echo e(__('Jatuh Tempo')); ?>:
                            <span class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e(optional($transaksi->jatuh_tempo_awal)->translatedFormat('d F Y')); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 px-6 py-6 lg:grid-cols-3">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Data Nasabah')); ?></p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Nama')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->nasabah->nama ?? __('Tidak diketahui')); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Kode Member')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->nasabah->kode_member ?? '—'); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Alamat')); ?></dt>
                            <dd class="text-right font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->nasabah->alamat ?? '—'); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Telepon')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->nasabah->telepon ?? '—'); ?>

                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Detail Transaksi')); ?></p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Type')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->type ?? '—'); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Tenor (hari)')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->tenor_hari ?? '—'); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Tarif Bunga Harian')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e(number_format((float) ($transaksi->tarif_bunga_harian ?? 0) * 100, 3, ',', '.')); ?>%
                            </dd>
                        </div>
                        <!-- <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Kasir')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                <?php echo e($transaksi->kasir->name ?? '—'); ?>

                            </dd>
                        </div> -->
                    </dl>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nilai Transaksi')); ?></p>
                    <dl class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300">
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Pinjaman Disetujui')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp <?php echo e(number_format((float) ($transaksi->uang_pinjaman ?? 0), 2, ',', '.')); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Biaya Admin')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp <?php echo e(number_format((float) ($transaksi->biaya_admin ?? 0), 2, ',', '.')); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Premi')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp <?php echo e(number_format((float) ($transaksi->premi ?? 0), 2, ',', '.')); ?>

                            </dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt><?php echo e(__('Uang Cair')); ?></dt>
                            <dd class="font-semibold text-neutral-900 dark:text-white">
                                Rp <?php echo e(number_format((float) ($transaksi->uang_cair ?? 0), 2, ',', '.')); ?>

                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <p class="text-sm font-semibold text-neutral-700 dark:text-neutral-200"><?php echo e(__('Rincian Barang Jaminan')); ?></p>
                <div class="mt-3 overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-left text-xs uppercase tracking-wider text-neutral-600 dark:bg-neutral-900 dark:text-neutral-300">
                            <tr>
                                <th class="px-4 py-3"><?php echo e(__('Jenis & Merek')); ?></th>
                                <th class="px-4 py-3 text-right"><?php echo e(__('Nilai Taksiran')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                            <?php $__empty_1 = true; $__currentLoopData = $transaksi->barangJaminan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-4 py-3 text-neutral-800 dark:text-neutral-100">
                                        <p class="font-semibold"><?php echo e($barang->jenis_barang); ?> — <?php echo e($barang->merek); ?></p>
                                        <!-- <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            <?php echo e(__('Kode Barang')); ?>: <?php echo e($barang->barang_id); ?>

                                        </p> -->
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            <?php echo e(__('Kondisi')); ?>: <?php echo e($barang->kondisi_fisik); ?>

                                        </p>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            <?php echo e(__('Kelengkapan')); ?>: <?php echo e($barang->kelengkapan); ?>

                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-neutral-900 dark:text-white">
                                        Rp <?php echo e(number_format((float) ($barang->nilai_taksiran ?? 0), 2, ',', '.')); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-neutral-500 dark:text-neutral-400">
                                        <?php echo e(__('Tidak ada barang terlampir.')); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if(request()->boolean('auto_print')): ?>
        <script>
            (() => {
                const removeAutoPrintParam = () => {
                    try {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('auto_print');
                        window.history.replaceState({}, '', url.toString());
                    } catch (error) {
                        // noop
                    }
                };

                const triggerPrint = (targetWindow) => {
                    if (!targetWindow) return;

                    const startPrint = () => {
                        try {
                            targetWindow.focus();
                            targetWindow.print();
                        } catch (error) {
                            // noop
                        }
                    };

                    if (targetWindow.document?.readyState === 'complete') {
                        startPrint();
                        return;
                    }

                    targetWindow.addEventListener('load', () => {
                        startPrint();
                    }, { once: true });
                };

                if (window.opener) {
                    triggerPrint(window);
                    removeAutoPrintParam();
                    return;
                }

                const printWindow = window.open(window.location.href, '_blank');
                if (!printWindow) {
                    return;
                }

                triggerPrint(printWindow);
                removeAutoPrintParam();
            })();
        </script>
    <?php endif; ?>

</body>
</html>
<?php /**PATH /var/www/gd/resources/views/gadai/nota-transaksi.blade.php ENDPATH**/ ?>