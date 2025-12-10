<?php
    $barang = $barangJaminan ?? null;
    $selectedPenaksir = old('pegawai_penaksir_id', $barang?->pegawai_penaksir_id);
    $resolvePhotoUrl = static function (?string $path): ?string {
        if (!$path) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::url($path);
    };
?>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="flex flex-col gap-2">
        <label for="pegawai_penaksir_id" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Petugas Penaksir')); ?></label>
        <select
            id="pegawai_penaksir_id"
            name="pegawai_penaksir_id"
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        >
            <option value=""><?php echo e(__('Pilih penaksir (opsional)')); ?></option>
            <?php $__currentLoopData = $penaksirList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penaksir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($penaksir->id); ?>" <?php echo e((string) $selectedPenaksir === (string) $penaksir->id ? 'selected' : ''); ?>><?php echo e($penaksir->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['pegawai_penaksir_id'];
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
        <label for="jenis_barang" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Jenis Barang')); ?></label>
        <input
            type="text"
            id="jenis_barang"
            name="jenis_barang"
            value="<?php echo e(old('jenis_barang', $barang?->jenis_barang)); ?>"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        <?php $__errorArgs = ['jenis_barang'];
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
        <label for="merek" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Merek')); ?></label>
        <input
            type="text"
            id="merek"
            name="merek"
            value="<?php echo e(old('merek', $barang?->merek)); ?>"
            required
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        <?php $__errorArgs = ['merek'];
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
        <label for="usia_barang_thn" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Usia Barang (Tahun)')); ?></label>
        <input
            type="number"
            id="usia_barang_thn"
            name="usia_barang_thn"
            value="<?php echo e(old('usia_barang_thn', $barang?->usia_barang_thn)); ?>"
            min="0"
            max="120"
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        <?php $__errorArgs = ['usia_barang_thn'];
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
        <label for="hps" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Harga Pasar Setempat (HPS)')); ?></label>
        <input
            type="text"
            inputmode="decimal"
            id="hps"
            name="hps"
            value="<?php echo e(old('hps', $barang ? number_format((float) $barang->hps, 2, ',', '.') : '')); ?>"
            required
            data-currency-input
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        <?php $__errorArgs = ['hps'];
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
        <label for="nilai_taksiran" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nilai Taksiran')); ?></label>
        <input
            type="text"
            inputmode="decimal"
            id="nilai_taksiran"
            name="nilai_taksiran"
            value="<?php echo e(old('nilai_taksiran', $barang ? number_format((float) $barang->nilai_taksiran, 2, ',', '.') : '')); ?>"
            required
            data-currency-input
            class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
        />
        <?php $__errorArgs = ['nilai_taksiran'];
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
</div>

<div class="flex flex-col gap-2">
    <label for="kondisi_fisik" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kondisi Fisik')); ?></label>
    <textarea
        id="kondisi_fisik"
        name="kondisi_fisik"
        rows="4"
        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
    ><?php echo e(old('kondisi_fisik', $barang?->kondisi_fisik)); ?></textarea>
    <?php $__errorArgs = ['kondisi_fisik'];
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
    <label for="kelengkapan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Kelengkapan')); ?></label>
    <textarea
        id="kelengkapan"
        name="kelengkapan"
        rows="4"
        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
    ><?php echo e(old('kelengkapan', $barang?->kelengkapan)); ?></textarea>
    <?php $__errorArgs = ['kelengkapan'];
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
    <label for="keterangan" class="text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Keterangan')); ?></label>
    <textarea
        id="keterangan"
        name="keterangan"
        rows="3"
        class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
    ><?php echo e(old('keterangan', $barang?->keterangan)); ?></textarea>
    <?php $__errorArgs = ['keterangan'];
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

<div class="grid gap-6 lg:grid-cols-2">
    <?php $__currentLoopData = range(1, 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $currentPhoto = $barang?->{'foto_' . $index};
            $currentPhotoUrl = $resolvePhotoUrl($currentPhoto);
        ?>
        <div class="flex flex-col gap-2">
            <label for="foto_<?php echo e($index); ?>" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">
                <?php echo e(__('Foto :number', ['number' => $index])); ?>

            </label>
            <input
                type="file"
                id="foto_<?php echo e($index); ?>"
                name="foto_<?php echo e($index); ?>"
                accept="image/*"
                class="block w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-900/40"
            />
            <?php $__errorArgs = ['foto_' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php if($currentPhotoUrl): ?>
                <p class="text-xs text-neutral-500 dark:text-neutral-300">
                    <?php echo e(__('Foto saat ini:')); ?>

                    <a
                        href="<?php echo e($currentPhotoUrl); ?>"
                        target="_blank"
                        class="font-semibold text-emerald-600 hover:underline dark:text-emerald-300"
                    >
                        <?php echo e(__('Lihat')); ?>

                    </a>
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /var/www/gd/resources/views/gadai/barang-jaminan/form-fields.blade.php ENDPATH**/ ?>