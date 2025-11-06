<x-layouts.app :title="__('Master User')">
    <div class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-neutral-500">{{ __('Master') }}</p>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Master User') }}</h1>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,360px)_1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Tambah User Baru') }}</h2>
                <p class="mt-1 text-sm text-neutral-500">{{ __('Tambahkan user baru dengan mengisi form di bawah ini.') }}</p>

                <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Nama') }}</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name') }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        @error('name', 'storeUser')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="username" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Username') }}</label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            required
                            value="{{ old('username') }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        @error('username', 'storeUser')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Email') }}</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        @error('email', 'storeUser')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="role" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Role') }}</label>
                        <select
                            id="role"
                            name="role"
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                            required
                        >
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>{{ __('Pilih role') }}</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                        </select>
                        @error('role', 'storeUser')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Password') }}</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                        >
                        @error('password', 'storeUser')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                            {{ __('Simpan User') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Daftar User') }}</h2>
                    <span class="text-sm text-neutral-500">{{ $users->count() }} {{ \Illuminate\Support\Str::plural('User', $users->count()) }}</span>
                </div>

                @forelse ($users as $user)
                    @php
                        $isEditingUser = (string) old('user_id') === (string) $user->id;
                        $oldEmail = $isEditingUser ? old('email') : $user->email;
                        $selectedRole = $isEditingUser ? old('role') : $user->role;
                    @endphp
                    <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                        <div class="border-b border-neutral-200 px-4 py-3 dark:border-neutral-700">
                            <h3 class="text-base font-semibold text-neutral-900 dark:text-white">{{ $user->name }}</h3>
                            <p class="text-sm text-neutral-500">{{ $user->email }} &middot; <span class="uppercase">{{ $user->role }}</span></p>
                        </div>

                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 px-4 py-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="space-y-1">
                                <label for="email-{{ $user->id }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Email') }}</label>
                                <input
                                    id="email-{{ $user->id }}"
                                    name="email"
                                    type="email"
                                    value="{{ $oldEmail }}"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                @error('email', 'updateUser_'.$user->id)
                                    <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="role-{{ $user->id }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Role') }}</label>
                                <select
                                    id="role-{{ $user->id }}"
                                    name="role"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                    required
                                >
                                    <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                    <option value="user" {{ $selectedRole === 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                                </select>
                                @error('role', 'updateUser_'.$user->id)
                                    <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="password-{{ $user->id }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Password Baru') }}</label>
                                <input
                                    id="password-{{ $user->id }}"
                                    name="password"
                                    type="password"
                                    placeholder="{{ __('Kosongkan jika tidak ingin mengganti password') }}"
                                    class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white"
                                >
                                @error('password', 'updateUser_'.$user->id)
                                    <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900">
                                    {{ __('Simpan Perubahan') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-neutral-300 bg-neutral-50 px-4 py-10 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-900/40 dark:text-neutral-300">
                        {{ __('Belum ada user yang terdaftar.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
