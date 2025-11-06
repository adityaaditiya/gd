<x-layouts.app :title="__('Hak Akses User')">
    <div
        id="access-manager"
        data-menu-tree='@json($menuTree)'
        data-defaults='@json($defaultPermissions)'
        data-users='@json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
            ];
        })->values()->all())'
        data-routes='@json([
            'show' => route('admin.access.show', ['user' => '__USER__']),
            'update' => route('admin.access.update', ['user' => '__USER__']),
            'reset' => route('admin.access.reset', ['user' => '__USER__']),
        ])'
        data-csrf="{{ csrf_token() }}"
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

        <div
            data-status
            class="hidden rounded-2xl border px-4 py-3 text-sm"
        ></div>

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
                            <label for="user-selector" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200">
                                {{ __('User ID') }}
                            </label>
                            <select
                                id="user-selector"
                                data-user-select
                                class="w-full rounded-xl border border-neutral-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                                <option value="">{{ __('Pilih user...') }}</option>
                            </select>
                        </div>

                        <div
                            data-selected-user
                            class="hidden rounded-xl border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-700 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100"
                        >
                            <p class="font-semibold" data-selected-name></p>
                            <p class="text-xs uppercase tracking-wide text-neutral-500" data-selected-username></p>
                            <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                {{ __('Perubahan izin hanya berlaku untuk user ini.') }}
                            </p>
                        </div>
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
                                        data-search
                                        class="w-full border-0 bg-transparent text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none dark:text-neutral-100"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="relative space-y-4">
                            <div
                                data-user-overlay
                                class="pointer-events-none absolute inset-0 z-10 hidden flex items-center justify-center rounded-2xl border border-dashed border-neutral-300 bg-white/70 text-center text-sm text-neutral-500 backdrop-blur dark:border-neutral-700 dark:bg-neutral-900/70 dark:text-neutral-300"
                            >
                                {{ __('Pilih user terlebih dahulu untuk mengatur hak akses.') }}
                            </div>

                            <div
                                data-loading
                                class="hidden space-y-3 rounded-2xl border border-neutral-200 bg-neutral-50 p-5 text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                            >
                                {{ __('Memuat data hak akses...') }}
                            </div>

                            <div data-tree-wrapper class="space-y-4">
                                <p
                                    data-no-results
                                    class="hidden rounded-2xl border border-neutral-200 bg-neutral-50 px-4 py-5 text-sm text-neutral-500 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-200"
                                >
                                    {{ __('Tidak ada menu yang cocok dengan pencarian Anda.') }}
                                </p>
                                <div data-tree class="space-y-4"></div>
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
                            data-save
                            class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-400 dark:focus:ring-offset-neutral-900"
                        >
                            <span data-save-label>{{ __('Simpan Hak Akses') }}</span>
                            <span data-save-progress class="hidden">{{ __('Menyimpan...') }}</span>
                        </button>

                        <button
                            type="button"
                            data-reset
                            class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:border-rose-100 disabled:bg-rose-100 disabled:text-rose-300 dark:border-rose-700 dark:bg-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-900/60 dark:focus:ring-offset-neutral-900"
                        >
                            <span data-reset-label>{{ __('Reset Ke Default') }}</span>
                            <span data-reset-progress class="hidden">{{ __('Mereset...') }}</span>
                        </button>
                    </div>
                </section>

                <section class="rounded-2xl border border-neutral-200 bg-white p-5 text-sm text-neutral-500 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
                    <p class="font-medium text-neutral-700 dark:text-neutral-200">{{ __('Tips Pengaturan') }}</p>
                    <ul class="mt-3 list-disc space-y-2 ps-5">
                        <li>{{ __('Aktifkan menu utama untuk membuka akses ke submenu di dalamnya.') }}</li>
                        <li>{{ __('Izin detail (seperti create, update) hanya dapat diaktifkan jika menu utama diizinkan.') }}</li>
                        <li>{{ __('Gunakan kolom pencarian untuk menemukan menu lebih cepat, terutama saat menggunakan perangkat mobile.') }}</li>
                    </ul>
                </section>
            </aside>
        </section>

        <noscript>
            <div class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-600 dark:bg-amber-900/40 dark:text-amber-100">
                {{ __('Fitur pengaturan hak akses memerlukan JavaScript. Aktifkan JavaScript di browser Anda untuk mengelola izin user.') }}
            </div>
        </noscript>
    </div>

    <script type="module">
        (() => {
            const root = document.getElementById('access-manager');
            if (!root) {
                return;
            }

            const parseData = (value, fallback) => {
                try {
                    return JSON.parse(value ?? '');
                } catch (error) {
                    return fallback;
                }
            };

            class AccessManager {
                constructor(rootElement) {
                    this.root = rootElement;
                    this.menuTree = parseData(this.root.dataset.menuTree, []);
                    this.defaults = parseData(this.root.dataset.defaults, {});
                    this.users = parseData(this.root.dataset.users, []);
                    this.routes = parseData(this.root.dataset.routes, {});
                    this.csrfToken = this.root.dataset.csrf ?? '';

                    this.statusEl = this.root.querySelector('[data-status]');
                    this.userSelect = this.root.querySelector('[data-user-select]');
                    this.userInfo = this.root.querySelector('[data-selected-user]');
                    this.userName = this.root.querySelector('[data-selected-name]');
                    this.userUsername = this.root.querySelector('[data-selected-username]');
                    this.searchInput = this.root.querySelector('[data-search]');
                    this.userOverlay = this.root.querySelector('[data-user-overlay]');
                    this.treeWrapper = this.root.querySelector('[data-tree-wrapper]');
                    this.treeContainer = this.root.querySelector('[data-tree]');
                    this.noResults = this.root.querySelector('[data-no-results]');
                    this.loadingNotice = this.root.querySelector('[data-loading]');
                    this.saveButton = this.root.querySelector('[data-save]');
                    this.saveLabel = this.root.querySelector('[data-save-label]');
                    this.saveProgress = this.root.querySelector('[data-save-progress]');
                    this.resetButton = this.root.querySelector('[data-reset]');
                    this.resetLabel = this.root.querySelector('[data-reset-label]');
                    this.resetProgress = this.root.querySelector('[data-reset-progress]');

                    this.nodeMap = {};
                    this.searchTerm = '';
                    this.selectedUserId = '';
                    this.selectedUser = null;
                    this.currentPermissions = this.clone(this.defaults);
                    this.isLoading = false;
                    this.isSaving = false;
                    this.isResetting = false;
                    this.actionLabels = {
                        create: 'Create',
                        read: 'Read',
                        update: 'Update',
                        delete: 'Delete',
                        export: 'Export',
                    };

                    this.buildNodeMap(this.menuTree);
                    this.prepareAllNodes();
                    this.renderUserOptions();
                    this.bindEvents();
                    this.renderTree();
                    this.updateUiState();
                }

                clone(value) {
                    try {
                        return structuredClone(value);
                    } catch (error) {
                        return JSON.parse(JSON.stringify(value));
                    }
                }

                buildNodeMap(nodes) {
                    nodes.forEach((node) => {
                        if (!node?.key) {
                            return;
                        }
                        this.nodeMap[node.key] = node;
                        if (Array.isArray(node.children) && node.children.length) {
                            this.buildNodeMap(node.children);
                        }
                    });
                }

                prepareAllNodes() {
                    Object.keys(this.nodeMap).forEach((key) => this.prepareNode(key));
                }

                prepareNode(key) {
                    if (!key) {
                        return;
                    }

                    if (!this.currentPermissions[key]) {
                        this.currentPermissions[key] = { allowed: false, actions: {} };
                    }

                    if (!this.currentPermissions[key].actions) {
                        this.currentPermissions[key].actions = {};
                    }

                    const node = this.nodeMap[key];
                    if (node?.actions?.length) {
                        node.actions.forEach((action) => {
                            if (!(action in this.currentPermissions[key].actions)) {
                                this.currentPermissions[key].actions[action] = false;
                            }
                        });
                    }
                }

                bindEvents() {
                    this.userSelect.addEventListener('change', () => this.handleUserSelection());
                    this.searchInput.addEventListener('input', (event) => {
                        this.searchTerm = event.target.value ?? '';
                        this.renderTree();
                        this.updateUiState();
                    });
                    this.saveButton.addEventListener('click', () => this.saveAccess());
                    this.resetButton.addEventListener('click', () => this.resetAccess());
                }

                renderUserOptions() {
                    const fragment = document.createDocumentFragment();
                    this.users.forEach((user) => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = `${String(user.username || '').toUpperCase()} — ${user.name}`;
                        fragment.appendChild(option);
                    });

                    this.userSelect.appendChild(fragment);
                }

                handleUserSelection() {
                    this.selectedUserId = this.userSelect.value ?? '';
                    this.selectedUser = this.users.find((user) => String(user.id) === String(this.selectedUserId)) ?? null;

                    if (!this.selectedUserId) {
                        this.currentPermissions = this.clone(this.defaults);
                        this.prepareAllNodes();
                        this.setStatus();
                        this.renderTree();
                        this.updateUiState();
                        return;
                    }

                    this.loadPermissions();
                }

                async loadPermissions() {
                    this.setLoading(true);
                    this.setStatus();

                    try {
                        const response = await fetch(this.routes.show.replace('__USER__', this.selectedUserId), {
                            headers: { Accept: 'application/json' },
                        });

                        if (!response.ok) {
                            throw new Error('Failed to load permissions');
                        }

                        const data = await response.json();
                        this.currentPermissions = this.clone(data.permissions ?? this.defaults);
                        this.prepareAllNodes();
                    } catch (error) {
                        this.setStatus('error', '{{ __('Gagal mengambil data hak akses. Silakan coba lagi.') }}');
                        this.currentPermissions = this.clone(this.defaults);
                        this.prepareAllNodes();
                    } finally {
                        this.setLoading(false);
                        this.renderTree();
                        this.updateUiState();
                    }
                }

                setLoading(state) {
                    this.isLoading = Boolean(state);
                    if (this.loadingNotice) {
                        this.loadingNotice.classList.toggle('hidden', !this.isLoading);
                    }
                    this.updateUiState();
                }

                isAllowed(key) {
                    return Boolean(this.currentPermissions[key]?.allowed);
                }

                togglePermission(key) {
                    this.prepareNode(key);
                    const next = !this.isAllowed(key);
                    this.setPermissionRecursive(key, next);
                }

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
                        node.children.forEach((child) => this.setPermissionRecursive(child.key, allowed));
                    }
                }

                toggleAction(key, action) {
                    this.prepareNode(key);
                    const current = Boolean(this.currentPermissions[key].actions?.[action]);
                    this.currentPermissions[key].actions[action] = !current;

                    if (this.currentPermissions[key].actions[action]) {
                        this.currentPermissions[key].allowed = true;
                    }
                }

                filteredTree() {
                    const term = this.searchTerm.trim().toLowerCase();
                    if (!term) {
                        return this.menuTree;
                    }

                    return this.filterNodes(this.menuTree, term);
                }

                filterNodes(nodes, term) {
                    const result = [];
                    nodes.forEach((node) => {
                        const label = String(node.label ?? '').toLowerCase();
                        const description = String(node.description ?? '').toLowerCase();
                        const childMatches = Array.isArray(node.children)
                            ? this.filterNodes(node.children, term)
                            : [];

                        if (label.includes(term) || description.includes(term) || childMatches.length) {
                            result.push({
                                ...node,
                                children: childMatches,
                            });
                        }
                    });

                    return result;
                }

                renderTree() {
                    const nodes = this.filteredTree();
                    this.treeContainer.innerHTML = '';

                    if (nodes.length) {
                        nodes.forEach((node) => {
                            const element = this.renderNode(node);
                            if (element) {
                                this.treeContainer.appendChild(element);
                            }
                        });
                    }

                    const shouldShowNoResults =
                        Boolean(this.selectedUserId) &&
                        !this.isLoading &&
                        nodes.length === 0 &&
                        Boolean(this.searchTerm.trim());

                    if (this.noResults) {
                        this.noResults.classList.toggle('hidden', !shouldShowNoResults);
                    }
                }

                renderNode(node) {
                    if (!node?.key) {
                        return null;
                    }

                    const wrapper = document.createElement('div');
                    wrapper.className = 'rounded-2xl border border-neutral-200 p-4 shadow-sm dark:border-neutral-700';

                    if (Array.isArray(node.children) && node.children.length) {
                        wrapper.classList.add('bg-neutral-50', 'dark:bg-neutral-900/80');
                    } else {
                        wrapper.classList.add('bg-white', 'dark:bg-neutral-900');
                    }

                    const header = document.createElement('div');
                    header.className = 'flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between';

                    const info = document.createElement('div');
                    info.className = 'space-y-1';

                    const title = document.createElement('p');
                    title.className = 'text-base font-semibold text-neutral-900 dark:text-white';
                    title.textContent = node.label;
                    info.appendChild(title);

                    if (node.description) {
                        const description = document.createElement('p');
                        description.className = 'text-sm text-neutral-500';
                        description.textContent = node.description;
                        info.appendChild(description);
                    }

                    if (Array.isArray(node.actions) && node.actions.length) {
                        const badge = document.createElement('p');
                        badge.className = 'text-xs font-medium uppercase tracking-wide text-neutral-400 dark:text-neutral-500';
                        badge.textContent = '{{ __('Memiliki izin lanjutan') }}';
                        info.appendChild(badge);
                    }

                    header.appendChild(info);

                    const toggleLabel = document.createElement('label');
                    toggleLabel.className = 'inline-flex items-center justify-end gap-2 text-sm font-medium text-neutral-600 dark:text-neutral-300';
                    const toggleText = document.createElement('span');
                    toggleText.textContent = '{{ __('Izinkan menu') }}';
                    toggleLabel.appendChild(toggleText);

                    const toggle = document.createElement('input');
                    toggle.type = 'checkbox';
                    toggle.className = 'h-5 w-5 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500 dark:border-neutral-600';
                    toggle.checked = this.isAllowed(node.key);
                    toggle.disabled = !this.selectedUserId || this.isLoading;
                    toggle.addEventListener('change', () => {
                        this.togglePermission(node.key);
                        this.renderTree();
                        this.updateUiState();
                    });
                    toggleLabel.appendChild(toggle);

                    header.appendChild(toggleLabel);
                    wrapper.appendChild(header);

                    if (Array.isArray(node.actions) && node.actions.length) {
                        const actionsContainer = document.createElement('div');
                        actionsContainer.className = 'mt-4 space-y-2 rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-800';

                        const actionsTitle = document.createElement('p');
                        actionsTitle.className = 'text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400';
                        actionsTitle.textContent = '{{ __('Izin Detail Menu') }}';
                        actionsContainer.appendChild(actionsTitle);

                        node.actions.forEach((action) => {
                            const actionLabel = document.createElement('label');
                            actionLabel.className = 'flex items-center justify-between gap-4 rounded-lg border border-transparent bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition hover:border-neutral-200 hover:shadow dark:bg-neutral-900 dark:text-neutral-100 dark:hover:border-neutral-700';

                            const actionName = document.createElement('span');
                            actionName.className = 'uppercase tracking-wide';
                            actionName.textContent = this.actionLabels[action] ?? action;
                            actionLabel.appendChild(actionName);

                            const actionToggle = document.createElement('input');
                            actionToggle.type = 'checkbox';
                            actionToggle.className = 'h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500 dark:border-neutral-600';
                            actionToggle.checked = Boolean(this.currentPermissions[node.key]?.actions?.[action]);
                            actionToggle.disabled = !this.selectedUserId || !this.isAllowed(node.key) || this.isLoading;
                            actionToggle.addEventListener('change', () => {
                                this.toggleAction(node.key, action);
                                this.renderTree();
                                this.updateUiState();
                            });

                            actionLabel.appendChild(actionToggle);
                            actionsContainer.appendChild(actionLabel);
                        });

                        wrapper.appendChild(actionsContainer);
                    }

                    if (Array.isArray(node.children) && node.children.length) {
                        const childrenContainer = document.createElement('div');
                        childrenContainer.className = 'mt-4 space-y-3 border-s-4 border-dashed border-neutral-200 ps-4 dark:border-neutral-700';
                        node.children.forEach((child) => {
                            const childElement = this.renderNode(child);
                            if (childElement) {
                                childrenContainer.appendChild(childElement);
                            }
                        });
                        wrapper.appendChild(childrenContainer);
                    }

                    return wrapper;
                }

                updateUiState() {
                    if (this.selectedUser) {
                        this.userInfo.classList.remove('hidden');
                        this.userName.textContent = this.selectedUser.name ?? '';
                        this.userUsername.textContent = this.selectedUser.username ?? '';
                    } else {
                        this.userInfo.classList.add('hidden');
                        this.userName.textContent = '';
                        this.userUsername.textContent = '';
                    }

                    if (this.userOverlay) {
                        const shouldShowOverlay = !this.selectedUserId;
                        this.userOverlay.classList.toggle('hidden', !shouldShowOverlay);
                    }

                    if (this.treeWrapper) {
                        const shouldDimTree = !this.selectedUserId;
                        this.treeWrapper.classList.toggle('opacity-50', shouldDimTree);
                        this.treeWrapper.classList.toggle('pointer-events-none', shouldDimTree);
                    }

                    this.updateButtonStates();
                }

                updateButtonStates() {
                    const disableActions = !this.selectedUserId || this.isLoading;

                    this.saveButton.disabled = disableActions || this.isSaving;
                    this.resetButton.disabled = disableActions || this.isResetting;

                    if (this.saveLabel && this.saveProgress) {
                        this.saveLabel.classList.toggle('hidden', this.isSaving);
                        this.saveProgress.classList.toggle('hidden', !this.isSaving);
                    }

                    if (this.resetLabel && this.resetProgress) {
                        this.resetLabel.classList.toggle('hidden', this.isResetting);
                        this.resetProgress.classList.toggle('hidden', !this.isResetting);
                    }
                }

                setStatus(type = null, message = '') {
                    if (!this.statusEl) {
                        return;
                    }

                    if (!type || !message) {
                        this.statusEl.className = 'hidden rounded-2xl border px-4 py-3 text-sm';
                        this.statusEl.textContent = '';
                        return;
                    }

                    this.statusEl.className = 'rounded-2xl border px-4 py-3 text-sm';

                    if (type === 'success') {
                        this.statusEl.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-900', 'dark:border-emerald-700', 'dark:bg-emerald-900/40', 'dark:text-emerald-100');
                    } else {
                        this.statusEl.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700', 'dark:border-rose-700', 'dark:bg-rose-900/40', 'dark:text-rose-100');
                    }

                    this.statusEl.textContent = message;
                }

                async saveAccess() {
                    if (!this.selectedUserId || this.isSaving || this.isLoading) {
                        return;
                    }

                    this.isSaving = true;
                    this.setStatus();
                    this.updateButtonStates();

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
                        this.setStatus('success', data.message ?? '{{ __('Konfigurasi berhasil disimpan.') }}');
                    } catch (error) {
                        this.setStatus('error', '{{ __('Tidak dapat menyimpan konfigurasi hak akses. Silakan coba lagi.') }}');
                    } finally {
                        this.isSaving = false;
                        this.updateButtonStates();
                    }
                }

                async resetAccess() {
                    if (!this.selectedUserId || this.isResetting || this.isLoading) {
                        return;
                    }

                    const confirmation = window.confirm('{{ __('Apakah Anda yakin ingin mereset semua hak akses untuk user ini?') }}');
                    if (!confirmation) {
                        return;
                    }

                    this.isResetting = true;
                    this.setStatus();
                    this.updateButtonStates();

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
                        this.currentPermissions = this.clone(data.permissions ?? this.defaults);
                        this.prepareAllNodes();
                        this.renderTree();
                        this.setStatus('success', data.message ?? '{{ __('Hak akses telah direset ke pengaturan awal.') }}');
                    } catch (error) {
                        this.setStatus('error', '{{ __('Gagal mereset hak akses. Silakan coba lagi.') }}');
                    } finally {
                        this.isResetting = false;
                        this.updateButtonStates();
                    }
                }
            }

            new AccessManager(root);
        })();
    </script>
</x-layouts.app>
