<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Pelunasan Cicilan')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pelunasan Cicilan'))]); ?>
    <?php
        $summary = $summary ?? null;
        $transaction = $transaction ?? null;
        $search = $search ?? '';
        $previewNumber = $previewNumber ?? null;
    ?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Pelunasan Cicilan')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Kelola proses penyelesaian cicilan emas.')); ?>

            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-200">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <section class="flex flex-col gap-4 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <!-- <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-500"><?php echo e(__('Menu Pelunasan Cicilan')); ?></span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Cari Nomor Cicilan Emas')); ?></h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Gunakan pencarian berdasarkan nomor cicilan emas untuk menyiapkan pelunasan normal (akhir kontrak) atau pelunasan dipercepat.')); ?>

                </p>
            </header> -->

            <form method="GET" class="grid gap-3 rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm dark:border-neutral-700 dark:bg-neutral-900 md:grid-cols-6">
                <div class="md:col-span-4">
                    <label class="flex flex-col gap-1">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nomor Cicilan Emas')); ?></span>
                        <input
                            type="search"
                            name="search"
                            value="<?php echo e($search); ?>"
                            placeholder="<?php echo e(__('Masukkan nomor cicilan emas')); ?>"
                            class="rounded-md border border-neutral-300 px-3 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                            autocomplete="off"
                            required
                        >
                    </label>
                </div>
                <div class="flex items-end gap-2 md:col-span-2 md:justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/40"
                    >
                        <?php echo e(__('Cari Cicilan')); ?>

                    </button>
                    <?php if($search !== ''): ?>
                        <a
                            href="<?php echo e(route('cicil-emas.pelunasan-cicilan')); ?>"
                            class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                        >
                            <?php echo e(__('Bersihkan')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if($search !== '' && ! $transaction): ?>
                <div class="flex flex-col gap-2 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-500/40 dark:bg-neutral-900 dark:text-amber-100">
                    <p class="font-semibold"><?php echo e(__('Nomor cicilan tidak ditemukan')); ?></p>
                    <p><?php echo e(__('Periksa kembali nomor cicilan emas atau gunakan menu Angsuran Rutin sebagai referensi pencarian.')); ?></p>
                </div>
            <?php endif; ?>

            <?php if($transaction): ?>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nomor Cicilan')); ?></span>
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/20 dark:text-sky-200"><?php echo e($transaction->status === \App\Models\CicilEmasTransaction::STATUS_SETTLED ? __('Lunas') : __('Aktif')); ?></span>
                        </div>
                        <p class="font-mono text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e($transaction->nomor_cicilan ?? '—'); ?></p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Tenor :bulan bulan • Angsuran ke-:ke', ['bulan' => $transaction->tenor_bulan, 'ke' => $summary['lastSequence'] ?? '—'])); ?></p>
                    </div>
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Data Nasabah')); ?></span>
                        <p class="text-base font-semibold text-neutral-900 dark:text-white"><?php echo e($transaction->nasabah?->nama ?? __('Tidak diketahui')); ?></p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300"><?php echo e($transaction->nasabah?->telepon ?? __('Kontak tidak tersedia')); ?></p>
                        <?php if($transaction->nasabah?->kode_member): ?>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Kode Member: :kode', ['kode' => $transaction->nasabah->kode_member])); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Ringkasan Pembayaran')); ?></span>
                        <!-- <div class="mt-2 flex items-center gap-2">
                            <span class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Sisa: Rp :nominal', ['nominal' => number_format($summary['remainingAmount'], 0, ',', '.')])); ?></span>
                            <?php if($summary['isAccelerated']): ?>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-100"><?php echo e(__('Pelunasan Dipercepat')); ?></span>
                            <?php else: ?>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-100"><?php echo e(__('Pelunasan Normal')); ?></span>
                            <?php endif; ?>
                        </div> -->
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            <?php echo e(__('Terbayar :paid dari :total angsuran', ['paid' => $summary['paidInstallments'], 'total' => $summary['totalInstallments']])); ?>

                        </p>
                        <?php if($summary['nextDueDate']): ?>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Jatuh tempo terdekat: :date', ['date' => $summary['nextDueDate']->translatedFormat('d M Y')])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('cicil-emas.pelunasan-cicilan.store')); ?>" class="grid gap-4 md:grid-cols-2">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="transaction_id" value="<?php echo e($transaction->id); ?>">

                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Detail Pelunasan Normal (Akhir Kontrak)')); ?></h3>
                        <div class="mt-3 grid gap-3">
                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nomor Pelunasan')); ?></span>
                                <input
                                    type="text"
                                    value="<?php echo e(old('nomor_pelunasan', $previewNumber)); ?>"
                                    class="rounded-md border border-neutral-300 bg-neutral-100 px-3 py-2 font-mono text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                    readonly
                                >
                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Format: PE03 + Tanggal (yymmdd) + urutan harian. Contoh: :nomor', ['nomor' => 'PE03'.now()->format('ymd').'001'])); ?></span>
                            </label>
                            <label class="flex flex-col gap-1">
                                <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Biaya Ongkos Kirim (opsional)')); ?></span>
                                <div class="relative">
                                    <!-- <span class="absolute left-3 top-2 text-sm text-neutral-500">Rp</span> -->
                                    <input
                                        type="number"
                                        name="biaya_ongkos_kirim"
                                        min="0"
                                        step="0.01"
                                        value="<?php echo e(old('biaya_ongkos_kirim')); ?>"
                                        class="w-full rounded-md border border-neutral-300 bg-white px-2 py-2 text-sm text-neutral-900 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-100"
                                        placeholder="0.00"
                                    >
                                </div>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Isi jika ada biaya pengiriman emas ke nasabah.')); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                        <h3 class="text-base font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Rangkuman Tagihan')); ?></h3>
                        <dl class="mt-3 space-y-2 text-sm text-neutral-700 dark:text-neutral-200">
                            <div class="flex items-center justify-between">
                                <dt><?php echo e(__('Total Tagihan')); ?></dt>
                                <dd class="font-semibold">Rp <?php echo e(number_format($summary['totalScheduled'], 0, ',', '.')); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt><?php echo e(__('Total Tagihan Terbayar')); ?></dt>
                                <dd class="font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format($summary['totalPaid'], 0, ',', '.')); ?></dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt><?php echo e(__('Sisa Tagihan')); ?></dt>
                                <dd class="font-semibold text-rose-600 dark:text-rose-300">Rp <?php echo e(number_format($summary['remainingAmount'], 0, ',', '.')); ?></dd>
                            </div>
                        </dl>
                        <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400">
                            <?php echo e(__('Pelunasan akan menandai seluruh angsuran sebagai terbayar dan mengubah status transaksi menjadi LUNAS.')); ?>

                        </p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                            >
                                <?php echo e(__('Simpan Pelunasan')); ?>

                            </button>
                            <a
                                href="<?php echo e(route('cicil-emas.angsuran-rutin', ['search' => $transaction->nomor_cicilan])); ?>"
                                class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-300/60 dark:border-neutral-600 dark:text-neutral-300 dark:hover:text-white"
                            >
                                <?php echo e(__('Lihat Riwayat Angsuran')); ?>

                            </a>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
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
<?php /**PATH /var/www/gd/resources/views/cicil-emas/pelunasan-cicilan.blade.php ENDPATH**/ ?>