<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Riwayat Cicilan')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Riwayat Cicilan'))]); ?>
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Riwayat Cicilan')); ?></h1>
            <!-- <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Pantau status pembayaran, saldo pokok tersisa, serta nilai aset emas terbaru untuk setiap portofolio cicilan.')); ?>

            </p> -->
            <p class="text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('Pantau status pembayaran dan saldo pokok tersisa untuk setiap portofolio cicilan.')); ?>

            </p>
        </div>

        <form method="GET" class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <div class="flex flex-col gap-3 md:flex-row md:items-end">
                <div class="flex-1">
                    <label for="search" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Cari Nasabah')); ?></label>
                    <div class="mt-2">
                        <input
                            id="search"
                            name="q"
                            type="search"
                            value="<?php echo e($filters['query'] ?? ''); ?>"
                            placeholder="<?php echo e(__('Nama atau kode member')); ?>"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm text-neutral-900 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                        >
                    </div>
                </div>

                <div class="md:w-48">
                    <label for="status" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Status')); ?></label>
                    <div class="mt-2">
                        <?php
                            $statusOptions = [
                                '' => __('Semua status'),
                                'aktif' => __('Aktif'),
                                'menunggak' => __('Menunggak'),
                                'lunas' => __('Lunas'),
                            ];
                        ?>
                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm text-neutral-900 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white"
                        >
                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(($filters['status'] ?? '') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
<span></span>
                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                        <?php echo e(__('Cari Data')); ?>

                    </button>
                    <?php if(($filters['query'] ?? null) || ($filters['status'] ?? null)): ?>
                        <a href="<?php echo e(route('cicil-emas.riwayat-cicilan')); ?>" class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-800 dark:focus:ring-offset-neutral-900">
                            <?php echo e(__('Atur ulang')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- <?php if(($hasQuery ?? false) && ($insights instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) && $insights->total()): ?>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Menampilkan :from–:to dari :total hasil', [
                        'from' => number_format($insights->firstItem(), 0, ',', '.'),
                        'to' => number_format($insights->lastItem(), 0, ',', '.'),
                        'total' => number_format($insights->total(), 0, ',', '.'),
                    ])); ?>

                </p>
            <?php elseif(($hasQuery ?? false) && ($insights->count() ?? 0) > 0 && $totalTransactions): ?>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Menampilkan :count hasil', ['count' => number_format($insights->count(), 0, ',', '.')])); ?>

                </p>
            <?php endif; ?> -->
        </form>

        <section class="grid gap-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
            <?php if(! ($hasQuery ?? false)): ?>
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white"><?php echo e(__('Cari riwayat cicilan nasabah')); ?></h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Mulai dengan memasukkan nama atau kode member pada kolom pencarian di atas untuk menampilkan data cicilan.')); ?></p>
                </div>
            <?php elseif(($insights->count() ?? 0) === 0): ?>
                <div class="rounded-xl border border-dashed border-neutral-300 bg-white p-10 text-center shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-neutral-800 dark:text-white"><?php echo e(__('Tidak ditemukan riwayat cicilan.')); ?></h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                        <?php echo e(__('Tidak ada nasabah yang cocok dengan pencarian ":query".', ['query' => $filters['query'] ?? ''])); ?>

                    </p>
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Coba gunakan nama lengkap atau kode member lainnya.')); ?></p>
                </div>
            <?php else: ?>
                <div class="flex flex-col gap-6">
                    <?php $__currentLoopData = $insights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $transaction = $insight['model'];
                            $nasabah = $transaction->nasabah;
                            $items = collect($insight['items'] ?? []);
                            $primaryItem = $items->first();
                            $installments = $insight['installments'];
                            $visibleInstallments = $installments->take(4);
                            $hiddenInstallments = $installments->slice(4);
                            $today = now()->startOfDay();
                            $statusClass = [
                                'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-200',
                                'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200',
                            ][$insight['status_style'] ?? 'info'] ?? 'bg-neutral-200 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200';
                        ?>
                        <article x-data="{ showAllInstallments: false }" class="flex flex-col gap-6 rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-neutral-700 dark:bg-neutral-900">
                        <header class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    <?php echo e($nasabah?->nama ?? __('Nasabah tidak ditemukan')); ?>

                                </h3>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-neutral-600 dark:text-neutral-300">
                                    <span><?php echo e(__('Kode Member: :kode', ['kode' => $nasabah?->kode_member ?? '—'])); ?></span>
                                    <span>•</span>
                                    <span><?php echo e(__('Dibuat :tanggal', ['tanggal' => $transaction->created_at?->translatedFormat('d F Y H:i')])); ?></span>
                                </div>
                                <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    <?php if($items->count() === 1): ?>
                                        <?php echo e($primaryItem['nama_barang'] ?? $transaction->pabrikan); ?> ·
                                        <?php echo e(number_format((float) ($primaryItem['berat'] ?? $transaction->berat_gram), 3, ',', '.')); ?> gr ·
                                        <?php echo e($primaryItem['kode'] ?? $transaction->kadar); ?>

                                    <?php elseif($items->count() > 1): ?>
                                        <?php echo e(__(':count barang', ['count' => $items->count()])); ?> ·
                                        <?php echo e(number_format($transaction->berat_gram, 3, ',', '.')); ?> gr ·
                                        <?php echo e($transaction->kadar); ?>

                                    <?php else: ?>
                                        <?php echo e($transaction->pabrikan); ?> · <?php echo e(number_format($transaction->berat_gram, 3, ',', '.')); ?> gr · <?php echo e($transaction->kadar); ?>

                                    <?php endif; ?>
                                </div>
                                <?php if($items->count() > 1): ?>
                                    <ul class="mt-1 list-disc space-y-1 ps-5 text-xs text-neutral-500 dark:text-neutral-400">
                                        <?php $__currentLoopData = $items->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <?php echo e($item['nama_barang'] ?? __('Barang')); ?> •
                                                <?php echo e(number_format((float) ($item['berat'] ?? 0), 3, ',', '.')); ?> gr •
                                                <?php echo e($item['kode'] ?? '—'); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($items->count() > 3): ?>
                                            <li>+ <?php echo e($items->count() - 3); ?> <?php echo e(__('barang lainnya')); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Margin')); ?> <?php echo e(number_format($insight['margin_percentage'] ?? 0, 2, ',', '.')); ?>% • Rp <?php echo e(number_format($insight['margin_amount'] ?? 0, 0, ',', '.')); ?>

                                </div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Biaya administrasi: Rp :amount', ['amount' => number_format($insight['administrasi'] ?? 0, 0, ',', '.')])); ?>

                                </div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Pokok pembiayaan: Rp :amount', ['amount' => number_format($insight['principal_without_margin'] ?? 0, 0, ',', '.')])); ?>

                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusClass); ?>">
                                    <?php echo e(__($insight['status'])); ?>

                                </span>
                                <div class="text-right text-sm text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Progres pembayaran: :progress%', ['progress' => number_format($insight['completion_ratio'], 2, ',', '.')])); ?>

                                </div>
                            </div>
                        </header>

                        <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nilai Emas Awal')); ?></dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format($transaction->harga_emas, 0, ',', '.')); ?></dd>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nilai Emas Terkini')); ?></dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">Rp <?php echo e(number_format($insight['current_gold_value'], 0, ',', '.')); ?></dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Δ :value', ['value' => ( $insight['gold_delta'] >= 0 ? '+' : '−') . 'Rp ' . number_format(abs($insight['gold_delta']), 0, ',', '.') ])); ?></p>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Saldo Pembiayaan Tersisa')); ?></dt>
                                <dd class="mt-1 text-lg font-semibold text-amber-600 dark:text-amber-300">Rp <?php echo e(number_format($insight['outstanding_principal'], 0, ',', '.')); ?></dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Tenor :bulan bulan · Angsuran Rp :angsuran', ['bulan' => $transaction->tenor_bulan, 'angsuran' => number_format($transaction->besaran_angsuran, 0, ',', '.')])); ?>

                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Total pembiayaan: Rp :amount', ['amount' => number_format($insight['total_financed'] ?? 0, 0, ',', '.')])); ?>

                                </p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Administrasi: Rp :amount', ['amount' => number_format($insight['administrasi'] ?? 0, 0, ',', '.')])); ?>

                                </p>
                            </div>
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Pembayaran Terakhir')); ?></dt>
                                <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">
                                    <?php if($insight['last_payment']): ?>
                                        <?php echo e($insight['last_payment']->paid_at?->translatedFormat('d M Y')); ?>

                                    <?php else: ?>
                                        <?php echo e(__('Belum ada pembayaran')); ?>

                                    <?php endif; ?>
                                </dd>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    <?php echo e(__('Total dibayar: Rp :amount', ['amount' => number_format($insight['total_paid'], 0, ',', '.')])); ?>

                                </p>
                            </div>
                        </section>

                        <section class="flex flex-col gap-3">
                            <h4 class="text-sm font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Riwayat Pembayaran')); ?></h4>
                            <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                                    <thead class="bg-neutral-50 dark:bg-neutral-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Termin')); ?></th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Jatuh Tempo')); ?></th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Angsuran')); ?></th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Denda')); ?></th>
                                            <th class="px-4 py-3 text-left font-semibold text-neutral-600 dark:text-neutral-300"><?php echo e(__('Status Pembayaran')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                        <?php $__currentLoopData = $visibleInstallments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $paid = $installment->paid_at !== null;
                                                $isOverdue = ! $paid && $installment->due_date->lt($today);
                                            ?>
                                            <tr class="bg-white hover:bg-neutral-50 dark:bg-neutral-900 dark:hover:bg-neutral-800/60">
                                                <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-200"><?php echo e(__('Angsuran :sequence', ['sequence' => $installment->sequence])); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300"><?php echo e($installment->due_date->translatedFormat('d M Y')); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($installment->amount, 0, ',', '.')); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($installment->penalty_amount ?? 0, 0, ',', '.')); ?></td>
                                                <td class="px-4 py-3">
                                                    <?php if($paid): ?>
                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300"><?php echo e(__('Lunas :tanggal', ['tanggal' => $installment->paid_at?->translatedFormat('d M Y')])); ?></span>
                                                    <?php elseif($isOverdue): ?>
                                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-200"><?php echo e(__('Terlambat')); ?></span>
                                                    <?php else: ?>
                                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-200"><?php echo e(__('Belum dibayar')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $hiddenInstallments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $paid = $installment->paid_at !== null;
                                                $isOverdue = ! $paid && $installment->due_date->lt($today);
                                            ?>
                                            <tr class="bg-white hover:bg-neutral-50 dark:bg-neutral-900 dark:hover:bg-neutral-800/60" x-show="showAllInstallments" x-transition>
                                                <td class="px-4 py-3 font-medium text-neutral-800 dark:text-neutral-200"><?php echo e(__('Angsuran :sequence', ['sequence' => $installment->sequence])); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300"><?php echo e($installment->due_date->translatedFormat('d M Y')); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($installment->amount, 0, ',', '.')); ?></td>
                                                <td class="px-4 py-3 text-neutral-600 dark:text-neutral-300">Rp <?php echo e(number_format($installment->penalty_amount ?? 0, 0, ',', '.')); ?></td>
                                                <td class="px-4 py-3">
                                                    <?php if($paid): ?>
                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300"><?php echo e(__('Lunas :tanggal', ['tanggal' => $installment->paid_at?->translatedFormat('d M Y')])); ?></span>
                                                    <?php elseif($isOverdue): ?>
                                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-200"><?php echo e(__('Terlambat')); ?></span>
                                                    <?php else: ?>
                                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-200"><?php echo e(__('Belum dibayar')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <?php if($hiddenInstallments->isNotEmpty()): ?>
                                    <div class="flex justify-end">
                                        <button
                                            type="button"
                                            class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-purple-600 hover:text-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 dark:text-purple-300 dark:hover:text-purple-200 dark:focus:ring-offset-neutral-900"
                                            x-on:click="showAllInstallments = !showAllInstallments"
                                            x-bind:aria-expanded="showAllInstallments"
                                        >
                                            <span x-show="!showAllInstallments"><?php echo e(__('Lihat semua angsuran (:count)', ['count' => $installments->count()])); ?></span>
                                            <span x-show="showAllInstallments"><?php echo e(__('Sembunyikan riwayat lengkap')); ?></span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-4">
                <?php if($insights instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator): ?>
                    <?php echo e($insights->onEachSide(1)->links()); ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($hasQuery ?? false): ?>
            <header class="flex items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wide text-purple-500"><?php echo e(__('Ringkasan Portofolio')); ?></span>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Snapshot Kinerja Cicil Emas')); ?></h2>
                </div>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900/40 dark:text-purple-200">
                    <?php echo e(number_format($portfolio['total_transactions'] ?? 0, 0, ',', '.')); ?> <?php echo e(__('transaksi')); ?>

                </span>
            </header>

            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Pokok Tercatat')); ?></dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-neutral-200">Rp <?php echo e(number_format($portfolio['total_principal'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Saldo Pokok Tersisa')); ?></dt>
                    <dd class="text-xl font-semibold text-amber-600 dark:text-amber-300">Rp <?php echo e(number_format($portfolio['total_outstanding'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Pembayaran Tercatat')); ?></dt>
                    <dd class="text-xl font-semibold text-emerald-600 dark:text-emerald-300">Rp <?php echo e(number_format($portfolio['total_paid'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total Administrasi Tercatat')); ?></dt>
                    <dd class="text-xl font-semibold text-neutral-900 dark:text-neutral-400">Rp <?php echo e(number_format($portfolio['total_administration'] ?? 0, 0, ',', '.')); ?></dd>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900">
                    <dt class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__('Rata-rata Penyelesaian')); ?></dt>
                    <dd class="text-xl font-semibold text-blue-600 dark:text-blue-300"><?php echo e(number_format($portfolio['average_completion'] ?? 0, 2, ',', '.')); ?>%</dd>
                </div>
            </dl>

            <div class="grid gap-3 sm:grid-cols-3">
                <?php
                    $statusColors = [
                        'Aktif' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                        'Menunggak' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200',
                        'Lunas' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200',
                    ];
                ?>
                <?php $__currentLoopData = ($portfolio['status_buckets'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-col rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                        <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400"><?php echo e(__($status)); ?></span>
                        <div class="mt-2 flex items-end justify-between">
                            <span class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e($count); ?></span>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold <?php echo e($statusColors[$status] ?? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200'); ?>">
                                <?php echo e(__('Status')); ?>

                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
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
<?php /**PATH /var/www/gd/resources/views/cicil-emas/riwayat-cicilan.blade.php ENDPATH**/ ?>