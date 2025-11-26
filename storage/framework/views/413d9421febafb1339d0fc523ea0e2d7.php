<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Daftar Cicilan')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Daftar Cicilan'))]); ?>
    <?php
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\CicilEmasTransaction> $transactions */
        $highlightId = session('transaction_summary.transaksi_id');
        $transactionErrorId = (string) session('transaction_error_id');
    ?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Daftar Cicilan')); ?></h1>
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Kelola jadwal cicilan emas aktif, lengkap dengan rincian jatuh tempo dan ketentuan denda.')); ?>

            </p>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <section class="flex flex-col gap-3 rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <!-- <header class="flex flex-col gap-1">
                <span class="text-xs font-semibold uppercase tracking-wide text-emerald-500"><?php echo e(__('Menu Daftar Cicilan')); ?></span>
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Penjadwalan Angsuran Otomatis')); ?></h2>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Setiap transaksi cicilan yang disetujui menghasilkan jadwal angsuran terstruktur sebagai panduan penagihan.')); ?>

                </p>
            </header> -->

            <?php if($transactions->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center gap-3 rounded-lg border border-dashed border-neutral-300 p-6 text-center text-neutral-600 dark:border-neutral-600 dark:text-neutral-300">
                    <div class="space-y-1">
                        <p class="text-base font-semibold text-neutral-800 dark:text-neutral-100"><?php echo e(__('Belum ada cicilan tersimpan')); ?></p>
                        <p class="text-sm"><?php echo e(__('Simulasi yang Anda simpan melalui menu Transaksi Cicil Emas akan muncul di sini secara otomatis.')); ?></p>
                    </div>
                    <a
                        href="<?php echo e(route('cicil-emas.transaksi-emas')); ?>"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                    >
                        <?php echo e(__('Buat Simulasi Cicilan')); ?>

                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-hidden rounded-lg border border-neutral-200 shadow-sm dark:border-neutral-700">
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left"><?php echo e(__('Tanggal')); ?></th>
                                <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Jatuh Tempo Terdekat')); ?></th>
                                <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Nomor Cicilan')); ?></th>
                                <th scope="col" class="px-4 py-3 text-left"><?php echo e(__('Nasabah')); ?></th>
                                <th scope="col" class="px-4 py-3 text-left"><?php echo e(__('Detail Barang')); ?></th>
                                <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Harga')); ?></th>
                                <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Uang Muka')); ?></th>
                                <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Margin')); ?></th>
                                <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Administrasi')); ?></th>
                                <th scope="col" class="px-4 py-3 text-right"><?php echo e(__('Angsuran / Bln')); ?></th>
                                <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Tenor')); ?></th>
                                <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Status')); ?></th>
                                <th scope="col" class="px-4 py-3 text-center"><?php echo e(__('Aksi')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white text-sm dark:divide-neutral-700 dark:bg-neutral-900" data-cicil-emas-table>
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isHighlighted = (string) $highlightId === (string) $transaction->id;
                                    $nearestInstallment = $transaction->relationLoaded('installments')
                                        ? $transaction->installments
                                            ->filter(fn ($installment) => $installment->paid_at === null)
                                            ->sortBy('due_date')
                                            ->first()
                                        : null;

                                    $hasPendingInstallment = $nearestInstallment !== null;
                                    $nearestDueDate = $nearestInstallment?->due_date;
                                    $isOverdue = $nearestDueDate ? $nearestDueDate->isPast() : false;

                                    if (! $hasPendingInstallment && $transaction->relationLoaded('installments')) {
                                        $allInstallmentsPaid = $transaction->installments->isNotEmpty()
                                            && $transaction->installments->every(fn ($installment) => $installment->paid_at !== null);
                                    } else {
                                        $allInstallmentsPaid = false;
                                    }

                                    $status = $transaction->status;
                                    $isCancelled = $status === \App\Models\CicilEmasTransaction::STATUS_CANCELLED;
                                    $isSettled = $status === \App\Models\CicilEmasTransaction::STATUS_SETTLED;
                                    $isCompleted = $status === \App\Models\CicilEmasTransaction::STATUS_COMPLETED;
                                    $totalPaidAmount = $transaction->relationLoaded('installments')
                                        ? $transaction->installments->sum(fn ($installment) => (float) ($installment->paid_amount ?? 0))
                                        : 0;

                                    $statusBadge = [
                                        'label' => __('Aktif'),
                                        'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
                                    ];

                                    if ($isCancelled) {
                                        $statusBadge = [
                                            'label' => __('Batal'),
                                            'class' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-300',
                                        ];
                                    } elseif ($isSettled) {
                                        $statusBadge = [
                                            'label' => __('Lunas'),
                                            'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
                                        ];
                                    } elseif ($isCompleted) {
                                        $statusBadge = [
                                            'label' => __('Selesai'),
                                            'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
                                        ];
                                    }
                                ?>
                                <tr class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'bg-emerald-50/60 dark:bg-emerald-500/10' => $isHighlighted,
                                    'opacity-70 dark:opacity-60' => $isCancelled,
                                ]); ?>">
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e(optional($transaction->created_at)->translatedFormat('d M Y')); ?></span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(optional($transaction->created_at)->format('H:i')); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <?php if($hasPendingInstallment && $nearestDueDate): ?>
                                            <div class="flex flex-col">
                                                <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                    'font-semibold text-neutral-900 dark:text-white' => ! $isOverdue,
                                                    'font-semibold text-red-600 dark:text-red-400' => $isOverdue,
                                                ]); ?>">
                                                    <?php echo e($nearestDueDate->translatedFormat('d M Y')); ?>

                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    <?php echo e(__('Cicilan ke-:sequence', ['sequence' => $nearestInstallment->sequence])); ?>

                                                </span>
                                            </div>
                                        <?php elseif($allInstallmentsPaid): ?>
                                            <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-300"><?php echo e(__('Lunas')); ?></span>
                                        <?php else: ?>
                                            <span class="text-sm font-semibold text-neutral-500 dark:text-neutral-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <?php if($transaction->nomor_cicilan): ?>
                                            <span class="font-mono text-sm font-semibold text-neutral-900 dark:text-white"><?php echo e($transaction->nomor_cicilan); ?></span>
                                        <?php else: ?>
                                            <span class="text-sm font-semibold text-neutral-500 dark:text-neutral-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e($transaction->nasabah->nama ?? __('Tidak diketahui')); ?></span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e($transaction->nasabah->telepon ?? __('Tidak diketahui')); ?></span></span>
                                            <?php if($transaction->nasabah && $transaction->nasabah->kode_member): ?>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Kode Member: :kode', ['kode' => $transaction->nasabah->kode_member])); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col">
                                            <?php
                                                $items = $transaction->relationLoaded('items') ? $transaction->items : collect();
                                            ?>
                                            <?php if($items->count() === 1): ?>
                                                <?php
                                                    $item = $items->first();
                                                ?>
                                                <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e($item->nama_barang ?? $transaction->pabrikan); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) ($item->berat ?? $transaction->berat_gram), 3, ',', '.')); ?> gr • <?php echo e($item->kode_barcode ?? $item->kode_intern ?? $transaction->kadar); ?> • <?php echo e($item->kode_intern ?? $item->kode_intern ?? '—'); ?></span>
                                            <?php elseif($items->count() > 1): ?>
                                                <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e(__(':count barang', ['count' => $items->count()])); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) $transaction->berat_gram, 3, ',', '.')); ?> gr </span>
                                                <ul class="mt-1 list-disc space-y-1 ps-4 text-[11px] text-neutral-500 dark:text-neutral-400">
                                                    <?php $__currentLoopData = $items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($item->nama_barang); ?> • <?php echo e(number_format((float) ($item->berat ?? 0), 3, ',', '.')); ?> gr • <?php echo e($item->kode_barcode ?? $item->kode_intern ?? '—'); ?> • <?php echo e($item->kode_intern ?? $item->kode_intern ?? '—'); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($items->count() > 3): ?>
                                                        <li>+ <?php echo e($items->count() - 3); ?> <?php echo e(__('barang lainnya')); ?></li>
                                                    <?php endif; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e($transaction->pabrikan); ?></span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) $transaction->berat_gram, 3, ',', '.')); ?> gr • <?php echo e($transaction->kadar); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <?php echo e(number_format((float) $transaction->harga_emas, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e(number_format((float) $transaction->estimasi_uang_muka, 0, ',', '.')); ?></span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) $transaction->dp_percentage, 2, ',', '.')); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <div class="flex flex-col items-end">
                                            <span class="font-semibold text-neutral-900 dark:text-white"><?php echo e(number_format((float) $transaction->margin_amount, 0, ',', '.')); ?></span>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(number_format((float) $transaction->margin_percentage, 2, ',', '.')); ?>%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <?php echo e(number_format((float) $transaction->administrasi, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-neutral-700 dark:text-neutral-200">
                                        <?php echo e(number_format((float) $transaction->besaran_angsuran, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        <span class="inline-flex rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"><?php echo e($transaction->tenor_bulan); ?> <?php echo e(__('Bulan')); ?></span>
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        <span class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-semibold',
                                            $statusBadge['class'],
                                        ]); ?>">
                                            <?php echo e($statusBadge['label']); ?>

                                        </span>
                                        <?php if($totalPaidAmount > 0): ?>
                                            <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                <?php echo e(__('Terbayar: :amount', ['amount' => number_format($totalPaidAmount, 0, ',', '.')])); ?>

                                            </div>
                                        <?php endif; ?>
                                        <?php if($isCancelled && $transaction->cancelled_at): ?>
                                            <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                                <?php echo e(__('Dibatalkan :date', ['date' => optional($transaction->cancelled_at)->translatedFormat('d M Y H:i')])); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 align-top text-center text-neutral-700 dark:text-neutral-200">
                                        <div class="relative flex justify-center" data-more-container>
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-full border border-neutral-200 bg-white p-2 text-neutral-500 transition hover:border-neutral-300 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:border-neutral-500 dark:hover:text-white"
                                                data-more-toggle
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                <span class="sr-only"><?php echo e(__('Menu aksi cicilan')); ?></span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.008M12 12h.008M19 12h.008" />
                                                </svg>
                                            </button>
                                            <div
                                                class="absolute right-0 top-full z-10 mt-2 hidden w-56 rounded-lg border border-neutral-200 bg-white py-1 text-sm shadow-lg dark:border-neutral-600 dark:bg-neutral-900"
                                                data-more-menu
                                                role="menu"
                                            >
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                    data-action="cancel-settlement"
                                                    data-form="cancel-settlement-<?php echo e($transaction->id); ?>"
                                                    data-disabled="<?php echo e($isSettled ? 'false' : 'true'); ?>"
                                                    <?php echo e($isSettled ? '' : 'disabled'); ?>

                                                    role="menuitem"
                                                >
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    <span><?php echo e(__('Batal Pelunasan')); ?></span>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                    data-action="cancel-completion"
                                                    data-form="cancel-completion-<?php echo e($transaction->id); ?>"
                                                    data-disabled="<?php echo e($isCompleted ? 'false' : 'true'); ?>"
                                                    <?php echo e($isCompleted ? '' : 'disabled'); ?>

                                                    role="menuitem"
                                                >
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992m-4.992 5.302h4.992M2.985 7.42c0-1.886 1.536-3.42 3.43-3.42h.672c.353 0 .695.124.968.351l1.569 1.31a.61.61 0 0 0 .783 0l1.57-1.31a1.27 1.27 0 0 1 .967-.351h.673c1.894 0 3.43 1.534 3.43 3.42v9.16c0 1.886-1.536 3.42-3.43 3.42H6.415c-1.894 0-3.43-1.534-3.43-3.42z" />
                                                    </svg>
                                                    <span><?php echo e(__('Batal Penyelesaian Cicilan')); ?></span>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                                    data-action="cancel-transaction"
                                                    data-form="cancel-transaction-<?php echo e($transaction->id); ?>"
                                                    data-prompt="<?php echo e(__('Masukkan alasan pembatalan transaksi cicilan ini:')); ?>"
                                                    data-validation="<?php echo e(__('Alasan pembatalan wajib diisi.')); ?>"
                                                    data-default-reason="<?php echo e($transactionErrorId === (string) $transaction->id ? old('alasan_batal') : ''); ?>"
                                                    <?php echo e($transaction->isCancelable() ? '' : 'disabled'); ?>

                                                    role="menuitem"
                                                >
                                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    <span><?php echo e(__('Batal Transaksi Cicilan')); ?></span>
                                                </button>
                                            </div>
                                        </div>

                                        <?php if($transactionErrorId === (string) $transaction->id): ?>
                                            <?php $__errorArgs = ['alasan_batal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="mt-2 text-xs text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php endif; ?>

                                        <?php if($isCancelled && $transaction->cancellation_reason): ?>
                                            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Alasan: :reason', ['reason' => $transaction->cancellation_reason])); ?></p>
                                        <?php elseif($totalPaidAmount > 0): ?>
                                            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total angsuran tercatat :amount', ['amount' => number_format($totalPaidAmount, 0, ',', '.')])); ?></p>
                                        <?php endif; ?>

                                        <?php if($isSettled): ?>
                                            <form id="cancel-settlement-<?php echo e($transaction->id); ?>" method="POST" action="<?php echo e(route('cicil-emas.pelunasan-cicilan.cancel', $transaction)); ?>" class="hidden">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                        <?php endif; ?>

                                        <?php if($isCompleted): ?>
                                            <form id="cancel-completion-<?php echo e($transaction->id); ?>" method="POST" action="<?php echo e(route('cicil-emas.penyelesaian-cicilan.cancel', $transaction)); ?>" class="hidden">
                                                <?php echo csrf_field(); ?>
                                            </form>
                                        <?php endif; ?>

                                        <form id="cancel-transaction-<?php echo e($transaction->id); ?>" method="POST" action="<?php echo e(route('cicil-emas.transaksi.cancel', $transaction)); ?>" class="hidden">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="alasan_batal" value="">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col gap-4 border border-t-0 border-neutral-200 bg-white p-4 text-sm dark:border-neutral-700 dark:bg-neutral-900 sm:flex-row sm:items-center sm:justify-between">
                    <form method="GET" action="<?php echo e(route('cicil-emas.daftar-cicilan')); ?>" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <?php $__currentLoopData = request()->except(['per_page', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="hidden" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <label for="rows-per-page" class="text-xm font-semibold tracking-wide text-neutral-500 dark:text-neutral-400">
                            <?php echo e(__('Rows per page')); ?>

                            <span class="ms-1 inline-flex items-center rounded-full bg-neutral-200 px-2 py-0.5 text-[11px] font-semibold text-neutral-700 dark:bg-neutral-700 dark:text-neutral-200"><?php echo e($perPage); ?></span>
                        </label>
                        <select
                            id="rows-per-page"
                            name="per_page"
                            class="flex items-center gap-3 rounded-lg border border-neutral-300 bg-white px-1 py-1 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 sm:w-32"
                            onchange="this.form.submit()"
                        >
                            <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($option); ?>" <?php if($option === $perPage): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>

                    <?php
                        $currentPage = $transactions->currentPage();
                        $lastPage = $transactions->lastPage();
                    ?>

                    <nav class="flex flex-wrap items-center gap-2" aria-label="<?php echo e(__('Pagination')); ?>">
                        <a
                            href="<?php echo e($transactions->url(1)); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $transactions->onFirstPage(),
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $transactions->onFirstPage(),
                            ]); ?>"
                            <?php if($transactions->onFirstPage()): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        >
                            &laquo; <?php echo e(__('First')); ?>

                        </a>
                        <a
                            href="<?php echo e($transactions->previousPageUrl() ?? $transactions->url(1)); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $transactions->onFirstPage(),
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $transactions->onFirstPage(),
                            ]); ?>"
                            <?php if($transactions->onFirstPage()): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        >
                            &lt; <?php echo e(__('Back')); ?>

                        </a>

                        <?php for($page = 1; $page <= $lastPage; $page++): ?>
                            <?php
                                $isActive = $page === $currentPage;
                            ?>
                            <a
                                href="<?php echo e($transactions->url($page)); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'inline-flex items-center justify-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                    'border-neutral-900 bg-neutral-900 text-white dark:border-white dark:bg-white dark:text-neutral-900' => $isActive,
                                    'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => ! $isActive,
                                ]); ?>"
                                <?php if($isActive): ?> aria-current="page" <?php endif; ?>
                            >
                                <?php echo e($page); ?>

                            </a>
                        <?php endfor; ?>

                        <a
                            href="<?php echo e($transactions->nextPageUrl() ?? $transactions->url($lastPage)); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => $currentPage < $lastPage,
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $currentPage >= $lastPage,
                            ]); ?>"
                            <?php if($currentPage >= $lastPage): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        >
                            <?php echo e(__('Next')); ?> &gt;
                        </a>
                        <a
                            href="<?php echo e($transactions->url($lastPage)); ?>"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'inline-flex items-center rounded-lg border px-3 py-1.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500/40',
                                'border-neutral-300 text-neutral-600 hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-800' => $currentPage < $lastPage,
                                'pointer-events-none border-neutral-300 text-neutral-400 opacity-60 dark:border-neutral-600 dark:text-neutral-500' => $currentPage >= $lastPage,
                            ]); ?>"
                            <?php if($currentPage >= $lastPage): ?> aria-disabled="true" tabindex="-1" <?php endif; ?>
                        >
                            <?php echo e(__('Last')); ?> &raquo;
                        </a>
                    </nav>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        (() => {
            const table = document.querySelector('[data-cicil-emas-table]');
            let activeDropdown = null;

            const closeDropdown = () => {
                if (!activeDropdown) return;
                const { menu, toggle } = activeDropdown;
                menu.classList.add('hidden');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
                activeDropdown = null;
            };

            if (table) {
                table.addEventListener('click', (event) => {
                    const toggle = event.target.closest('[data-more-toggle]');
                    if (toggle) {
                        event.preventDefault();
                        const container = toggle.closest('[data-more-container]');
                        const menu = container?.querySelector('[data-more-menu]');

                        if (!menu) return;

                        if (activeDropdown?.menu === menu) {
                            closeDropdown();
                            return;
                        }

                        closeDropdown();
                        menu.classList.remove('hidden');
                        toggle.setAttribute('aria-expanded', 'true');
                        activeDropdown = { menu, toggle };
                        return;
                    }

                    const cancelSettlement = event.target.closest('[data-action="cancel-settlement"]');
                    if (cancelSettlement) {
                        event.preventDefault();
                        if (cancelSettlement.disabled || cancelSettlement.dataset.disabled === 'true') {
                            return;
                        }

                        const formId = cancelSettlement.dataset.form;
                        const form = formId ? document.getElementById(formId) : null;

                        if (!form) return;

                        const confirmed = window.confirm('<?php echo e(__('Batalkan pelunasan dan kembalikan cicilan menjadi aktif?')); ?>');

                        if (confirmed) {
                            form.submit();
                            closeDropdown();
                        }

                        return;
                    }

                    const cancelCompletion = event.target.closest('[data-action="cancel-completion"]');
                    if (cancelCompletion) {
                        event.preventDefault();
                        if (cancelCompletion.disabled || cancelCompletion.dataset.disabled === 'true') {
                            return;
                        }

                        const formId = cancelCompletion.dataset.form;
                        const form = formId ? document.getElementById(formId) : null;

                        if (!form) return;

                        const confirmed = window.confirm('<?php echo e(__('Batalkan penyelesaian cicilan dan kembalikan menjadi aktif?')); ?>');

                        if (confirmed) {
                            form.submit();
                            closeDropdown();
                        }

                        return;
                    }

                    const cancelTransaction = event.target.closest('[data-action="cancel-transaction"]');
                    if (cancelTransaction) {
                        event.preventDefault();
                        if (cancelTransaction.disabled) {
                            return;
                        }

                        const promptText = cancelTransaction.dataset.prompt || '';
                        const validationText = cancelTransaction.dataset.validation || '';
                        const defaultReason = cancelTransaction.dataset.defaultReason || '';
                        const formId = cancelTransaction.dataset.form;
                        const form = formId ? document.getElementById(formId) : null;

                        if (!form) return;

                        const reason = window.prompt(promptText, defaultReason);
                        if (reason === null) {
                            return;
                        }

                        const trimmed = reason.trim();
                        if (!trimmed) {
                            if (validationText) {
                                alert(validationText);
                            }
                            return;
                        }

                        const input = form.querySelector('input[name="alasan_batal"]');
                        if (input) {
                            input.value = trimmed;
                        }

                        form.submit();
                        closeDropdown();
                        return;
                    }

                    if (event.target.closest('[data-more-menu]')) {
                        return;
                    }

                    closeDropdown();
                });
            }

            document.addEventListener('click', (event) => {
                if (!activeDropdown) return;
                if (table && table.contains(event.target)) return;
                closeDropdown();
            });
        })();
    </script>

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
<?php /**PATH /var/www/geka/gd/resources/views/cicil-emas/daftar-cicilan.blade.php ENDPATH**/ ?>