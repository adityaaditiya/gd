<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => __('Master User')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Master User'))]); ?>
    <div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-neutral-500"><?php echo e(__('Master')); ?></p>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white"><?php echo e(__('Master User')); ?></h1>
            </div>
        </div>

        <?php if(session('status')): ?>
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-100">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php
            $roleOptions = [
                \App\Models\User::ROLE_ADMIN => __('Admin'),
                \App\Models\User::ROLE_OWNER => __('Owner'),
                \App\Models\User::ROLE_STAFF => __('Staff'),
                \App\Models\User::ROLE_KASIR => __('Kasir'),
                \App\Models\User::ROLE_PENAKSIR => __('Penaksir'),
                \App\Models\User::ROLE_USER => __('User'),
            ];
        ?>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,360px)_1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Tambah User Baru')); ?></h2>
                <p class="mt-1 text-sm text-neutral-500"><?php echo e(__('Tambahkan user baru dengan mengisi form di bawah ini.')); ?></p>

                <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="mt-6 space-y-5">
                    <?php echo csrf_field(); ?>

                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Nama')); ?></label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="<?php echo e(old('name')); ?>"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        <?php $__errorArgs = ['name', 'storeUser'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1">
                        <label for="username" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Username')); ?></label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            required
                            value="<?php echo e(old('username')); ?>"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        <?php $__errorArgs = ['username', 'storeUser'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Email')); ?></label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="<?php echo e(old('email')); ?>"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        <?php $__errorArgs = ['email', 'storeUser'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1">
                        <label for="role" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Role')); ?></label>
                        <select
                            id="role"
                            name="role"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            required
                        >
                            <option value="" disabled <?php echo e(old('role') ? '' : 'selected'); ?>><?php echo e(__('Pilih role')); ?></option>
                            <?php $__currentLoopData = $roleOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleValue => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roleValue); ?>" <?php echo e(old('role') === $roleValue ? 'selected' : ''); ?>>
                                    <?php echo e($roleLabel); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['role', 'storeUser'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Password')); ?></label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        <?php $__errorArgs = ['password', 'storeUser'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            <?php echo e(__('Simpan User')); ?>

                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white"><?php echo e(__('Daftar User')); ?></h2>
                    <span class="text-sm text-neutral-500"><?php echo e($users->count()); ?> <?php echo e(\Illuminate\Support\Str::plural('User', $users->count())); ?></span>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isEditingUser = (string) old('user_id') === (string) $user->id;
                        $oldEmail = $isEditingUser ? old('email') : $user->email;
                        $selectedRole = $isEditingUser ? old('role') : $user->role;
                    ?>
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="border-b border-neutral-200 px-4 py-3 dark:border-neutral-700">
                            <h3 class="text-base font-semibold text-neutral-900 dark:text-white"><?php echo e($user->name); ?></h3>
                            <p class="text-sm text-neutral-500"><?php echo e($user->email); ?> &middot; <span class="uppercase"><?php echo e($user->role); ?></span></p>
                        </div>

                        <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="space-y-4 px-4 py-4">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">

                            <div class="space-y-1">
                                <label for="email-<?php echo e($user->id); ?>" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Email')); ?></label>
                                <input
                                    id="email-<?php echo e($user->id); ?>"
                                    name="email"
                                    type="email"
                                    value="<?php echo e($oldEmail); ?>"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                <?php $__errorArgs = ['email', 'updateUser_'.$user->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="space-y-1">
                                <label for="role-<?php echo e($user->id); ?>" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Role')); ?></label>
                                <select
                                    id="role-<?php echo e($user->id); ?>"
                                    name="role"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                    <?php $__currentLoopData = $roleOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleValue => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($roleValue); ?>" <?php echo e($selectedRole === $roleValue ? 'selected' : ''); ?>>
                                            <?php echo e($roleLabel); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['role', 'updateUser_'.$user->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="space-y-1">
                                <label for="password-<?php echo e($user->id); ?>" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200"><?php echo e(__('Password Baru')); ?></label>
                                <input
                                    id="password-<?php echo e($user->id); ?>"
                                    name="password"
                                    type="password"
                                    placeholder="<?php echo e(__('Kosongkan jika tidak ingin mengganti password')); ?>"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                >
                                <?php $__errorArgs = ['password', 'updateUser_'.$user->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-sm text-rose-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                                    <?php echo e(__('Simpan Perubahan')); ?>

                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="rounded-xl border border-dashed border-neutral-300 bg-neutral-50 px-4 py-10 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-900/40 dark:text-neutral-300">
                        <?php echo e(__('Belum ada user yang terdaftar.')); ?>

                    </div>
                <?php endif; ?>
            </div>
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
<?php /**PATH /var/www/geka/gd/resources/views/admin/users/index.blade.php ENDPATH**/ ?>