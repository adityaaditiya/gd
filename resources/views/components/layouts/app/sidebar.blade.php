<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            @php
                $gadaiRoutes = ['gadai.pemberian-kredit', 'gadai.lihat-gadai', 'gadai.lihat-data-lelang'];
                $isGadaiActive = request()->routeIs(...$gadaiRoutes);
                $masterRoutes = ['admin.users.*', 'admin.pages.*'];
                $isMasterActive = request()->routeIs(...$masterRoutes);
            @endphp

            <nav class="flex flex-col gap-3">
                <div>
                    <a
                        href="{{ route('dashboard') }}"
                        wire:navigate
                        @class([
                            'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors duration-200',
                            'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('dashboard'),
                            'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('dashboard'),
                        ])
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
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div>
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                        data-accordion-toggle
                        data-accordion-target="gadai-menu"
                        aria-expanded="{{ $isGadaiActive ? 'true' : 'false' }}"
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
                            <span>{{ __('Gadai') }}</span>
                        </span>
                        <svg
                            data-accordion-icon
                            class="size-4 transform transition-transform duration-300 {{ $isGadaiActive ? 'rotate-90' : '' }}"
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
                        style="max-height: {{ $isGadaiActive ? '500px' : '0px' }};"
                    >
                        <a
                            href="{{ route('gadai.pemberian-kredit') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.pemberian-kredit'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.pemberian-kredit'),
                            ])
                        >
                            {{ __('Pemberian Kredit') }}
                        </a>
                        <a
                            href="{{ route('gadai.lihat-gadai') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-gadai'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-gadai'),
                            ])
                        >
                            {{ __('Lihat Gadai') }}
                        </a>
                        <a
                            href="{{ route('gadai.lihat-data-lelang') }}"
                            wire:navigate
                            @class([
                                'block rounded-lg px-3 py-2 transition-colors duration-200',
                                'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('gadai.lihat-data-lelang'),
                                'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('gadai.lihat-data-lelang'),
                            ])
                        >
                            {{ __('Lihat Data Lelang') }}
                        </a>
                    </div>
                </div>

                @if (auth()->user()?->role === 'admin')
                    <div>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-neutral-700 transition-colors duration-200 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-700 dark:hover:text-white"
                            data-accordion-toggle
                            data-accordion-target="master-menu"
                            aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}"
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
                                <span>{{ __('Master') }}</span>
                            </span>
                            <svg
                                data-accordion-icon
                                class="size-4 transform transition-transform duration-300 {{ $isMasterActive ? 'rotate-90' : '' }}"
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
                            style="max-height: {{ $isMasterActive ? '500px' : '0px' }};"
                        >
                            <a
                                href="{{ route('admin.users.index') }}"
                                wire:navigate
                                @class([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.users.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.users.*'),
                                ])
                            >
                                {{ __('Master User') }}
                            </a>
                            <a
                                href="{{ route('admin.pages.index') }}"
                                wire:navigate
                                @class([
                                    'block rounded-lg px-3 py-2 transition-colors duration-200',
                                    'bg-neutral-200 text-neutral-900 dark:bg-neutral-700 dark:text-white' => request()->routeIs('admin.pages.*'),
                                    'text-neutral-600 hover:bg-neutral-200 hover:text-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:hover:text-white' => !request()->routeIs('admin.pages.*'),
                                ])
                            >
                                {{ __('Halaman Baru') }}
                            </a>
                        </div>
                    </div>
                @endif
            </nav>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <script>
            const initializeAccordion = () => {
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

            document.addEventListener('DOMContentLoaded', initializeAccordion);
            document.addEventListener('livewire:navigated', initializeAccordion);
        </script>

        @fluxScripts
    </body>
</html>
