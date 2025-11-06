<x-layouts.app :title="__('Hak Akses User')">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

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
        x-cloak
        class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8"
    >
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-neutral-500">{{ __('Master') }}</p>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Hak Akses User') }}</h1>
            </div>

            <template x-if="status">
                <div
                    class="rounded-lg border px-4 py-2 text-sm"
                    :class="status.type === 'success'
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-100'
                        : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-700 dark:bg-rose-900/30 dark:text-rose-100'"
                >
                    <span x-text="status.message"></span>
                </div>
            </template>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,360px)]">
            <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Panel Menu') }}</h2>
                        <p class="mt-1 text-sm text-neutral-500">
                            {{ __('Kelola akses menu dengan struktur hirarkis berikut.') }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                            @click="expandAll()"
                        >
                            <flux:icon.arrows-pointing-out class="h-4 w-4" />
                            {{ __('Expand all') }}
                        </button>
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 shadow-sm transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                            @click="collapseAll()"
                        >
                            <flux:icon.arrows-pointing-in class="h-4 w-4" />
                            {{ __('Collapse all') }}
                        </button>
                    </div>
                </div>

                <div>
                    <label for="search-menu" class="text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('Search') }}</label>
                    <div class="mt-1 flex items-center rounded-lg border border-neutral-300 px-3 py-2 shadow-sm focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800">
                        <flux:icon.magnifying-glass class="h-4 w-4 text-neutral-400" />
                        <input
                            id="search-menu"
                            type="search"
                            placeholder="{{ __('Cari menu atau sub-menu...') }}"
                            x-model="search"
                            class="ms-2 w-full border-0 bg-transparent text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-100"
                        >
                    </div>
                </div>

                <div class="relative">
                    <div
                        x-show="!selectedUserId"
                        class="pointer-events-none absolute inset-0 flex items-center justify-center rounded-lg border border-dashed border-neutral-300 bg-white/80 text-center text-sm text-neutral-500 backdrop-blur-sm dark:border-neutral-700 dark:bg-neutral-900/80 dark:text-neutral-300"
                    >
                        {{ __('Pilih user terlebih dahulu untuk mengatur hak akses.') }}
                    </div>

                    <div class="mt-4 space-y-3" :class="selectedUserId ? '' : 'opacity-50'">
                        <template x-for="node in filteredTree()" :key="node.key">
                            <div x-data="treeNode(node)">
                                <div class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 dark:border-neutral-700">
                                    <div class="flex flex-1 items-center gap-3">
                                        <button
                                            type="button"
                                            class="flex h-7 w-7 items-center justify-center rounded-md border border-neutral-300 text-sm font-medium text-neutral-600 transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                                            @click="$root.toggleNode(node.key)"
                                            x-show="hasChildren"
                                            :aria-expanded="$root.isExpanded(node.key)"
                                        >
                                            <span x-text="$root.isExpanded(node.key) ? '-' : '+'"></span>
                                        </button>
                                        <div class="flex flex-1 flex-col">
                                            <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100" x-text="node.label"></span>
                                            <template x-if="node.children?.length">
                                                <span class="text-xs uppercase tracking-wide text-neutral-400 dark:text-neutral-500">{{ __('Node Utama') }}</span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border text-neutral-500 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            :class="$root.isAllowed(node.key)
                                                ? 'border-indigo-500 bg-indigo-600 text-white hover:bg-indigo-500'
                                                : 'border-neutral-300 hover:border-neutral-400 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800'"
                                            @click="$root.togglePermission(node.key)"
                                            :disabled="!selectedUserId"
                                        >
                                            <flux:icon.eye class="h-5 w-5" />
                                        </button>
                                        <template x-if="node.actions?.length">
                                            <button
                                                type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-neutral-300 text-neutral-500 transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                                                @click="$root.openActionModal(node.key)"
                                                :disabled="!selectedUserId"
                                            >
                                                <flux:icon.cog-6-tooth class="h-5 w-5" />
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <div
                                    x-show="hasChildren && $root.isExpanded(node.key)"
                                    class="ms-6 mt-2 border-l border-dashed border-neutral-300 ps-4 dark:border-neutral-700"
                                >
                                    <template x-for="child in node.children" :key="child.key">
                                        <div x-data="treeNode(child)" class="mt-2 first:mt-0">
                                            <div class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 dark:border-neutral-700">
                                                <div class="flex flex-1 items-center gap-3">
                                                    <template x-if="hasChildren">
                                                        <button
                                                            type="button"
                                                            class="flex h-6 w-6 items-center justify-center rounded-md border border-neutral-300 text-xs font-medium text-neutral-600 transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:text-neutral-200 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                                                            @click="$root.toggleNode(child.key)"
                                                            :aria-expanded="$root.isExpanded(child.key)"
                                                        >
                                                            <span x-text="$root.isExpanded(child.key) ? '-' : '+'"></span>
                                                        </button>
                                                    </template>
                                                    <div class="flex flex-1 flex-col">
                                                        <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100" x-text="child.label"></span>
                                                        <template x-if="child.actions?.length">
                                                            <span class="text-xs uppercase tracking-wide text-neutral-400 dark:text-neutral-500">{{ __('Node Sub-Menu') }}</span>
                                                        </template>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <button
                                                        type="button"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border text-neutral-500 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                        :class="$root.isAllowed(child.key)
                                                            ? 'border-indigo-500 bg-indigo-600 text-white hover:bg-indigo-500'
                                                            : 'border-neutral-300 hover:border-neutral-400 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800'"
                                                        @click="$root.togglePermission(child.key)"
                                                        :disabled="!selectedUserId"
                                                    >
                                                        <flux:icon.eye class="h-5 w-5" />
                                                    </button>
                                                    <template x-if="child.actions?.length">
                                                        <button
                                                            type="button"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-neutral-300 text-neutral-500 transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                                                            @click="$root.openActionModal(child.key)"
                                                            :disabled="!selectedUserId"
                                                        >
                                                            <flux:icon.cog-6-tooth class="h-5 w-5" />
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <template x-if="hasChildren">
                                                <div
                                                    x-show="$root.isExpanded(child.key)"
                                                    class="ms-6 mt-2 border-l border-dashed border-neutral-300 ps-4 dark:border-neutral-700"
                                                >
                                                    <template x-for="grandChild in child.children" :key="grandChild.key">
                                                        <div class="mt-2 first:mt-0">
                                                            <div class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 dark:border-neutral-700">
                                                                <div>
                                                                    <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100" x-text="grandChild.label"></span>
                                                                    <template x-if="grandChild.actions?.length">
                                                                        <span class="block text-xs uppercase tracking-wide text-neutral-400 dark:text-neutral-500">{{ __('Resource Izin') }}</span>
                                                                    </template>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <button
                                                                        type="button"
                                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full border text-neutral-500 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                                        :class="$root.isAllowed(grandChild.key)
                                                                            ? 'border-indigo-500 bg-indigo-600 text-white hover:bg-indigo-500'
                                                                            : 'border-neutral-300 hover:border-neutral-400 hover:bg-neutral-100 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800'"
                                                                        @click="$root.togglePermission(grandChild.key)"
                                                                        :disabled="!selectedUserId"
                                                                    >
                                                                        <flux:icon.eye class="h-5 w-5" />
                                                                    </button>
                                                                    <template x-if="grandChild.actions?.length">
                                                                        <button
                                                                            type="button"
                                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-neutral-300 text-neutral-500 transition hover:border-neutral-400 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-neutral-600 dark:hover:border-neutral-500 dark:hover:bg-neutral-800"
                                                                            @click="$root.openActionModal(grandChild.key)"
                                                                            :disabled="!selectedUserId"
                                                                        >
                                                                            <flux:icon.cog-6-tooth class="h-5 w-5" />
                                                                        </button>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-4 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-900">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Panel Kontrol User') }}</h2>
                    <p class="mt-1 text-sm text-neutral-500">{{ __('Pilih user dan simpan konfigurasi izin yang diinginkan.') }}</p>
                </div>

                <div class="space-y-1">
                    <label for="user-selector" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">{{ __('MASUKKAN USER ID') }}</label>
                    <select
                        id="user-selector"
                        class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
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
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100">
                        <p class="font-medium" x-text="selectedUser.name"></p>
                        <p class="text-xs uppercase tracking-wide text-neutral-500" x-text="selectedUser.username"></p>
                        <p class="mt-1 text-xs text-neutral-500">
                            {{ __('Perubahan izin hanya berlaku untuk user yang saat ini dipilih.') }}
                        </p>
                    </div>
                </template>

                <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
                    <button
                        type="button"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-400 dark:focus:ring-offset-neutral-900"
                        @click="saveAccess"
                        :disabled="!selectedUserId || isSaving || isLoading"
                    >
                        <template x-if="!isSaving">
                            <span>{{ __('Simpan') }}</span>
                        </template>
                        <template x-if="isSaving">
                            <span>{{ __('Menyimpan...') }}</span>
                        </template>
                    </button>
                    <button
                        type="button"
                        class="inline-flex flex-1 items-center justify-center rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-rose-400 dark:focus:ring-offset-neutral-900"
                        @click="resetAccess"
                        :disabled="!selectedUserId || isResetting || isLoading"
                    >
                        <template x-if="!isResetting">
                            <span>{{ __('Reset Hak Akses') }}</span>
                        </template>
                        <template x-if="isResetting">
                            <span>{{ __('Mereset...') }}</span>
                        </template>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Detail Izin -->
        <div
            x-show="activeModalKey"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
            x-transition.opacity
            @keydown.escape.window="closeModal"
            @click.self="closeModal"
        >
            <div class="w-full max-w-md rounded-xl border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">{{ __('Detail Izin') }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            {{ __('Atur izin CRUD khusus untuk menu yang dipilih.') }}
                        </p>
                    </div>
                    <button
                        type="button"
                        class="rounded-full p-1 text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:hover:bg-neutral-800"
                        @click="closeModal"
                    >
                        <flux:icon.x-mark class="h-5 w-5" />
                    </button>
                </div>

                <div class="mt-5 space-y-3">
                    <template x-if="modalActions.length">
                        <template x-for="action in modalActions" :key="action">
                            <label class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 text-sm font-medium text-neutral-700 dark:border-neutral-700 dark:text-neutral-100">
                                <span class="uppercase tracking-wide" x-text="actionLabels[action] ?? action"></span>
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500 dark:border-neutral-600"
                                    :checked="currentPermissions[activeModalKey]?.actions?.[action] ?? false"
                                    @change="toggleAction(activeModalKey, action)">
                            </label>
                        </template>
                    </template>
                    <template x-if="!modalActions.length">
                        <p class="text-sm text-neutral-500">{{ __('Menu ini tidak memiliki izin detail.') }}</p>
                    </template>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-900"
                        @click="closeModal"
                    >
                        {{ __('Selesai') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('accessManager', (config) => ({
                menuTree: config.menuTree,
                users: config.users,
                defaults: config.defaults,
                routes: config.routes,
                csrfToken: config.csrfToken,
                expanded: {},
                nodeMap: {},
                search: '',
                selectedUserId: '',
                selectedUser: null,
                currentPermissions: JSON.parse(JSON.stringify(config.defaults)),
                isLoading: false,
                isSaving: false,
                isResetting: false,
                status: null,
                activeModalKey: null,
                actionLabels: {
                    create: 'Create',
                    read: 'Read',
                    update: 'Update',
                    delete: 'Delete',
                    export: 'Export',
                },
                init() {
                    this.nodeMap = this.buildNodeMap(this.menuTree);
                    this.expanded = this.buildExpandedState(this.menuTree, true);
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
                buildExpandedState(nodes, value, expanded = {}) {
                    nodes.forEach((node) => {
                        expanded[node.key] = value;
                        if (node.children?.length) {
                            this.buildExpandedState(node.children, value, expanded);
                        }
                    });

                    return expanded;
                },
                filteredTree() {
                    if (!this.search.trim()) {
                        return this.menuTree;
                    }

                    const term = this.search.toLowerCase();
                    const filtered = this.filterNodes(this.menuTree, term);
                    this.expandAll();

                    return filtered;
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
                toggleNode(key) {
                    if (!this.hasChildren(key)) {
                        return;
                    }

                    this.expanded[key] = !this.expanded[key];
                },
                isExpanded(key) {
                    return Boolean(this.expanded[key]);
                },
                hasChildren(key) {
                    const node = this.nodeMap[key];
                    return Boolean(node?.children?.length);
                },
                expandAll() {
                    this.expanded = this.buildExpandedState(this.menuTree, true);
                },
                collapseAll() {
                    this.expanded = this.buildExpandedState(this.menuTree, false);
                },
                isAllowed(key) {
                    return Boolean(this.currentPermissions[key]?.allowed);
                },
                togglePermission(key) {
                    const next = !this.isAllowed(key);
                    this.setPermissionRecursive(key, next);
                },
                setPermissionRecursive(key, allowed) {
                    if (!this.currentPermissions[key]) {
                        this.currentPermissions[key] = { allowed: allowed, actions: {} };
                    }

                    this.currentPermissions[key].allowed = allowed;

                    Object.keys(this.currentPermissions[key].actions ?? {}).forEach((action) => {
                        if (!allowed) {
                            this.currentPermissions[key].actions[action] = false;
                        }
                    });

                    const node = this.nodeMap[key];
                    if (node?.children?.length) {
                        node.children.forEach((child) => {
                            this.setPermissionRecursive(child.key, allowed);
                        });
                    }
                },
                openActionModal(key) {
                    const node = this.nodeMap[key];
                    if (!node?.actions?.length) {
                        return;
                    }

                    if (!this.currentPermissions[key]) {
                        this.currentPermissions[key] = {
                            allowed: false,
                            actions: {},
                        };
                    }

                    node.actions.forEach((action) => {
                        if (!(action in this.currentPermissions[key].actions)) {
                            this.currentPermissions[key].actions[action] = false;
                        }
                    });

                    this.activeModalKey = key;
                },
                closeModal() {
                    this.activeModalKey = null;
                },
                get modalActions() {
                    if (!this.activeModalKey) {
                        return [];
                    }

                    return this.nodeMap[this.activeModalKey]?.actions ?? [];
                },
                toggleAction(key, action) {
                    if (!this.currentPermissions[key]) {
                        this.currentPermissions[key] = {
                            allowed: false,
                            actions: {},
                        };
                    }

                    const current = Boolean(this.currentPermissions[key].actions?.[action]);
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

            Alpine.data('treeNode', (node) => ({
                node,
                get hasChildren() {
                    return Boolean(this.node.children?.length);
                },
            }));
        });
    </script>
</x-layouts.app>
