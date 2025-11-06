@php
    $var = $nodeVar ?? 'node';
    $level = $level ?? 0;
@endphp

<div
    class="rounded-2xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
    :class="{
        'bg-neutral-50 dark:bg-neutral-900/80': {{ $var }}.children?.length,
    }"
    x-init="$root.prepareNode({{ $var }}.key)"
>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-1">
            <p class="text-base font-semibold text-neutral-900 dark:text-white" x-text="{{ $var }}.label"></p>
            <template x-if="{{ $var }}.description">
                <p class="text-sm text-neutral-500" x-text="{{ $var }}.description"></p>
            </template>
            <template x-if="{{ $var }}.actions?.length">
                <p class="text-xs font-medium uppercase tracking-wide text-neutral-400 dark:text-neutral-500">
                    {{ __('Memiliki izin lanjutan') }}
                </p>
            </template>
        </div>
        <label class="inline-flex items-center justify-end gap-2 text-sm font-medium text-neutral-600 dark:text-neutral-300">
            <span>{{ __('Izinkan menu') }}</span>
            <input
                type="checkbox"
                class="h-5 w-5 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500 dark:border-neutral-600"
                :checked="$root.isAllowed({{ $var }}.key)"
                @change="$root.togglePermission({{ $var }}.key)"
                :disabled="!$root.selectedUserId || $root.isLoading"
            >
        </label>
    </div>

    <template x-if="{{ $var }}.actions?.length">
        <div class="mt-4 space-y-2 rounded-xl border border-neutral-200 bg-neutral-50 p-3 dark:border-neutral-700 dark:bg-neutral-800">
            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">
                {{ __('Izin Detail Menu') }}
            </p>
            <template x-for="action in {{ $var }}.actions" :key="action">
                <label class="flex items-center justify-between gap-4 rounded-lg border border-transparent bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm transition hover:border-neutral-200 hover:shadow dark:bg-neutral-900 dark:text-neutral-100 dark:hover:border-neutral-700">
                    <span class="uppercase tracking-wide" x-text="$root.actionLabels[action] ?? action"></span>
                    <input
                        type="checkbox"
                        class="h-4 w-4 rounded border-neutral-300 text-indigo-600 focus:ring-indigo-500 dark:border-neutral-600"
                        :checked="$root.currentPermissions[{{ $var }}.key]?.actions?.[action] ?? false"
                        @change="$root.toggleAction({{ $var }}.key, action)"
                        :disabled="!$root.selectedUserId || !$root.isAllowed({{ $var }}.key) || $root.isLoading"
                    >
                </label>
            </template>
        </div>
    </template>

    <template x-if="{{ $var }}.children?.length">
        <div class="mt-4 space-y-3 border-s-4 border-dashed border-neutral-200 ps-4 dark:border-neutral-700">
            <template x-for="child in {{ $var }}.children" :key="child.key">
                @include('admin.access.partials.node', ['level' => $level + 1, 'nodeVar' => 'child'])
            </template>
        </div>
    </template>
</div>
