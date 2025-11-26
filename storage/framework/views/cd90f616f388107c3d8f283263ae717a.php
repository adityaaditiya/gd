<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <?php if (isset($component)) { $__componentOriginal17e56bc23bb0192e474b351c4358d446 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal17e56bc23bb0192e474b351c4358d446 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.index','data' => ['sticky' => true,'stashable' => true,'class' => 'border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['sticky' => true,'stashable' => true,'class' => 'border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900']); ?>
            <?php if (isset($component)) { $__componentOriginal1b6467b07b302021134396bbd98e74a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1b6467b07b302021134396bbd98e74a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.toggle','data' => ['class' => 'lg:hidden','icon' => 'x-mark']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden','icon' => 'x-mark']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $attributes = $__attributesOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__attributesOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $component = $__componentOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__componentOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>

            <a href="<?php echo e(route('dashboard')); ?>" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <?php if (isset($component)) { $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $attributes = $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $component = $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
            </a>

            <?php
                $gadaiRoutes = [
                    'gadai.pemberian-kredit',
                    'gadai.lihat-gadai',
                    'gadai.lihat-barang-gadai',
                    'gadai.lihat-data-lelang',
                ];
                $isGadaiActive = request()->routeIs(...$gadaiRoutes);
                $laporanRoutes = [
                    'laporan.saldo-kas',
                    'laporan.transaksi-gadai',
                    'laporan.pelunasan-gadai',
                    'laporan.batal-gadai',
                    'laporan.perpanjangan-gadai',
                    'laporan.lelang',
                    'laporan.cicil-emas',
                ];
                $isLaporanActive = request()->routeIs(...$laporanRoutes);
                $akuntansiRoutes = [
                    'akuntansi.jurnal',
                    'akuntansi.buku-besar',
                    'akuntansi.neraca-percobaan',
                    'akuntansi.laba-rugi',
                    'akuntansi.neraca',
                ];
                $isAkuntansiActive = request()->routeIs(...$akuntansiRoutes);
                $cicilEmasRoutes = [
                    'cicil-emas.transaksi-emas',
                    'cicil-emas.transaksi-emas.store',
                    'cicil-emas.daftar-cicilan',
                    'cicil-emas.angsuran-rutin',
                    'cicil-emas.riwayat-cicilan',
                    'cicil-emas.pelunasan-cicilan',
                    'cicil-emas.penyelesaian-cicilan',
                ];
                $isCicilEmasActive = request()->routeIs(...$cicilEmasRoutes);
                $jualEmasRoutes = ['jual-emas.transaksi-penjualan', 'jual-emas.lihat-penjualan', 'jual-emas.batal-penjualan'];
                $isJualEmasActive = request()->routeIs(...$jualEmasRoutes);
                $beliEmasRoutes = ['beli-emas.transaksi-pembelian', 'beli-emas.lihat-pembelian', 'beli-emas.batal-pembelian'];
                $isBeliEmasActive = request()->routeIs(...$beliEmasRoutes);
                $titipEmasRoutes = ['titip-emas.transaksi-titip-emas', 'titip-emas.lihat-titipan'];
                $isTitipEmasActive = request()->routeIs(...$titipEmasRoutes);
                $barangRoutes = ['barang.data-barang'];
                $isBarangActive = request()->routeIs(...$barangRoutes);
                $nasabahRoutes = [
                    'nasabah.tambah-nasabah',
                    'nasabah.data-nasabah',
                    'nasabah.lihat-transaksi-nasabah',
                    'nasabah.nasabah-baru',
                    'nasabah.cdd-nasabah',
                ];
                $isNasabahActive = request()->routeIs(...$nasabahRoutes);
                $masterRoutes = ['admin.users.*', 'admin.pages.*', 'admin.master-kode-group.*'];
                $isMasterActive = request()->routeIs(...$masterRoutes);
            ?>

            <nav class="flex flex-col gap-3">
                <div>
                    <a
                        href="<?php echo e(route('dashboard')); ?>"
                        wire:navigate
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors duration-200',
                            'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('dashboard'),
                            'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('dashboard'),
                        ]); ?>"
                    >
                        <svg
                            class="size-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 12 9-9 9 9M4.5 9.75v10.125A1.125 1.125 0 0 0 5.625 21h12.75A1.125 1.125 0 0 0 19.5 19.875V9.75"
                            />
                        </svg>
                        <span><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="gadai-menu"
                        aria-expanded="<?php echo e($isGadaiActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 6.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25C7.5 9.246 6.996 9.75 6.375 9.75h-2.25A1.125 1.125 0 0 1 3 8.625v-2.25Zm0 8.25c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 16.875v-2.25ZM9.75 6.375c0-.621.504-1.125 1.125-1.125h10.5c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-10.5A1.125 1.125 0 0 1 9.75 8.625v-2.25Zm0 8.25c0-.621.504-1.125 1.125-1.125h10.5c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-10.5a1.125 1.125 0 0 1-1.125-1.125v-2.25Z"
                                />
                            </svg>
                            <span><?php echo e(__('Gadai Elektronik')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isGadaiActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="gadai-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isGadaiActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('gadai.pemberian-kredit')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.pemberian-kredit'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.pemberian-kredit'),
                            ]); ?>"
                        >
                            <?php echo e(__('Pemberian Kredit')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('gadai.lihat-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('gadai.lihat-barang-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-barang-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-barang-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Barang Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('gadai.lihat-data-lelang')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-data-lelang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-data-lelang'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Data Lelang')); ?>

                        </a>
                    </div>
                </div>
                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="cicil-emas-menu"
                        aria-expanded="<?php echo e($isCicilEmasActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.75 6a2.25 2.25 0 0 1 2.25-2.25h12a2.25 2.25 0 0 1 2.25 2.25v1.5A2.25 2.25 0 0 1 18 9.75H6A2.25 2.25 0 0 1 3.75 7.5V6Zm0 7.5A2.25 2.25 0 0 1 6 11.25h12a2.25 2.25 0 0 1 2.25 2.25v1.5A2.25 2.25 0 0 1 18 17.25H6a2.25 2.25 0 0 1-2.25-2.25v-1.5Zm6.75 5.25a.75.75 0 0 1 .75-.75h2a.75.75 0 0 1 0 1.5h-2a.75.75 0 0 1-.75-.75Z"
                                />
                            </svg>
                            <span><?php echo e(__('Cicil Emas')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isCicilEmasActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="cicil-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isCicilEmasActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('cicil-emas.transaksi-emas')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.transaksi-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.transaksi-emas'),
                            ]); ?>"
                        >
                            <?php echo e(__('Transaksi Emas')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('cicil-emas.daftar-cicilan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.daftar-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.daftar-cicilan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Daftar Cicilan')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('cicil-emas.angsuran-rutin')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.angsuran-rutin'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.angsuran-rutin'),
                            ]); ?>"
                        >
                            <?php echo e(__('Angsuran Rutin')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('cicil-emas.riwayat-cicilan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.riwayat-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.riwayat-cicilan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Riwayat Cicilan')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('cicil-emas.pelunasan-cicilan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.pelunasan-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.pelunasan-cicilan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Pelunasan Cicilan')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('cicil-emas.penyelesaian-cicilan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('cicil-emas.penyelesaian-cicilan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('cicil-emas.penyelesaian-cicilan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Penyelesaian Cicilan')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="jual-emas-menu"
                        aria-expanded="<?php echo e($isJualEmasActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 6H13.5M13.5 6 10.5 3m3 3-3 3M19.5 18H10.5m0 0 3 3m-3-3 3-3M4.5 12h15"
                                />
                            </svg>
                            <span><?php echo e(__('Jual Emas')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isJualEmasActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="jual-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isJualEmasActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('jual-emas.transaksi-penjualan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.transaksi-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.transaksi-penjualan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Transaksi Penjualan')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('jual-emas.lihat-penjualan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.lihat-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.lihat-penjualan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Penjualan')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('jual-emas.batal-penjualan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('jual-emas.batal-penjualan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('jual-emas.batal-penjualan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Batal Penjualan')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="beli-emas-menu"
                        aria-expanded="<?php echo e($isBeliEmasActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20.25 7.5l-8.954 8.955a1.125 1.125 0 0 1-1.591 0L3.75 10.5m16.5-3L15 3.75M20.25 7.5 15 12.75"
                                />
                            </svg>
                            <span><?php echo e(__('Beli Emas')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isBeliEmasActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="beli-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isBeliEmasActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('beli-emas.transaksi-pembelian')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.transaksi-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.transaksi-pembelian'),
                            ]); ?>"
                        >
                            <?php echo e(__('Transaksi Pembelian')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('beli-emas.lihat-pembelian')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.lihat-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.lihat-pembelian'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Pembelian')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('beli-emas.batal-pembelian')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('beli-emas.batal-pembelian'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('beli-emas.batal-pembelian'),
                            ]); ?>"
                        >
                            <?php echo e(__('Batal Pembelian')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="titip-emas-menu"
                        aria-expanded="<?php echo e($isTitipEmasActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.25 12a9.75 9.75 0 0 1 19.5 0v6a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 18V12Zm9-4.5h1.5a1.5 1.5 0 0 1 1.5 1.5v1.5h-4.5V9a1.5 1.5 0 0 1 1.5-1.5Zm-1.5 6h4.5V18h-4.5v-4.5Z"
                                />
                            </svg>
                            <span><?php echo e(__('Titip Emas')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isTitipEmasActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="titip-emas-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isTitipEmasActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('titip-emas.transaksi-titip-emas')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('titip-emas.transaksi-titip-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('titip-emas.transaksi-titip-emas'),
                            ]); ?>"
                        >
                            <?php echo e(__('Transaksi Titip Emas')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('titip-emas.lihat-titipan')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('titip-emas.lihat-titipan'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('titip-emas.lihat-titipan'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Titipan')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="barang-menu"
                        aria-expanded="<?php echo e($isBarangActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3.375 19.5h17.25M4.5 8.25h15a.75.75 0 0 1 .75.75v9.75a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V9a.75.75 0 0 1 .75-.75Zm.75-3h13.5a.75.75 0 0 1 .75.75V8.25H4.5V6a.75.75 0 0 1 .75-.75Z"
                                />
                            </svg>
                            <span><?php echo e(__('Barang')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isBarangActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="barang-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isBarangActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('barang.data-barang')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('barang.data-barang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('barang.data-barang'),
                            ]); ?>"
                        >
                            <?php echo e(__('Data Barang')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="nasabah-menu"
                        aria-expanded="<?php echo e($isNasabahActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.837 0-5.518-.652-7.499-1.632Z"
                                />
                            </svg>
                            <span><?php echo e(__('Nasabah')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isNasabahActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="nasabah-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isNasabahActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('nasabah.data-nasabah')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.data-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.data-nasabah'),
                            ]); ?>"
                        >
                            <?php echo e(__('Data Nasabah')); ?>

                        </a>
                                                <a
                            href="<?php echo e(route('nasabah.lihat-transaksi-nasabah')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                            ]); ?>"
                        >
                            <?php echo e(__('Lihat Transaksi Nasabah')); ?>

                        </a>
                        </a>
                                                <a
                            href="<?php echo e(route('nasabah.nasabah-baru')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.lihat-transaksi-nasabah'),
                            ]); ?>"
                        >
                            <?php echo e(__('Data Nasabah Baru')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('nasabah.cdd-nasabah')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('nasabah.cdd-nasabah'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('nasabah.cdd-nasabah'),
                            ]); ?>"
                        >
                            <?php echo e(__('CDD Nasabah')); ?>

                        </a>
                    </div>
                </div>
<div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="laporan-menu"
                        aria-expanded="<?php echo e($isLaporanActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 4.5h18M3 9h18M9 13.5h12M9 18h12M3 13.5h3M3 18h3"
                                />
                            </svg>
                            <span><?php echo e(__('Laporan')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isLaporanActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="laporan-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isLaporanActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('laporan.saldo-kas')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.saldo-kas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.saldo-kas'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Saldo Kas')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.transaksi-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.transaksi-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.transaksi-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Transaksi Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.pelunasan-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.pelunasan-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.pelunasan-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Pelunasan Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.batal-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.batal-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.batal-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Pembatalan Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.perpanjangan-gadai')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.perpanjangan-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.perpanjangan-gadai'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Perpanjangan Gadai')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.lelang')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.lelang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.lelang'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Lelang')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('laporan.cicil-emas')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('laporan.cicil-emas'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('laporan.cicil-emas'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laporan Cicil Emas')); ?>

                        </a>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="akuntansi-menu"
                        aria-expanded="<?php echo e($isAkuntansiActive ? 'true' : 'false'); ?>"
                    >
                        <span class="flex items-center gap-2">
                            <svg
                                class="size-5"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3v18m-7.5-9h15M5.25 8.25h13.5m-13.5 7.5h13.5"
                                />
                            </svg>
                            <span><?php echo e(__('Akuntansi')); ?></span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 <?php echo e($isAkuntansiActive ? 'rotate-90' : ''); ?>"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                    <div
                        id="akuntansi-menu"
                        class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                        style="max-height: <?php echo e($isAkuntansiActive ? '500px' : '0px'); ?>;"
                    >
                        <a
                            href="<?php echo e(route('akuntansi.jurnal')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.jurnal'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.jurnal'),
                            ]); ?>"
                        >
                            <?php echo e(__('Jurnal')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('akuntansi.buku-besar')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.buku-besar'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.buku-besar'),
                            ]); ?>"
                        >
                            <?php echo e(__('Buku Besar')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('akuntansi.neraca')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.neraca'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.neraca'),
                            ]); ?>"
                        >
                            <?php echo e(__('Neraca')); ?>

                        </a>
                        <a
                            href="<?php echo e(route('akuntansi.laba-rugi')); ?>"
                            wire:navigate
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('akuntansi.laba-rugi'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('akuntansi.laba-rugi'),
                            ]); ?>"
                        >
                            <?php echo e(__('Laba Rugi')); ?>

                        </a>
                    </div>
                </div>
                <?php if(auth()->user()?->hasAdminAccess()): ?>
                    <div>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                            data-accordion-toggle
                            data-accordion-target="master-menu"
                            aria-expanded="<?php echo e($isMasterActive ? 'true' : 'false'); ?>"
                        >
                            <span class="flex items-center gap-2">
                                <svg
                                    class="size-5"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 3h7.5v7.5H3V3Zm10.5 0H21v7.5h-7.5V3ZM3 13.5h7.5V21H3v-7.5Zm10.5 0H21V21h-7.5v-7.5Z"
                                    />
                                </svg>
                                <span><?php echo e(__('Master')); ?></span>
                            </span>
                            <svg
                                data-accordion-icon
                                class="size-4 transform transition-transform duration-300 <?php echo e($isMasterActive ? 'rotate-90' : ''); ?>"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        <div
                            id="master-menu"
                            class="ms-3 mt-1 space-y-1 overflow-hidden text-sm transition-all duration-300"
                            style="max-height: <?php echo e($isMasterActive ? '500px' : '0px'); ?>;"
                        >
                            <a
                                href="<?php echo e(route('admin.users.index')); ?>"
                                wire:navigate
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.users.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.users.*'),
                                ]); ?>"
                            >
                                <?php echo e(__('Master User')); ?>

                            </a>
                            <a
                                href="<?php echo e(route('admin.pages.index')); ?>"
                                wire:navigate
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.pages.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.pages.*'),
                                ]); ?>"
                            >
                                <?php echo e(__('Halaman Baru')); ?>

                            </a>
                            <a
                                href="<?php echo e(route('admin.master-kode-group.index')); ?>"
                                wire:navigate
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.master-kode-group.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.master-kode-group.*'),
                                ]); ?>"
                            >
                                <?php echo e(__('Master Kode Group')); ?>

                            </a>
                            <a
                                href="<?php echo e(route('admin.master-perhitungan-gadai.index')); ?>"
                                wire:navigate
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.master-perhitungan-gadai.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.master-perhitungan-gadai.*'),
                                ]); ?>"
                            >
                                <?php echo e(__('Master Perhitungan Gadai')); ?>

                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </nav>

            <?php if (isset($component)) { $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $attributes = $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $component = $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>

            <!-- <?php if (isset($component)) { $__componentOriginalacac6a48a34186ea0abd369a00e5e2d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.index','data' => ['variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline']); ?>
                <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'folder-git-2','href' => 'https://github.com/laravel/livewire-starter-kit','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'folder-git-2','href' => 'https://github.com/laravel/livewire-starter-kit','target' => '_blank']); ?>
                <?php echo e(__('Repository')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalda376aa217444bbd92367ba1444eb3b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda376aa217444bbd92367ba1444eb3b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::navlist.item','data' => ['icon' => 'book-open-text','href' => 'https://laravel.com/docs/starter-kits#livewire','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::navlist.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'book-open-text','href' => 'https://laravel.com/docs/starter-kits#livewire','target' => '_blank']); ?>
                <?php echo e(__('Documentation')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $attributes = $__attributesOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__attributesOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda376aa217444bbd92367ba1444eb3b8)): ?>
<?php $component = $__componentOriginalda376aa217444bbd92367ba1444eb3b8; ?>
<?php unset($__componentOriginalda376aa217444bbd92367ba1444eb3b8); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4)): ?>
<?php $attributes = $__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4; ?>
<?php unset($__attributesOriginalacac6a48a34186ea0abd369a00e5e2d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalacac6a48a34186ea0abd369a00e5e2d4)): ?>
<?php $component = $__componentOriginalacac6a48a34186ea0abd369a00e5e2d4; ?>
<?php unset($__componentOriginalacac6a48a34186ea0abd369a00e5e2d4); ?>
<?php endif; ?> -->

            <!-- Desktop User Menu -->
            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['class' => 'hidden lg:block','position' => 'bottom','align' => 'start']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hidden lg:block','position' => 'bottom','align' => 'start']); ?>
                <?php if (isset($component)) { $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::profile','data' => ['name' => auth()->user()->name,'initials' => auth()->user()->initials(),'icon:trailing' => 'chevrons-up-down','dataTest' => 'sidebar-menu-button']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->name),'initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->initials()),'icon:trailing' => 'chevrons-up-down','data-test' => 'sidebar-menu-button']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $attributes = $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $component = $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => ['class' => 'w-[220px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-[220px]']); ?>
                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        <?php echo e(auth()->user()->initials()); ?>

                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                </div>
                            </div>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['href' => route('profile.edit'),'icon' => 'cog','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit')),'icon' => 'cog','wire:navigate' => true]); ?><?php echo e(__('Settings')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full','dataTest' => 'logout-button']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full','data-test' => 'logout-button']); ?>
                            <?php echo e(__('Log Out')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    </form>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $attributes = $__attributesOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__attributesOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal17e56bc23bb0192e474b351c4358d446)): ?>
<?php $component = $__componentOriginal17e56bc23bb0192e474b351c4358d446; ?>
<?php unset($__componentOriginal17e56bc23bb0192e474b351c4358d446); ?>
<?php endif; ?>

        <!-- Mobile User Menu -->
        <?php if (isset($component)) { $__componentOriginale96c14d638c792103c11b984a4ed1896 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale96c14d638c792103c11b984a4ed1896 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::header','data' => ['class' => 'lg:hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden']); ?>
            <?php if (isset($component)) { $__componentOriginal1b6467b07b302021134396bbd98e74a9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1b6467b07b302021134396bbd98e74a9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.toggle','data' => ['class' => 'lg:hidden','icon' => 'bars-2','inset' => 'left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'lg:hidden','icon' => 'bars-2','inset' => 'left']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $attributes = $__attributesOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__attributesOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1b6467b07b302021134396bbd98e74a9)): ?>
<?php $component = $__componentOriginal1b6467b07b302021134396bbd98e74a9; ?>
<?php unset($__componentOriginal1b6467b07b302021134396bbd98e74a9); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::spacer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::spacer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $attributes = $__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__attributesOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a)): ?>
<?php $component = $__componentOriginal4a4f7aa062a095c651c2f80bb685a42a; ?>
<?php unset($__componentOriginal4a4f7aa062a095c651c2f80bb685a42a); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::dropdown','data' => ['position' => 'top','align' => 'end']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['position' => 'top','align' => 'end']); ?>
                <?php if (isset($component)) { $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::profile','data' => ['initials' => auth()->user()->initials(),'iconTrailing' => 'chevron-down']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::profile'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['initials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(auth()->user()->initials()),'icon-trailing' => 'chevron-down']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $attributes = $__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__attributesOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994)): ?>
<?php $component = $__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994; ?>
<?php unset($__componentOriginal2e5cdd03843a4c4d68fb9a6d7bd7e994); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        <?php echo e(auth()->user()->initials()); ?>

                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                </div>
                            </div>
                        </div>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.radio.group','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.radio.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['href' => route('profile.edit'),'icon' => 'cog','wire:navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit')),'icon' => 'cog','wire:navigate' => true]); ?><?php echo e(__('Settings')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $attributes = $__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__attributesOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1)): ?>
<?php $component = $__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1; ?>
<?php unset($__componentOriginal48a7a6275c4dbe43f3b08c99bf9c2ce1); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginald5e1eb3ae521062f8474178ba08933ca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5e1eb3ae521062f8474178ba08933ca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.separator','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.separator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $attributes = $__attributesOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__attributesOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5e1eb3ae521062f8474178ba08933ca)): ?>
<?php $component = $__componentOriginald5e1eb3ae521062f8474178ba08933ca; ?>
<?php unset($__componentOriginald5e1eb3ae521062f8474178ba08933ca); ?>
<?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginal5027d420cfeeb03dd925cfc08ae44851 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::menu.item','data' => ['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full','dataTest' => 'logout-button']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::menu.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['as' => 'button','type' => 'submit','icon' => 'arrow-right-start-on-rectangle','class' => 'w-full','data-test' => 'logout-button']); ?>
                            <?php echo e(__('Log Out')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $attributes = $__attributesOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__attributesOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851)): ?>
<?php $component = $__componentOriginal5027d420cfeeb03dd925cfc08ae44851; ?>
<?php unset($__componentOriginal5027d420cfeeb03dd925cfc08ae44851); ?>
<?php endif; ?>
                    </form>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $attributes = $__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__attributesOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a)): ?>
<?php $component = $__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a; ?>
<?php unset($__componentOriginalf7749b857446d2788d0b6ca0c63f9d3a); ?>
<?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $attributes = $__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__attributesOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888)): ?>
<?php $component = $__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888; ?>
<?php unset($__componentOriginal2b4bb2cd4b8f1a3c08bae49ea918b888); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $attributes = $__attributesOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__attributesOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale96c14d638c792103c11b984a4ed1896)): ?>
<?php $component = $__componentOriginale96c14d638c792103c11b984a4ed1896; ?>
<?php unset($__componentOriginale96c14d638c792103c11b984a4ed1896); ?>
<?php endif; ?>

        <?php echo e($slot); ?>


        <script>
            window.appSidebar = window.appSidebar || {};

            if (!window.appSidebar.initializeAccordion) {
                window.appSidebar.initializeAccordion = () => {
                    document.querySelectorAll('[data-accordion-toggle]').forEach((toggle) => {
                        if (toggle.dataset.accordionInitialized === 'true') {
                            return;
                        }

                        toggle.dataset.accordionInitialized = 'true';

                        const targetId = toggle.getAttribute('data-accordion-target');
                        const target = document.getElementById(targetId);

                        if (!target) {
                            return;
                        }

                        const icon = toggle.querySelector('[data-accordion-icon]');
                        const isExpanded = toggle.getAttribute('aria-expanded') === 'true';

                        target.style.maxHeight = isExpanded ? `${target.scrollHeight}px` : '0px';

                        toggle.addEventListener('click', () => {
                            const currentlyExpanded = toggle.getAttribute('aria-expanded') === 'true';

                            if (currentlyExpanded) {
                                target.style.maxHeight = '0px';
                                toggle.setAttribute('aria-expanded', 'false');
                                icon?.classList.remove('rotate-90');
                            } else {
                                target.style.maxHeight = `${target.scrollHeight}px`;
                                toggle.setAttribute('aria-expanded', 'true');
                                icon?.classList.add('rotate-90');
                            }
                        });
                    });
                };

                document.addEventListener('DOMContentLoaded', window.appSidebar.initializeAccordion);
                document.addEventListener('livewire:navigated', window.appSidebar.initializeAccordion);
            }
        </script>

        <?php app('livewire')->forceAssetInjection(); ?>
<?php echo app('flux')->scripts(); ?>

        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH /var/www/geka/gd/resources/views/components/layouts/app/sidebar.blade.php ENDPATH**/ ?>