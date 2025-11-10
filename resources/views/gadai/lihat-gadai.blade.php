<x-layouts.app :title="__('Lihat Gadai')">
    <div class="space-y-6">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('Data Transaksi Gadai') }}</h1>
            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                {{ __('Pantau seluruh kontrak gadai yang aktif lengkap dengan detail nasabah, barang jaminan, dan estimasi bunga harian.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm dark:border-emerald-500/60 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex flex-col gap-4 border-b border-neutral-200 p-4 dark:border-neutral-700">
                
                    <!-- <a
                        href="{{ route('gadai.pemberian-kredit') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-emerald-700 hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-emerald-400 dark:bg-emerald-500 dark:hover:border-emerald-300 dark:hover:bg-emerald-400"
                    >
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('Tambah Transaksi Gadai') }}</span>
                    </a> -->
                <form
                    method="GET"
                    action="{{ route('gadai.lihat-gadai') }}"
                    class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between"
                    data-filter-form
                    data-auto-submit="{{ $shouldAutoSubmitFilters ? 'true' : 'false' }}"
                >
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:gap-4">
                        <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Dari') }}</span>
                            <input
                                id="tanggal-dari"
                                name="tanggal_dari"
                                type="date"
                                value="{{ $tanggalDari }}"
                                class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                onchange="this.form.requestSubmit()"
                            />
                        </label>
                        <label class="flex flex-col gap-2 text-sm text-neutral-600 dark:text-neutral-200">
                            <span class="text-xs font-semibold uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Tanggal Sampai') }}</span>
                            <input
                                id="tanggal-sampai"
                                name="tanggal_sampai"
                                type="date"
                                value="{{ $tanggalSampai }}"
                                class="rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-200 dark:focus:border-emerald-400 dark:focus:ring-emerald-500/40"
                                onchange="this.form.requestSubmit()"
                            />
                        </label>
                        <div class="flex flex-col gap-2">
                            <span></span>
                            <span></span>
                            <span></span>
                            @if (!empty($search) || $tanggalDari || $tanggalSampai)
                                <a
                                    href="{{ route('gadai.lihat-gadai', ['per_page' => $perPage]) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-neutral-300 px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500 dark:border-neutral-600 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                >
                                    {{ __('Reset') }}
                                </a>
                            @endif
                            
                        </div>
                    </div>
                    <label class="flex w-full items-center gap-3 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-600 shadow-sm focus-within:border-emerald-500 focus-within:text-neutral-900 focus-within:ring-2 focus-within:ring-emerald-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:focus-within:border-emerald-400 dark:focus-within:text-white dark:focus-within:ring-emerald-500/40" for="search-transaksi">
                        <svg class="size-5 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <div class="flex w-full flex-col">
                            <input
                                id="search-transaksi"
                                name="search"
                                type="search"
                                value="{{ $search ?? '' }}"
                                placeholder="{{ __('Cari No. SBG, nama nasabah, kode member, atau telepon…') }}"
                                class="w-full border-0 bg-transparent p-0 text-sm text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:ring-0 dark:text-white"
                            />
                        </div>
                    </label>
                </form>
            </div>
            <div
                class="overflow-x-auto"
                data-transaksi-gadai-table
            >
                <table class="min-w-full divide-y divide-neutral-200 text-left text-sm text-neutral-700 dark:divide-neutral-700 dark:text-neutral-200">
                <thead class="bg-neutral-50 text-xs uppercase tracking-wide text-neutral-500 dark:bg-neutral-900 dark:text-neutral-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">{{ __('No. SBG') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Nasabah') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Barang Jaminan') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Pinjaman') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tenor') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Bunga Terakumulasi') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Tarif Bunga Harian') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Jatuh Tempo') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Kasir') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                        <th scope="col" class="px-4 py-3 text-center">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($transaksiGadai as $transaksi)
                        <tr class="align-top hover:bg-neutral-50 dark:hover:bg-neutral-700/70">
                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-neutral-900 dark:text-white">
                                {{ $transaksi->no_sbg }}
                                <div class="text-xs font-normal text-neutral-500 dark:text-neutral-300">
                                    {{ __('Tanggal Gadai: :date', ['date' => optional($transaksi->tanggal_gadai)->format('d M Y') ?? '—']) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $transaksi->nasabah?->nama ?? '—' }}</span>
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ $transaksi->nasabah?->kode_member ?? '' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($transaksi->barangJaminan->isEmpty())
                                    <span class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Belum ada barang terhubung') }}</span>
                                @else
                                    <ul class="space-y-1">
                                        @foreach ($transaksi->barangJaminan as $barang)
                                            <li class="rounded-lg bg-neutral-50 px-3 py-2 text-xs text-neutral-700 dark:bg-neutral-900 dark:text-neutral-200">
                                                <div class="font-semibold text-neutral-900 dark:text-white">{{ $barang->jenis_barang }} — {{ $barang->merek }}</div>
                                                <div>Rp {{ number_format((float) $barang->nilai_taksiran, 0, ',', '.') }}</div>
                                                <div class="text-[11px] text-neutral-500 dark:text-neutral-300">{{ __('Kelengkapan:') }} {{ $barang->kelengkapan ?? '—' }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="font-semibold text-emerald-600 dark:text-emerald-300">Rp {{ number_format((float) $transaksi->uang_pinjaman, 0, ',', '.') }}</div>
                                @if ((float) $transaksi->biaya_admin > 0)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-300">{{ __('Biaya admin: Rp :amount', ['amount' => number_format((float) $transaksi->biaya_admin, 0, ',', '.')]) }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3">{{ $transaksi->tenor_hari ? $transaksi->tenor_hari . ' ' . __('hari') : '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3">Rp {{ number_format((float) $transaksi->total_bunga, 0, ',', '.') }}</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ number_format((float) $transaksi->tarif_bunga_harian * 100, 2, ',', '.') }}%</td>
                            <td class="whitespace-nowrap px-4 py-3">{{ optional($transaksi->jatuh_tempo_awal)->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col text-xs text-neutral-600 dark:text-neutral-300">
                                    <span>{{ __('Kasir:') }} {{ $transaksi->kasir?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-neutral-700 dark:bg-neutral-700/60 dark:text-neutral-100">
                                    {{ __($transaksi->status_transaksi ?? 'Tidak diketahui') }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="relative flex justify-center" data-more-container>
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-full border border-neutral-200 bg-white p-2 text-neutral-500 transition hover:border-neutral-300 hover:text-neutral-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:border-neutral-500 dark:hover:text-white"
                                        data-more-toggle
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <span class="sr-only">{{ __('Menu aksi untuk transaksi') }}</span>
                                        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.008M12 12h.008M19 12h.008" />
                                        </svg>
                                    </button>
                                    <div
                                        class="absolute right-0 top-full z-20 mt-2 hidden w-48 rounded-lg border border-neutral-200 bg-white py-1 text-sm shadow-lg dark:border-neutral-600 dark:bg-neutral-900"
                                        data-more-menu
                                        role="menu"
                                    >
                                        <button
                                            type="button"
                                            class="flex w-full items-center gap-2 px-4 py-2 text-left text-neutral-700 transition hover:bg-neutral-50 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                            data-menu-item="cancel"
                                            data-transaksi-id="{{ $transaksi->transaksi_id }}"
                                            {{ $transaksi->status_transaksi === 'Lunas' ? 'disabled' : '' }}
                                            role="menuitem"
                                        >
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>{{ __('Batal Gadai') }}</span>
                                        </button>
                                        <a
                                            href="{{ route('laporan.pelunasan-gadai', ['search' => $transaksi->no_sbg]) }}"
                                            class="flex items-center gap-2 px-4 py-2 text-neutral-700 transition hover:bg-neutral-50 focus:outline-none dark:text-neutral-200 dark:hover:bg-neutral-700/60"
                                            data-menu-item="pelunasan"
                                            role="menuitem"
                                        >
                                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 .75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>{{ __('Pelunasan Gadai') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('Belum ada transaksi gadai yang tersimpan.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            <div class="border-t border-neutral-200 bg-neutral-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-900">
                <x-table.pagination-controls
                    :paginator="$transaksiGadai"
                    :per-page="$perPage"
                    :per-page-options="$perPageOptions"
                    :form-action="route('gadai.lihat-gadai')"
                />
            </div>
        </div>
    </div>

    @once
<script data-navigate-once>
  window.KRESNO = window.KRESNO || {};
  // Guard agar listener tabel tidak terpasang dua kali
  if (!window.KRESNO.lihatGadaiBound) {
    document.addEventListener('DOMContentLoaded', function () {
      const table = document.querySelector('[data-transaksi-gadai-table]');
      if (!table) return;

      let activeDropdown = null;

      const closeDropdown = () => {
        if (!activeDropdown) return;
        const { menu, toggle } = activeDropdown;
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        activeDropdown = null;
      };

      table.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-more-toggle]');
        if (toggle) {
          event.preventDefault();
          const container = toggle.closest('[data-more-container]');
          if (!container) return;

          const menu = container.querySelector('[data-more-menu]');
          if (!menu) return;

          if (activeDropdown && activeDropdown.menu === menu) {
            closeDropdown();
            return;
          }

          closeDropdown();
          menu.classList.remove('hidden');
          toggle.setAttribute('aria-expanded', 'true');
          activeDropdown = { menu, toggle };
          return;
        }

        if (event.target.closest('[data-more-menu]')) return;
        closeDropdown();
      });

      document.addEventListener('click', function (event) {
        if (!activeDropdown) return;
        if (table.contains(event.target)) return;
        closeDropdown();
      });

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeDropdown();
      });
    });

    // Setelah Livewire navigasi, DOM baru → pasang ulang handler sekali
    document.addEventListener('livewire:navigated', function () {
      // biarkan event DOMContentLoaded di atas tidak jalan lagi;
      // untuk rerender Livewire, kita pasang handler manual di sini:
      const table = document.querySelector('[data-transaksi-gadai-table]');
      if (!table) return;

      // Hindari double-bind pada navigasi berikutnya
      if (table.dataset.bound === 'true') return;
      table.dataset.bound = 'true';

      let activeDropdown = null;

      const closeDropdown = () => {
        if (!activeDropdown) return;
        const { menu, toggle } = activeDropdown;
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        activeDropdown = null;
      };

      table.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-more-toggle]');
        if (toggle) {
          event.preventDefault();
          const container = toggle.closest('[data-more-container]');
          if (!container) return;

          const menu = container.querySelector('[data-more-menu]');
          if (!menu) return;

          if (activeDropdown && activeDropdown.menu === menu) {
            closeDropdown();
            return;
          }

          closeDropdown();
          menu.classList.remove('hidden');
          toggle.setAttribute('aria-expanded', 'true');
          activeDropdown = { menu, toggle };
          return;
        }

        if (event.target.closest('[data-more-menu]')) return;
        closeDropdown();
      });

      document.addEventListener('click', function (event) {
        if (!activeDropdown) return;
        if (table.contains(event.target)) return;
        closeDropdown();
      });

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closeDropdown();
      });
    });

    window.KRESNO.lihatGadaiBound = true;
  }
</script>
@endonce

</x-layouts.app>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.querySelector('[data-filter-form]');

            if (filterForm && filterForm.dataset.autoSubmit === 'true') {
                filterForm.requestSubmit();
            }
        });
    </script>
@endpush
