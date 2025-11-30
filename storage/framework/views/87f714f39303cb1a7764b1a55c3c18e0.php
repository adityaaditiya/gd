<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Pemberian Kredit Gadai')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pemberian Kredit Gadai'))]); ?>
    <div
        class="space-y-8"
        id="pemberian-gadai-page"
        data-initialized="false"
        data-today="<?php echo e(($today ?? now()->toDateString())); ?>"
    >
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Pemberian Kredit Gadai')); ?></h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                <?php echo e(__('pilih barang jaminan yang sudah diverifikasi, pilih nasabah dan input nominal pinjaman yang disetujui.')); ?>

            </p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <?php if($barangSiapGadai->isEmpty()): ?>
                <div class="p-6 text-sm text-neutral-600 dark:text-neutral-300">
                    <?php echo e(__('Belum ada barang dengan status siap gadai. Tambahkan data barang terlebih dahulu di menu Barang Gadai.')); ?>

                </div>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('gadai.transaksi-gadai.store')); ?>" class="space-y-8 p-6">
                    <?php echo csrf_field(); ?>

                    <section class="space-y-4">
                        <div class="flex items-center gap-3">
                            <!-- <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">1</span> -->
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Pilih Barang Jaminan Siap Gadai')); ?></h2>
                                <!-- <p class="text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Kasir memulai proses dengan memilih aset yang belum terikat kontrak.')); ?></p> -->
                            </div>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label for="barang_ids" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Barang Jaminan')); ?></label>
                                    <label for="barang_search" class="sr-only"><?php echo e(__('Cari Barang Jaminan')); ?></label>
                                    <input
                                        type="search"
                                        id="barang_search"
                                        placeholder="<?php echo e(__('Cari barang…')); ?>"
                                        class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                    />
                                </div>
                                <?php
                                    $barangDipilih = collect(old('barang_ids', []))->map(fn ($id) => (string) $id)->all();
                                ?>
                                <select
                                    id="barang_ids"
                                    name="barang_ids[]"
                                    required
                                    multiple
                                    size="6"
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <?php $__currentLoopData = $barangSiapGadai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($barang->barang_id); ?>"
                                            data-nilai="<?php echo e($barang->nilai_taksiran); ?>"
                                            data-deskripsi="<?php echo e($barang->jenis_barang); ?> — <?php echo e($barang->merek); ?>"
                                            data-search="<?php echo e(strtolower($barang->jenis_barang . ' ' . $barang->merek . ' ' . ($barang->kode_barang ?? ''))); ?>"
                                            <?php echo e(in_array((string) $barang->barang_id, $barangDipilih, true) ? 'selected' : ''); ?>

                                        >
                                            <?php echo e($barang->jenis_barang); ?> — <?php echo e($barang->merek); ?> (<?php echo e(__('Taksiran: :amount', ['amount' => number_format((float) $barang->nilai_taksiran, 0, ',', '.')])); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Gunakan kursor untuk memblok langsung atau Ctrl/Cmd + klik untuk memilih lebih dari satu barang.')); ?></p>
                                <?php $__errorArgs = ['barang_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <?php if($errors->has('barang_ids.*')): ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($errors->first('barang_ids.*')); ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="rounded-lg border border-dashed border-emerald-300 bg-emerald-50/70 p-4 text-sm text-emerald-900 dark:border-emerald-500 dark:bg-emerald-500/10 dark:text-emerald-200">
                                <p class="font-semibold"><?php echo e(__('Ringkasan Barang Terpilih')); ?></p>
                                <dl class="mt-2 space-y-2 text-xs">
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300"><?php echo e(__('Jumlah Barang')); ?></dt>
                                        <dd id="ringkasan-jumlah" class="font-semibold text-neutral-900 dark:text-white">0</dd>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300"><?php echo e(__('Total Nilai Taksiran')); ?></dt>
                                        <dd id="ringkasan-total-nilai" class="font-semibold text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                    <div class="flex justify-between gap-2">
                                        <dt class="text-neutral-600 dark:text-neutral-300"><?php echo e(__('Plafon Maksimal (80%)')); ?></dt>
                                        <dd id="ringkasan-plafon" class="font-semibold text-neutral-900 dark:text-white">—</dd>
                                    </div>
                                </dl>
                                <div class="mt-3 rounded-lg bg-white/60 p-3 text-xs text-neutral-700 shadow-sm dark:bg-neutral-900/40 dark:text-neutral-200">
                                    <p class="font-semibold"><?php echo e(__('Daftar Barang')); ?></p>
                                    <ul id="ringkasan-daftar" class="mt-2 space-y-1">
                                        <li class="italic text-neutral-500 dark:text-neutral-400"><?php echo e(__('Belum ada barang dipilih.')); ?></li>
                                    </ul>
                                </div>
                                <p class="mt-3 text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Total nilai taksiran digunakan sebagai acuan batas plafon pinjaman 80%.')); ?></p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <!-- <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">2</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Input Detail Kontrak')); ?></h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Lengkapi informasi kontrak dan pastikan plafon pinjaman tidak melebihi 80% dari nilai taksiran.')); ?></p>
                            </div>
                        </div> -->
                        <?php
                            $typeOptions = $masterPerhitunganGadai->pluck('type')->unique()->values();
                        ?>
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label for="no_sbg" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nomor SBG')); ?></label>
                                <input
                                    type="text"
                                    id="no_sbg"
                                    name="no_sbg"
                                    value="<?php echo e(old('no_sbg', $defaultNoSbg)); ?>"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm font-semibold text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nomor SBG dibuat otomatis dan mengikuti format GE02 + tanggal (YYMMDD) + urutan harian tiga digit.')); ?></p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between gap-2">
                                    <label for="nasabah_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nasabah')); ?></label>
                                    <label for="nasabah_search" class="sr-only"><?php echo e(__('Cari Nasabah')); ?></label>
                                    <input
                                        type="search"
                                        id="nasabah_search"
                                        placeholder="<?php echo e(__('Cari nasabah…')); ?>"
                                        class="block w-48 rounded-lg border border-neutral-300 bg-white px-2 py-1 text-xs text-neutral-700 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                    />
                                </div>
                                <select
                                    id="nasabah_id"
                                    name="nasabah_id"
                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <option value="" disabled <?php echo e(old('nasabah_id') ? '' : 'selected'); ?> data-placeholder="true"><?php echo e(__('Pilih nasabah')); ?></option>
                                    <?php $__currentLoopData = $nasabahList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nasabah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($nasabah->id); ?>"
                                            data-search="<?php echo e(strtolower($nasabah->nama . ' ' . $nasabah->kode_member . ' ' . ($nasabah->kelurahan ?? '') . ' ' . $nasabah->alamat)); ?>"
                                            <?php echo e((string) old('nasabah_id') === (string) $nasabah->id ? 'selected' : ''); ?>

                                        >
                                            <?php echo e($nasabah->nama); ?> — <?php echo e($nasabah->kode_member); ?> (<?php echo e($nasabah->kelurahan ?? '-'); ?>) — <?php echo e($nasabah->alamat); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['nasabah_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="type" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Type Kredit')); ?></label>
                                <select
                                    id="type"
                                    name="type"
                                    <?php echo e($typeOptions->isEmpty() ? 'disabled' : ''); ?>

                                    required
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 disabled:cursor-not-allowed disabled:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                >
                                    <option value="" disabled <?php echo e(old('type') ? '' : 'selected'); ?> data-placeholder="true"><?php echo e(__('Pilih type kredit')); ?></option>
                                    <?php $__currentLoopData = $typeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($option); ?>" <?php echo e(old('type') === $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php if($typeOptions->isEmpty()): ?>
                                    <p class="text-xs text-amber-600 dark:text-amber-400"><?php echo e(__('Belum ada konfigurasi Master Perhitungan Gadai. Tambahkan data terlebih dahulu untuk mengaktifkan form ini.')); ?></p>
                                <?php else: ?>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tarif bunga, biaya admin, dan jatuh tempo mengikuti type yang dipilih.')); ?></p>
                                <?php endif; ?>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="tanggal_gadai" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tanggal Gadai')); ?></label>
                                <input
                                    type="date"
                                    id="tanggal_gadai"
                                    name="tanggal_gadai"
                                    value="<?php echo e(old('tanggal_gadai', $today ?? now()->toDateString())); ?>"
                                    required
                                    readonly
                                    min="<?php echo e($today ?? now()->toDateString()); ?>"
                                    max="<?php echo e($today ?? now()->toDateString()); ?>"
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <?php $__errorArgs = ['tanggal_gadai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="jatuh_tempo_awal" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Jatuh Tempo Awal')); ?></label>
                                <input
                                    type="date"
                                    id="jatuh_tempo_awal"
                                    name="jatuh_tempo_awal"
                                    value="<?php echo e(old('jatuh_tempo_awal')); ?>"
                                    required
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <?php $__errorArgs = ['jatuh_tempo_awal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tanggal jatuh tempo dihitung otomatis dari hari master yang terdaftar pada type yang dipilih.')); ?></p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('* Otomatis akan terisi setelah mengisi kolom Nominal Pinjaman.')); ?></p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="tenor_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tenor (Hari)')); ?></label>
                                <input
                                    type="text"
                                    id="tenor_display"
                                    value="—"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Tenor dihitung otomatis dari tanggal gadai dan jatuh tempo yang bersumber dari data Master Perhitungan Gadai.')); ?></p>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('* Otomatis akan terisi setelah mengisi kolom Nominal Pinjaman.')); ?></p>

                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="uang_pinjaman" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nominal Pinjaman')); ?></label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="uang_pinjaman"
                                    name="uang_pinjaman"
                                    value="<?php echo e(old('uang_pinjaman')); ?>"
                                    required
                                    data-currency-input
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                <?php $__errorArgs = ['uang_pinjaman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Masukkan nominal pinjaman untuk menentukan range data master yang aktif.')); ?></p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="skema_bunga_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Skema Bunga')); ?></label>
                                <input
                                    type="text"
                                    id="skema_bunga_display"
                                    value="<?php echo e(old('skema_bunga_display', '—')); ?>"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="tarif_bunga_harian_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tarif Efektif per Hari (%)')); ?></label>
                                <?php
                                    $oldTarifDecimal = old('tarif_bunga_harian');
                                    $oldTarifDisplay = is_numeric($oldTarifDecimal)
                                        ? rtrim(rtrim(number_format((float) $oldTarifDecimal * 100, 4, ',', '.'), '0'), ',') . '%'
                                        : '—';
                                ?>
                                <input
                                    type="text"
                                    id="tarif_bunga_harian_display"
                                    value="<?php echo e($oldTarifDisplay); ?>"
                                    placeholder="0,000%"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <input type="hidden" id="tarif_bunga_harian_value" value="<?php echo e(old('tarif_bunga_harian')); ?>">
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="tarif_bunga_per_periode_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Tarif Bunga per Periode (%)')); ?></label>
                                <input
                                    type="text"
                                    id="tarif_bunga_per_periode_display"
                                    value="<?php echo e(__('—')); ?>"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="periode_hari_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Periode Bunga (Hari)')); ?></label>
                                <input
                                    type="text"
                                    id="periode_hari_display"
                                    value="<?php echo e(__('—')); ?>"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p
                                    class="text-xs text-neutral-500 dark:text-neutral-400"
                                    data-formula-helper
                                    data-default-message="<?php echo e(__('Tarif bunga, biaya admin, dan jatuh tempo mengikuti Master Perhitungan Gadai.')); ?>"
                                    data-type-message="<?php echo e(__('Pilih type kredit terlebih dahulu untuk memuat tarif master.')); ?>"
                                    data-amount-message="<?php echo e(__('Masukkan nominal pinjaman untuk mencari range data master yang tepat.')); ?>"
                                    data-not-found-message="<?php echo e(__('Tidak ada konfigurasi master yang cocok untuk nominal :amount.')); ?>"
                                    data-empty-message="<?php echo e(__('Belum ada data Master Perhitungan Gadai. Silakan tambahkan di menu Master terlebih dahulu.')); ?>"
                                >
                                    <?php echo e(__('Tarif bunga, biaya admin, dan jatuh tempo mengikuti Master Perhitungan Gadai.')); ?>

                                </p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="biaya_admin" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Biaya Administrasi')); ?></label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="biaya_admin"
                                    name="biaya_admin"
                                    value="<?php echo e(old('biaya_admin')); ?>"
                                    data-currency-input
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <?php $__errorArgs = ['biaya_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Biaya admin mengikuti konfigurasi Master Perhitungan Gadai dan tidak dapat diubah manual.')); ?></p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="premi" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Premi / Asuransi')); ?></label>
                                <input
                                    type="text"
                                    inputmode="decimal"
                                    id="premi"
                                    name="premi"
                                    value="<?php echo e(old('premi')); ?>"
                                    data-currency-input
                                    class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
                                />
                                <?php $__errorArgs = ['premi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="total_potongan_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Total Potongan (Admin + Premi)')); ?></label>
                                <input
                                    type="text"
                                    id="total_potongan_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="uang_cair_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Uang Cair (Diterima Nasabah)')); ?></label>
                                <input
                                    type="text"
                                    id="uang_cair_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <!-- <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nilai ini otomatis muncul di nota kontrak sebagai dana bersih yang diterima nasabah.')); ?></p> -->
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="estimasi_bunga_display" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Estimasi Bunga (Tarif × Tenor)')); ?></label>
                                <input
                                    type="text"
                                    id="estimasi_bunga_display"
                                    value="Rp 0"
                                    readonly
                                    class="block w-full rounded-lg border border-neutral-300 bg-neutral-100 px-3 py-2 text-sm text-neutral-900 shadow-sm focus:border-neutral-300 focus:outline-none focus:ring-0 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                />
                                <p class="text-xs text-neutral-500 dark:text-neutral-400"><?php echo e(__('Nilai bunga mengikuti tarif bunga dari data master perhitungan gadai dikalikan nominal pinjaman dan tenor aktual.')); ?></p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-4">
                        <!-- <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-600 text-sm font-semibold text-white">3</span>
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Terbitkan Kontrak & Kunci Barang')); ?></h2>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300"><?php echo e(__('Setelah disimpan, sistem akan membuat kontrak berstatus Aktif dan mengunci barang agar tidak dapat digunakan ulang.')); ?></p>
                            </div>
                        </div> -->

                        <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-900/50 dark:black">
                            <ul class="list-disc space-y-1 pl-5">
                                <li><?php echo e(__('Nominal pinjaman otomatis divalidasi agar tidak melampaui plafon 80% dari nilai taksiran.')); ?></li>
                                <li><?php echo e(__('Barang jaminan yang dipilih tidak lagi tampil pada daftar siap gadai setelah SBG terbit.')); ?></li>
                                <li><?php echo e(__('Halaman ini tetap terbuka setelah simpan dan sistem akan membuka jendela cetak nota secara otomatis.')); ?></li>
                            </ul>
                        </div>
                    </section>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="<?php echo e(route('gadai.lihat-gadai')); ?>"
                            class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-400 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                        >
                            <?php echo e(__('Batal')); ?>

                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-500 dark:bg-emerald-500 dark:hover:border-emerald-400 dark:hover:bg-emerald-400"
                        >
                            <?php echo e(__('Simpan Transaksi')); ?>

                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php if(! $barangSiapGadai->isEmpty()): ?>
        <script>
            // Namespace global aman (tanpa redeclare)
            window.KRESNO = window.KRESNO || {};
            window.KRESNO.gadaiPage ??= {
                init() {
                    const root = document.getElementById('pemberian-gadai-page');
                    if (!root || root.dataset.initialized === 'true') return;
                    root.dataset.initialized = 'true';

                    const select = document.getElementById('barang_ids');
                    const ringkasanJumlah = document.getElementById('ringkasan-jumlah');
                    const ringkasanTotal = document.getElementById('ringkasan-total-nilai');
                    const ringkasanPlafon = document.getElementById('ringkasan-plafon');
                    const ringkasanDaftar = document.getElementById('ringkasan-daftar');
                    const tanggalGadaiInput = document.getElementById('tanggal_gadai');
                    const jatuhTempoInput = document.getElementById('jatuh_tempo_awal');
                    const pinjamanInput = document.getElementById('uang_pinjaman');
                    const biayaAdminInput = document.getElementById('biaya_admin');
                    const premiInput = document.getElementById('premi');
                    const tenorDisplay = document.getElementById('tenor_display');
                    const bungaDisplay = document.getElementById('estimasi_bunga_display');
                    const totalPotonganDisplay = document.getElementById('total_potongan_display');
                    const uangCairDisplay = document.getElementById('uang_cair_display');
                    const barangSearchInput = document.getElementById('barang_search');
                    const nasabahSelect = document.getElementById('nasabah_id');
                    const nasabahSearchInput = document.getElementById('nasabah_search');
                    const typeSelect = document.getElementById('type');
                    const tarifBungaHiddenInput = document.getElementById('tarif_bunga_harian_value');
                    const tarifBungaDisplayInput = document.getElementById('tarif_bunga_harian_display');
                    const tarifBungaPerPeriodeDisplay = document.getElementById('tarif_bunga_per_periode_display');
                    const periodeHariDisplay = document.getElementById('periode_hari_display');
                    const skemaBungaDisplay = document.getElementById('skema_bunga_display');
                    const formulaHelper = root.querySelector('[data-formula-helper]');

                    if (!select) return;

                    const formatCurrency = (value) => {
                        if (value === null || value === undefined || value === '') return '—';
                        const number = Number.parseFloat(value);
                        if (Number.isNaN(number)) return '—';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(number);
                    };

                    const parseDecimal = (rawValue) => {
                        if (rawValue === null || rawValue === undefined) return 0;
                        if (typeof rawValue !== 'string') {
                            const numeric = Number(rawValue);
                            return Number.isNaN(numeric) ? 0 : numeric;
                        }

                        let value = rawValue.trim();
                        if (value === '') return 0;

                        value = value.replace(/[^0-9,.-]/g, '');
                        const lastComma = value.lastIndexOf(',');
                        const lastDot = value.lastIndexOf('.');

                        if (lastComma !== -1 && lastDot !== -1) {
                            if (lastComma > lastDot) {
                                value = value.replace(/\./g, '');
                                value = value.replace(/,/g, '.');
                            } else {
                                value = value.replace(/,/g, '');
                            }
                        } else if (lastComma !== -1) {
                            value = value.replace(/\./g, '');
                            value = value.replace(/,/g, '.');
                        } else {
                            value = value.replace(/,/g, '');
                        }

                        const parsed = Number.parseFloat(value);
                        return Number.isNaN(parsed) ? 0 : parsed;
                    };

                    const formatPercent = (value) => {
                        if (!Number.isFinite(value)) return '—';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'percent',
                            minimumFractionDigits: 3,
                            maximumFractionDigits: 4,
                        }).format(value);
                    };

                    const applySchemeDisplays = ({ schemeLabel, effectiveRate, perPeriodRate, periodeDays }) => {
                        if (skemaBungaDisplay) {
                            skemaBungaDisplay.value = schemeLabel ?? '—';
                        }

                        if (tarifBungaHiddenInput) {
                            tarifBungaHiddenInput.value = Number.isFinite(effectiveRate) ? effectiveRate.toString() : '';
                        }

                        if (tarifBungaDisplayInput) {
                            tarifBungaDisplayInput.value = Number.isFinite(perPeriodRate)
                                ? '-'
                                : Number.isFinite(effectiveRate)
                                    ? formatPercent(effectiveRate)
                                    : '—';
                        }

                        if (tarifBungaPerPeriodeDisplay) {
                            tarifBungaPerPeriodeDisplay.value = Number.isFinite(perPeriodRate)
                                ? formatPercent(perPeriodRate)
                                : '—';
                        }

                        if (periodeHariDisplay) {
                            periodeHariDisplay.value = Number.isFinite(periodeDays) && periodeDays > 0
                                ? `${periodeDays} hari`
                                : '—';
                        }
                    };

                    const MILLISECONDS_PER_DAY = 24 * 60 * 60 * 1000;

                    const isInvalidDate = (date) => !(date instanceof Date) || Number.isNaN(date.getTime());

                    const calculateActualDays = (startDate, endDate) => {
                        if (isInvalidDate(startDate) || isInvalidDate(endDate)) {
                            return 0;
                        }

                        const startUtc = Date.UTC(
                            startDate.getFullYear(),
                            startDate.getMonth(),
                            startDate.getDate(),
                        );
                        const endUtc = Date.UTC(
                            endDate.getFullYear(),
                            endDate.getMonth(),
                            endDate.getDate(),
                        );

                        if (endUtc < startUtc) {
                            return 0;
                        }

                        const diffDays = Math.floor((endUtc - startUtc) / MILLISECONDS_PER_DAY);
                        return Math.max(1, diffDays + 1);
                    };

                    const masterFormulas = <?php echo json_encode($masterPerhitunganGadaiForJs ?? [], 15, 512) ?>;
                    const todayString = root.dataset.today ?? '';
                    const defaultFormulaMessage = formulaHelper?.dataset.defaultMessage ?? '';
                    const typeMessage = formulaHelper?.dataset.typeMessage ?? defaultFormulaMessage;
                    const amountMessage = formulaHelper?.dataset.amountMessage ?? defaultFormulaMessage;
                    const notFoundTemplate = formulaHelper?.dataset.notFoundMessage ?? defaultFormulaMessage;
                    const emptyMessage = formulaHelper?.dataset.emptyMessage ?? defaultFormulaMessage;

                    const setFormulaHelper = (message, state = 'muted') => {
                        if (!formulaHelper) return;
                        formulaHelper.textContent = message;
                        formulaHelper.classList.remove(
                            'text-neutral-500',
                            'dark:text-neutral-400',
                            'text-emerald-600',
                            'dark:text-emerald-300',
                            'text-rose-600',
                            'dark:text-rose-400'
                        );

                        if (state === 'success') {
                            formulaHelper.classList.add('text-emerald-600', 'dark:text-emerald-300');
                        } else if (state === 'error') {
                            formulaHelper.classList.add('text-rose-600', 'dark:text-rose-400');
                        } else {
                            formulaHelper.classList.add('text-neutral-500', 'dark:text-neutral-400');
                        }
                    };

                    const toInputDate = (date) => {
                        if (isInvalidDate(date)) return '';
                        const year = date.getFullYear();
                        const month = `${date.getMonth() + 1}`.padStart(2, '0');
                        const day = `${date.getDate()}`.padStart(2, '0');
                        return `${year}-${month}-${day}`;
                    };

                    const applyCurrencyValue = (input, amount) => {
                        if (!input) return;
                        if (amount === null || amount === undefined || Number.isNaN(amount)) {
                            input.value = '';
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            return;
                        }

                        input.value = amount.toString();
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                    };

                    const updateFormulaFields = () => {
                        if (!typeSelect || !pinjamanInput) {
                            return null;
                        }

                        if (!masterFormulas.length) {
                            setFormulaHelper(emptyMessage, 'error');
                            applyCurrencyValue(biayaAdminInput, null);
                            if (tarifBungaHiddenInput) {
                                tarifBungaHiddenInput.value = '';
                            }
                            if (tarifBungaDisplayInput) {
                                tarifBungaDisplayInput.value = '—';
                            }
                            return null;
                        }

                        const selectedType = typeSelect.value?.trim();
                        const nominal = parseDecimal(pinjamanInput.value ?? '');
                        const tanggalGadaiValue = tanggalGadaiInput?.value ?? todayString ?? '';

                        if (!selectedType) {
                            setFormulaHelper(typeMessage, 'muted');
                            applyCurrencyValue(biayaAdminInput, null);
                            if (tarifBungaHiddenInput) {
                                tarifBungaHiddenInput.value = '';
                            }
                            if (tarifBungaDisplayInput) {
                                tarifBungaDisplayInput.value = '—';
                            }
                            if (jatuhTempoInput && tanggalGadaiValue) {
                                jatuhTempoInput.value = tanggalGadaiValue;
                            }
                            return null;
                        }

                        if (nominal <= 0) {
                            setFormulaHelper(amountMessage, 'muted');
                            applyCurrencyValue(biayaAdminInput, null);
                            if (tarifBungaHiddenInput) {
                                tarifBungaHiddenInput.value = '';
                            }
                            if (tarifBungaDisplayInput) {
                                tarifBungaDisplayInput.value = '—';
                            }
                            if (jatuhTempoInput && tanggalGadaiValue) {
                                jatuhTempoInput.value = tanggalGadaiValue;
                            }
                            return null;
                        }

                        const formula = masterFormulas.find((candidate) => {
                            if (!candidate || candidate.type !== selectedType) {
                                return false;
                            }

                            const minimum = Number(candidate.range_awal ?? 0);
                            const maximum = Number(candidate.range_akhir ?? 0);
                            return nominal >= minimum && nominal <= maximum;
                        });

                        if (!formula) {
                            const message = notFoundTemplate.replace(':amount', formatCurrency(nominal));
                            setFormulaHelper(message, 'error');
                            applyCurrencyValue(biayaAdminInput, null);
                            applySchemeDisplays({
                                schemeLabel: '—',
                                effectiveRate: Number.NaN,
                                perPeriodRate: Number.NaN,
                                periodeDays: Number.NaN,
                            });
                            if (jatuhTempoInput && tanggalGadaiValue) {
                                jatuhTempoInput.value = tanggalGadaiValue;
                            }
                            return null;
                        }

                        const scheme = String(formula.skema_bunga ?? 'harian').toLowerCase();
                        const periode = Number(formula.periode_hari ?? 0);
                        const ratePerPeriod = Number(formula.tarif_bunga_per_periode ?? 0);
                        const isPeriodik = scheme === 'periodik';
                        const ratePerDay = isPeriodik && periode > 0
                            ? ratePerPeriod / periode
                            : Number(formula.tarif_bunga_harian ?? 0);

                        applySchemeDisplays({
                            schemeLabel: isPeriodik ? `Periodik${periode > 0 ? ` (${periode} hari)` : ''}` : 'Harian',
                            effectiveRate: ratePerDay,
                            perPeriodRate: isPeriodik ? ratePerPeriod : Number.NaN,
                            periodeDays: isPeriodik ? periode : Number.NaN,
                        });

                        applyCurrencyValue(biayaAdminInput, Number(formula.biaya_admin ?? 0));

                        if (jatuhTempoInput) {
                            const baseDateSource = tanggalGadaiValue || todayString;
                            let baseDate = baseDateSource ? new Date(baseDateSource) : new Date();

                            if (isInvalidDate(baseDate)) {
                                baseDate = new Date();
                            }

                            const offsetDays = Number.parseInt(formula.jatuh_tempo_awal ?? 0, 10);
                            if (!Number.isNaN(offsetDays)) {
                                const dueDate = new Date(baseDate);
                                dueDate.setDate(dueDate.getDate() + offsetDays);
                                jatuhTempoInput.value = toInputDate(dueDate);
                            }
                        }

                        setFormulaHelper(
                            `${formula.type} • ${formatCurrency(formula.range_awal ?? 0)} - ${formatCurrency(formula.range_akhir ?? 0)}`,
                            'success'
                        );

                        return formula;
                    };

                    let totalNilaiTerpilih = 0;
                    const emptyListMessage = <?php echo json_encode(__('Belum ada barang dipilih.'), 15, 512) ?>;

                    const filterSelectOptions = (inputEl, selectEl) => {
                        if (!inputEl || !selectEl) return;

                        const term = inputEl.value.trim().toLowerCase();
                        Array.from(selectEl.options).forEach((option) => {
                            if (!option) return;

                            if (!option.value && option.dataset.placeholder === 'true') {
                                option.hidden = false;
                                return;
                            }

                            const searchable = (option.dataset.search ?? option.textContent ?? '').toLowerCase();
                            option.hidden = term !== '' && !searchable.includes(term);
                        });
                    };

                    const updateSummary = () => {
                        const options = Array.from(select?.selectedOptions ?? []).filter((option) => option.value);
                        totalNilaiTerpilih = 0;

                        if (!ringkasanJumlah || !ringkasanTotal || !ringkasanPlafon || !ringkasanDaftar) {
                            return;
                        }

                        ringkasanJumlah.textContent = options.length.toString();

                        if (options.length === 0) {
                            ringkasanTotal.textContent = '—';
                            ringkasanPlafon.textContent = '—';
                            ringkasanDaftar.innerHTML = `<li class="italic text-neutral-500 dark:text-neutral-400">${emptyListMessage}</li>`;
                            select?.setAttribute('data-total-nilai', '0');
                            return;
                        }

                        ringkasanDaftar.innerHTML = '';

                        options.forEach((option) => {
                            const nilai = parseDecimal(option.dataset.nilai ?? '0');
                            totalNilaiTerpilih += nilai;

                            const item = document.createElement('li');
                            item.className = 'rounded-md bg-emerald-100/70 px-3 py-2 text-neutral-700 dark:bg-emerald-500/10 dark:text-neutral-100';
                            item.innerHTML = `<span class="font-semibold text-neutral-900 dark:text-white">${option.dataset.deskripsi ?? option.textContent ?? ''}</span><div>${formatCurrency(nilai)}</div>`;
                            ringkasanDaftar.appendChild(item);
                        });

                        ringkasanTotal.textContent = formatCurrency(totalNilaiTerpilih);
                        ringkasanPlafon.textContent = formatCurrency(totalNilaiTerpilih * 0.8);
                        select?.setAttribute('data-total-nilai', totalNilaiTerpilih.toString());
                    };

                    const updateBunga = () => {
                        if (!tenorDisplay || !bungaDisplay) return;

                        const tanggalGadai = tanggalGadaiInput?.value ? new Date(tanggalGadaiInput.value) : null;
                        const jatuhTempo = jatuhTempoInput?.value ? new Date(jatuhTempoInput.value) : null;
                        const ratePerDayRaw = tarifBungaHiddenInput ? parseDecimal(tarifBungaHiddenInput.value ?? '') : 0;
                        const ratePerDay = ratePerDayRaw > 0 ? ratePerDayRaw : 0;
                        const tenor = calculateActualDays(tanggalGadai, jatuhTempo);

                        tenorDisplay.value = tenor > 0 ? `${tenor} hari` : '—';

                        const pinjaman = parseDecimal(pinjamanInput?.value ?? '');
                        const adminCost = parseDecimal(biayaAdminInput?.value ?? '');
                        const premiCost = parseDecimal(premiInput?.value ?? '');
                        const totalPotongan = Math.max(0, adminCost + premiCost);
                        const uangCair = Math.max(0, pinjaman - totalPotongan);
                        const bunga = tenor > 0 && pinjaman > 0 ? pinjaman * ratePerDay * tenor : 0;

                        bungaDisplay.value = tenor > 0 && pinjaman > 0 ? formatCurrency(bunga) : formatCurrency(0);
                        if (totalPotonganDisplay) {
                            totalPotonganDisplay.value = formatCurrency(totalPotongan);
                        }
                        if (uangCairDisplay) {
                            uangCairDisplay.value = formatCurrency(uangCair);
                        }
                    };

                    select.addEventListener('change', () => {
                        updateSummary();
                        updateBunga();
                    });
                    barangSearchInput?.addEventListener('input', () => {
                        filterSelectOptions(barangSearchInput, select);
                    });
                    nasabahSearchInput?.addEventListener('input', () => {
                        filterSelectOptions(nasabahSearchInput, nasabahSelect);
                    });
                    tanggalGadaiInput?.addEventListener('change', () => {
                        updateFormulaFields();
                        updateBunga();
                    });
                    jatuhTempoInput?.addEventListener('change', updateBunga);
                    typeSelect?.addEventListener('change', () => {
                        updateFormulaFields();
                        updateBunga();
                    });
                    pinjamanInput?.addEventListener('input', () => {
                        updateFormulaFields();
                        updateBunga();
                    });
                    biayaAdminInput?.addEventListener('input', updateBunga);
                    premiInput?.addEventListener('input', updateBunga);

                    updateSummary();
                    updateFormulaFields();
                    updateBunga();
                    filterSelectOptions(barangSearchInput, select);
                    filterSelectOptions(nasabahSearchInput, nasabahSelect);
                }
            };

            // Boot pertama + saat Livewire navigasi
            (function bootGadaiPage() {
                const run = () => window.KRESNO.gadaiPage.init?.();
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', run, { once: true });
                } else {
                    run();
                }
                document.addEventListener('livewire:navigated', () => {
                    // reset guard agar halaman ini bisa re-init saat kembali dari halaman lain
                    const root = document.getElementById('pemberian-gadai-page');
                    if (root) root.dataset.initialized = 'false';
                    run();
                });
            })();
        </script>
    <?php endif; ?>

    <?php if(session('print_preview_url')): ?>
        <script>
            (() => {
                const printUrl = <?php echo json_encode(session('print_preview_url'), 15, 512) ?>;
                if (!printUrl) return;

                const openPrintWindow = () => {
                    try {
                        window.open(printUrl, '_blank');
                    } catch (error) {
                        // noop
                    }
                };

                if (document.readyState === 'complete') {
                    openPrintWindow();
                } else {
                    window.addEventListener('load', openPrintWindow, { once: true });
                }
            })();
        </script>
    <?php endif; ?>
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
<?php /**PATH /var/www/gd/resources/views/gadai/pemberian-kredit.blade.php ENDPATH**/ ?>