<x-layouts.app :title="__('Hak Akses User')">
    <div
        x-data="accessManager({
            menuTree: @js($menuTree),
            defaults: @js($defaultPermissions),
            users: @js($users->map(fn ($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
            ])->values()->all()),
            routes: {
                show: '{{ route('admin.access.show', ['user' => '__USER__']) }}',
                update: '{{ route('admin.access.update', ['user' => '__USER__']) }}',
                reset: '{{ route('admin.access.reset', ['user' => '__USER__']) }}',
            },
            csrfToken: '{{ csrf_token() }}',
        })"
        class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8"
    >
        <header class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Pengaturan Aplikasi') }}</p>
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Hak Akses User') }}</h1>
                <p class="text-sm text-neutral-600 dark:text-neutral-300">
                    {{ __('Atur izin menu dan sub-menu untuk setiap user dengan mudah, termasuk akses lanjutan untuk fitur tertentu.') }}
                </p>
            </div>
        </header>

        <template x-if="status">
            <div
                class="rounded-2xl border px-4 py-3 text-sm"
                :class="status.type === 'success'
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100'
                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-700 dark:bg-rose-900/40 dark:text-rose-100'"
            >
                <span x-text="status.message"></span>
            </div>
        </template>

        <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,320px)]">
            <div class="space-y-6">
                <section class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <div class="space-y-3">
                        <div>
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Pilih User') }}</h2>
                            <p class="text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Tentukan user yang akan diberikan hak akses menu aplikasi.') }}
                            </p>
                        </div>

                        <div class="space-y-1">
                            <label for="user-selector" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('User ID') }}</label>
                            <select
                                id="user-selector"
                                class="w-full rounded-xl border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                                x-model="selectedUserId"
                                @change="selectUser($event)"
                            >
                                <option value="">{{ __('Pilih user...') }}</option>
                                <template x-for="user in users" :key="user.id">
                                    <option :value="user.id" x-text="`${user.username.toUpperCase()} — ${user.name}`"></option>
                                </template>
                            </select>
                        </div>

                        <template x-if="selectedUser">
                            <div class="rounded-xl border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                                <p class="font-semibold" x-text="selectedUser.name"></p>
                                <p class="text-xs uppercase tracking-wide text-neutral-500" x-text="selectedUser.username"></p>
                                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Perubahan izin hanya berlaku untuk user ini.') }}
                                </p>
                            </div>
                        </template>
                    </div>
                </section>

                <section class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <div class="space-y-4">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Hak Akses Menu & Submenu') }}</h2>
                                <p class="text-sm text-neutral-500 dark:text-neutral-300">
                                    {{ __('Aktifkan atau nonaktifkan akses menu serta kelola izin detail untuk submenu yang tersedia.') }}
                                </p>
                            </div>
                            <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                                <div class="flex h-11 w-full items-center gap-2 rounded-xl border border-neutral-300 px-3 focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 sm:max-w-xs">
                                    <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10.6 10.6Z" />
                                    </svg>
                                    <input
                                        id="search-menu"
                                        type="search"
                                        placeholder="{{ __('Cari menu atau submenu') }}"
                                        x-model="search"
                                        class="w-full border-0 bg-transparent text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-100"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="relative space-y-4">
                            <div
                                x-show="!selectedUserId"
                                class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center rounded-2xl border border-dashed border-neutral-300 bg-white/70 text-center text-sm text-neutral-500 backdrop-blur dark:border-neutral-700 dark:bg-neutral-900/70 dark:text-neutral-300"
                            >
                                {{ __('Pilih user terlebih dahulu untuk mengatur hak akses.') }}
                            </div>

                            <template x-if="isLoading">
                                <div class="space-y-3 rounded-2xl border border-neutral-200 bg-neutral-50 p-5 text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                    {{ __('Memuat data hak akses...') }}
                                </div>
                            </template>

                            <div class="space-y-4" :class="{ 'opacity-50': !selectedUserId }">
                                <template x-if="selectedUserId && filteredTree().length === 0 && !isLoading">
                                    <p class="rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-5 text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200">
                                        {{ __('Tidak ada menu yang cocok dengan pencarian Anda.') }}
                                    </p>
                                </template>

                                <template x-for="node in filteredTree()" :key="node.key">
                                    @include('admin.access.partials.node', ['level' => 0, 'nodeVar' => 'node'])
                                </template>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="flex flex-col gap-4">
                <section class="rounded-2xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Ringkasan Aksi') }}</h2>
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-300">
                        {{ __('Simpan perubahan atau kembalikan ke pengaturan awal jika dibutuhkan.') }}
                    </p>

                    <div class="mt-6 space-y-3">
                        <button
                            type="button"
                            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-400 dark:focus:ring-offset-neutral-900"
                            @click="saveAccess"
                            :disabled="!selectedUserId || isSaving || isLoading"
                        >
                            <template x-if="!isSaving">
                                <span>{{ __('Simpan Hak Akses') }}</span>
                            </template>
                            <template x-if="isSaving">
                                <span>{{ __('Menyimpan...') }}</span>
                            </template>
                        </button>

                        <button
                            type="button"
                            class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:border-rose-100 disabled:bg-rose-100 disabled:text-rose-300 dark:border-rose-700 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-900/60 dark:focus:ring-offset-neutral-900"
                            @click="resetAccess"
                            :disabled="!selectedUserId || isResetting || isLoading"
                        >
                            <template x-if="!isResetting">
                                <span>{{ __('Reset Ke Default') }}</span>
                            </template>
                            <template x-if="isResetting">
                                <span>{{ __('Mereset...') }}</span>
                            </template>
                        </button>
                    </div>
                </section>

                <section class="rounded-2xl border border-neutral-200 bg-white p-5 text-sm text-neutral-500 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                    <p class="font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tips Pengaturan') }}</p>
                    <ul class="mt-3 space-y-2 list-disc ps-5">
                        <li>{{ __('Aktifkan menu utama untuk membuka akses ke submenu di dalamnya.') }}</li>
                        <li>{{ __('Izin detail (seperti create, update) hanya dapat diaktifkan jika menu utama diizinkan.') }}</li>
                        <li>{{ __('Gunakan kolom pencarian untuk menemukan menu lebih cepat, terutama saat menggunakan perangkat mobile.') }}</li>
                    </ul>
                </section>
            </aside>
        </section>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('accessManager', (config) => ({
                menuTree: config.menuTree,
                users: config.users,
                defaults: config.defaults,
                routes: config.routes,
                csrfToken: config.csrfToken,
                nodeMap: {},
                search: '',
                selectedUserId: '',
                selectedUser: null,
                currentPermissions: JSON.parse(JSON.stringify(config.defaults)),
                isLoading: false,
                isSaving: false,
                isResetting: false,
                status: null,
                actionLabels: {
                    create: 'Create',
                    read: 'Read',
                    update: 'Update',
                    delete: 'Delete',
                    export: 'Export',
                },
                init() {
                    this.nodeMap = this.buildNodeMap(this.menuTree);
                    this.prepareAllNodes();
                },
                buildNodeMap(nodes, map = {}) {
                    nodes.forEach((node) => {
                        map[node.key] = node;
                        if (node.children?.length) {
                            this.buildNodeMap(node.children, map);
                        }
                    });

                    return map;
                },
                prepareAllNodes() {
                    Object.keys(this.nodeMap).forEach((key) => this.prepareNode(key));
                },
                prepareNode(key) {
                    const node = this.nodeMap[key];
                    if (!node) {
                        return;
                    }

                    if (!this.currentPermissions[key]) {
                        this.currentPermissions[key] = { allowed: false, actions: {} };
                    }

                    if (!this.currentPermissions[key].actions) {
                        this.currentPermissions[key].actions = {};
                    }

                    if (node.actions?.length) {
                        node.actions.forEach((action) => {
                            if (!(action in this.currentPermissions[key].actions)) {
                                this.currentPermissions[key].actions[action] = false;
                            }
                        });
                    }
                },
                filteredTree() {
                    if (!this.search.trim()) {
                        return this.menuTree;
                    }

                    const term = this.search.toLowerCase();
                    return this.filterNodes(this.menuTree, term);
                },
                filterNodes(nodes, term) {
                    return nodes
                        .map((node) => {
                            const matches = node.label.toLowerCase().includes(term);
                            const children = node.children?.length ? this.filterNodes(node.children, term) : [];

                            if (matches || children.length) {
                                return {
                                    ...node,
                                    children,
                                };
                            }

                            return null;
                        })
                        .filter(Boolean);
                },
                isAllowed(key) {
                    return Boolean(this.currentPermissions[key]?.allowed);
                },
                togglePermission(key) {
                    this.prepareNode(key);
                    const next = !this.isAllowed(key);
                    this.setPermissionRecursive(key, next);
                },
                setPermissionRecursive(key, allowed) {
                    this.prepareNode(key);
                    this.currentPermissions[key].allowed = allowed;

                    if (!allowed) {
                        Object.keys(this.currentPermissions[key].actions ?? {}).forEach((action) => {
                            this.currentPermissions[key].actions[action] = false;
                        });
                    }

                    const node = this.nodeMap[key];
                    if (node?.children?.length) {
                        node.children.forEach((child) => {
                            this.setPermissionRecursive(child.key, allowed);
                        });
                    }
                },
                toggleAction(key, action) {
                    this.prepareNode(key);
                    const current = this.currentPermissions[key].actions[action] ?? false;
                    this.currentPermissions[key].actions[action] = !current;

                    if (this.currentPermissions[key].actions[action]) {
                        this.currentPermissions[key].allowed = true;
                    }
                },
                async selectUser(event) {
                    const userId = event?.target?.value ?? this.selectedUserId;
                    this.selectedUserId = userId;
                    this.selectedUser = this.users.find((user) => String(user.id) === String(userId)) ?? null;

                    if (!this.selectedUserId) {
                        this.currentPermissions = JSON.parse(JSON.stringify(this.defaults));
                        this.prepareAllNodes();
                        return;
                    }

                    this.isLoading = true;
                    this.status = null;

                    try {
                        const response = await fetch(this.routes.show.replace('__USER__', this.selectedUserId));
                        if (!response.ok) {
                            throw new Error('Failed to load permissions');
                        }

                        const data = await response.json();
                        this.currentPermissions = data.permissions ?? JSON.parse(JSON.stringify(this.defaults));
                        this.prepareAllNodes();
                    } catch (error) {
                        this.status = {
                            type: 'error',
                            message: '{{ __('Gagal mengambil data hak akses. Silakan coba lagi.') }}',
                        };
                    } finally {
                        this.isLoading = false;
                    }
                },
                async saveAccess() {
                    if (!this.selectedUserId) {
                        return;
                    }

                    this.isSaving = true;
                    this.status = null;

                    try {
                        const response = await fetch(this.routes.update.replace('__USER__', this.selectedUserId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                            body: JSON.stringify({ permissions: this.currentPermissions }),
                        });

                        if (!response.ok) {
                            throw new Error('Failed to save permissions');
                        }

                        const data = await response.json();
                        this.status = {
                            type: 'success',
                            message: data.message ?? '{{ __('Konfigurasi berhasil disimpan.') }}',
                        };
                    } catch (error) {
                        this.status = {
                            type: 'error',
                            message: '{{ __('Tidak dapat menyimpan konfigurasi hak akses. Silakan coba lagi.') }}',
                        };
                    } finally {
                        this.isSaving = false;
                    }
                },
                async resetAccess() {
                    if (!this.selectedUserId) {
                        return;
                    }

                    if (!window.confirm('{{ __('Apakah Anda yakin ingin mereset semua hak akses untuk user ini?') }}')) {
                        return;
                    }

                    this.isResetting = true;
                    this.status = null;

                    try {
                        const response = await fetch(this.routes.reset.replace('__USER__', this.selectedUserId), {
                            method: 'DELETE',
                            headers: {
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Failed to reset permissions');
                        }

                        const data = await response.json();
                        this.currentPermissions = data.permissions ?? JSON.parse(JSON.stringify(this.defaults));
                        this.prepareAllNodes();
                        this.status = {
                            type: 'success',
                            message: data.message ?? '{{ __('Hak akses telah direset ke pengaturan awal.') }}',
                        };
                    } catch (error) {
                        this.status = {
                            type: 'error',
                            message: '{{ __('Gagal mereset hak akses. Silakan coba lagi.') }}',
                        };
                    } finally {
                        this.isResetting = false;
                    }
                },
            }));
        });
    </script>
</x-layouts.app>
