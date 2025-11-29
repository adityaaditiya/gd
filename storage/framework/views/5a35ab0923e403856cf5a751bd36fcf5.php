<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Pelunasan Transaksi Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pelunasan Transaksi Gadai'))]); ?>
    <?php
        $listQuery = $query;
        $listRoute = route('gadai.lihat-gadai', $listQuery);
        $nasabah = $transaksi->nasabah?->nama ?? '—';
        $kasir = $transaksi->kasir?->name ?? '—';
        $barangJaminan = $transaksi->barangJaminan ?? collect();
        $perhitungan = $perhitunganPelunasan;
        $tarifBungaPersen = $perhitungan['tarif_bunga'] * 100;
    ?>

    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-2">
                <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">
                    <?php echo e(__('Pelunasan Transaksi Gadai')); ?>

                </h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Konfirmasi penerimaan pelunasan untuk kontrak :number atas nama :customer.', [
                        'number' => $transaksi->no_sbg,
                        'customer' => $nasabah,
                    ])); ?>

                </p>
            </div>
            <a
    href="<?php echo e($listRoute); ?>"
    class="ml-auto inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-200 dark:hover:bg-neutral-800/70"
>
    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
    </svg>
    <span><?php echo e(__('Kembali ke daftar transaksi')); ?></span>
</a>

        </div>
        
<br>

<aside class="space-y-6">
                <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Informasi Transaksi')); ?></h2>
                    <dl class="mt-4 space-y-3 text-sm text-neutral-700 dark:text-neutral-200">
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('No. SBG')); ?></dt>
                            <dd class="text-right font-semibold text-neutral-900 dark:text-white"><?php echo e($transaksi->no_sbg); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Nasabah')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e($nasabah); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Kasir Pembuat')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e($kasir); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tanggal Gadai')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e(optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—'); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Jatuh Tempo')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e(optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—'); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Uang Pinjaman')); ?></dt>
                            <dd class="text-right font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format((float) $transaksi->uang_pinjaman, 0, ',', '.')); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tarif Bunga Harian')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e(number_format($tarifBungaPersen, 2, ',', '.')); ?>%</dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Hari Pemakaian Aktual')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white"><?php echo e($perhitungan['actual_days']); ?> <?php echo e(__('hari')); ?></dd>
                        </div>
                        <div class="flex items-start justify-between gap-4">
                            <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Sewa Modal Terutang')); ?></dt>
                            <dd class="text-right text-neutral-900 dark:text-white">Rp <?php echo e(number_format($perhitungan['sewa_modal'], 0, ',', '.')); ?></dd>
                        </div>
                        <?php if($perhitungan['biaya_lain'] > 0): ?>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-neutral-600 dark:text-neutral-300"><?php echo e(__('Biaya Lain-Lain Pelunasan')); ?></dt>
                                <dd class="text-right text-neutral-900 dark:text-white">Rp <?php echo e(number_format($perhitungan['biaya_lain'], 0, ',', '.')); ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>

                    <?php if($barangJaminan->isNotEmpty()): ?>
                        <div class="mt-4 space-y-2 text-sm text-neutral-600 dark:text-neutral-300">
                            <p class="font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Barang Jaminan')); ?></p>
                            <ul class="space-y-2">
                                <?php $__currentLoopData = $barangJaminan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="rounded-lg border border-neutral-200 px-3 py-2 text-xs text-neutral-700 dark:border-neutral-700 dark:text-neutral-200">
                                        <div class="font-semibold text-neutral-900 dark:text-white"><?php echo e($barang->jenis_barang); ?> — <?php echo e($barang->merek); ?></div>
                                        <div><?php echo e(__('Nilai taksiran: :amount', ['amount' => 'Rp ' . number_format((float) $barang->nilai_taksiran, 0, ',', '.')])); ?></div>
                                        <div class="text-[11px] text-neutral-500 dark:text-neutral-400"><?php echo e(__('Kelengkapan:')); ?> <?php echo e($barang->kelengkapan ?? '—'); ?></div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

        <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <form
                    method="POST"
                    action="<?php echo e(route('gadai.transaksi-gadai.settle', ['transaksi' => $transaksi->transaksi_id])); ?>"
                    class="space-y-6 p-6"
                >
                    <?php echo csrf_field(); ?>
                    <?php $__currentLoopData = ['search', 'tanggal_dari', 'tanggal_sampai', 'per_page', 'page']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $param): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="<?php echo e($param); ?>" value="<?php echo e($listQuery[$param] ?? ''); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium"><?php echo e(__('Tanggal Pelunasan')); ?></span>
                            <input
                                type="date"
                                name="tanggal_pelunasan"
                                value="<?php echo e(old('tanggal_pelunasan', $defaults['tanggal_pelunasan'])); ?>"
                                required
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            >
                            <?php $__errorArgs = ['tanggal_pelunasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium"><?php echo e(__('Metode Pembayaran')); ?></span>
                            <input
                                type="text"
                                name="metode_pembayaran"
                                value="<?php echo e(old('metode_pembayaran', $defaults['metode_pembayaran'])); ?>"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="<?php echo e(__('Contoh: Tunai, Transfer Bank…')); ?>"
                            >
                            <?php $__errorArgs = ['metode_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium"><?php echo e(__('Pokok Dibayar')); ?></span>
                            <input
                                type="text"
                                name="pokok_dibayar"
                                value="<?php echo e(old('pokok_dibayar', $defaults['pokok_dibayar'])); ?>"
                                required
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="<?php echo e(__('Masukkan nominal pokok…')); ?>"
                            >
                            <?php $__errorArgs = ['pokok_dibayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </label>

                        <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <span class="font-medium"><?php echo e(__('Bunga Dibayar')); ?></span>
                            <input
                                type="text"
                                name="bunga_dibayar"
                                value="<?php echo e(old('bunga_dibayar', $defaults['bunga_dibayar'])); ?>"
                                inputmode="decimal"
                                data-currency-input
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                placeholder="<?php echo e(__('Masukkan nominal bunga…')); ?>"
                            >
                            <?php $__errorArgs = ['bunga_dibayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </label>
                    </div>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium"><?php echo e(__('Biaya Lain-Lain (Opsional)')); ?></span>
                        <input
                            type="text"
                            name="biaya_lain_dibayar"
                            value="<?php echo e(old('biaya_lain_dibayar', $defaults['biaya_lain_dibayar'])); ?>"
                            inputmode="decimal"
                            data-currency-input
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="<?php echo e(__('Nominal biaya lain-lain yang harus dilunasi (jika ada)…')); ?>"
                        >
                        <?php $__errorArgs = ['biaya_lain_dibayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium"><?php echo e(__('Total Pelunasan')); ?></span>
                        <input
                            type="text"
                            name="total_pelunasan"
                            value="<?php echo e(old('total_pelunasan', $defaults['total_pelunasan'])); ?>"
                            required
                            inputmode="decimal"
                            data-currency-input
                            class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="<?php echo e(__('Total dana yang diterima kasir…')); ?>"
                        >
                        <?php $__errorArgs = ['total_pelunasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>

                    <label class="flex flex-col gap-2 text-sm text-neutral-700 dark:text-neutral-200">
                        <span class="font-medium"><?php echo e(__('Catatan Pelunasan')); ?></span>
                        <textarea
                            name="catatan_pelunasan"
                            rows="3"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-800 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                            placeholder="<?php echo e(__('Catat detail tambahan atau kondisi barang saat ditebus…')); ?>"
                        ><?php echo e(old('catatan_pelunasan')); ?></textarea>
                        <?php $__errorArgs = ['catatan_pelunasan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </label>

                    <div class="flex flex-col gap-3 rounded-lg border border-emerald-200 bg-emerald-50/60 px-4 py-3 text-xs text-emerald-800 shadow-sm dark:border-emerald-500/50 dark:bg-emerald-500/10 dark:text-emerald-200">
                        <p class="font-semibold"><?php echo e(__('Ringkasan pelunasan')); ?></p>
                        <ul class="list-disc space-y-1 pl-4">
                            <li><?php echo e(__('Total pelunasan minimal meliputi pokok pinjaman, sewa modal terutang, dan biaya lain-lain yang Anda cantumkan.')); ?></li>
                            <li><?php echo e(__('Setelah disimpan, status transaksi berubah menjadi Lunas dan tercatat pada laporan pelunasan.')); ?></li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <a
                            href="<?php echo e($listRoute); ?>"
                            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800/70"
                        >
                            <?php echo e(__('Batal')); ?>

                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span><?php echo e(__('Konfirmasi Pelunasan')); ?></span>
                        </button>
                    </div>
                </form>
            </div>

            

                <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-6 text-sm text-emerald-800 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-200">
                    <h2 class="text-lg font-semibold"><?php echo e(__('Perhitungan Pelunasan')); ?></h2>
                    <dl class="mt-4 space-y-3">
                        <div class="flex items-center justify-between gap-4">
                            <dt><?php echo e(__('Pokok Pinjaman')); ?></dt>
                            <dd class="font-semibold">Rp <?php echo e(number_format($perhitungan['pokok'], 0, ',', '.')); ?></dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt><?php echo e(__('Sewa Modal Terutang')); ?></dt>
                            <dd class="font-semibold">Rp <?php echo e(number_format($perhitungan['sewa_modal'], 0, ',', '.')); ?></dd>
                        </div>
                        <?php if($perhitungan['biaya_lain'] > 0): ?>
                            <div class="flex items-center justify-between gap-4">
                                <dt><?php echo e(__('Biaya Lain-Lain Pelunasan')); ?></dt>
                                <dd class="font-semibold">Rp <?php echo e(number_format($perhitungan['biaya_lain'], 0, ',', '.')); ?></dd>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between gap-4 border-t border-emerald-200 pt-3 dark:border-emerald-500/40">
                            <dt><?php echo e(__('Total Tagihan Pelunasan')); ?></dt>
                            <dd class="text-base font-bold">Rp <?php echo e(number_format($perhitungan['total_tagihan'], 0, ',', '.')); ?></dd>
                        </div>
                    </dl>
                    <p class="mt-4 text-xs text-emerald-700 dark:text-emerald-200/80">
                        <?php echo e(__('Nilai di atas dihitung berdasarkan tarif bunga harian 0,15% dan jumlah hari aktual sejak tanggal gadai.')); ?>

                    </p>
                </div>
            </aside>
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
<?php /**PATH /var/www/gd/resources/views/gadai/pelunasan.blade.php ENDPATH**/ ?>