<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CicilEmasTransaksiController extends Controller
{
    public function index(): View
    {
        $transactions = CicilEmasTransaction::with(['nasabah', 'items'])
            ->latest()
            ->get();

        return view('cicil-emas.daftar-cicilan', [
            'transactions' => $transactions,
        ]);
    }

    public function create(): View
    {
        $selectedPackageIds = collect(session()->getOldInput('package_ids', []))
            ->map(fn ($value) => $this->decodePackageKey($value))
            ->filter(fn ($value) => $value !== null)
            ->unique()
            ->values();

        $packages = $this->packageCollectionForIds($selectedPackageIds)->values()->all();
        $nasabahs = Nasabah::orderBy('nama')
            ->get(['id', 'nama', 'kode_member']);

        $tenorOptions = collect(config('cicil_emas.tenor_options', []))
            ->filter(fn ($value) => is_numeric($value) && $value > 0)
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('cicil-emas.transaksi-emas', [
            'packages' => $packages,
            'nasabahs' => $nasabahs,
            'defaultDownPayment' => (int) config('cicil_emas.default_down_payment', 1_000_000),
            'defaultDownPaymentPercentage' => (float) config('cicil_emas.default_down_payment_percentage', 10),
            'tenorOptions' => $tenorOptions,
            'marginConfig' => $this->marginConfiguration(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rawPackageIds = collect($request->input('package_ids', []));
        $normalizedPackageIds = $rawPackageIds
            ->map(fn ($value) => $this->decodePackageKey($value))
            ->filter(fn ($value) => $value !== null)
            ->values();

        $packages = $this->packageCollectionForIds($normalizedPackageIds);

        $tenorOptions = collect(config('cicil_emas.tenor_options', []))
            ->filter(fn ($value) => is_numeric($value) && $value > 0)
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();

        $validator = Validator::make($request->all(), [
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'package_ids' => ['required', 'array', 'min:1'],
            'package_ids.*' => ['distinct'],
            'down_payment_mode' => ['required', Rule::in(['nominal', 'percentage'])],
            'estimasi_uang_muka' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::requiredIf(fn () => $request->input('down_payment_mode') === 'nominal'),
            ],
            'down_payment_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
                Rule::requiredIf(fn () => $request->input('down_payment_mode') === 'percentage'),
            ],
            'tenor_bulan' => ['required', 'integer', Rule::in($tenorOptions->all())],
            'besaran_angsuran' => ['required', 'numeric', 'min:0'],
            'administrasi' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validator->after(function ($validator) use ($packages, $normalizedPackageIds, $rawPackageIds) {
            if ($normalizedPackageIds->isEmpty()) {
                $validator->errors()->add('package_ids', __('Silakan pilih minimal satu barang.'));
                return;
            }

            if ($packages->isEmpty()) {
                $validator->errors()->add('package_ids', __('Barang yang dipilih tidak ditemukan.'));
                return;
            }

            if ($packages->count() !== $normalizedPackageIds->unique()->count()) {
                $validator->errors()->add('package_ids', __('Beberapa barang yang dipilih tidak tersedia.'));
            }

            if ($normalizedPackageIds->count() !== $rawPackageIds->count()) {
                $validator->errors()->add('package_ids', __('Format data barang tidak valid.'));
            }
        });

        $validated = $validator->validate();

        $selectedPackageIds = collect($validated['package_ids'] ?? [])
            ->map(fn ($id) => (string) $id)
            ->filter(fn ($id) => $packages->has($id))
            ->unique()
            ->values();

        if ($selectedPackageIds->isEmpty()) {
            throw ValidationException::withMessages([
                'package_ids' => __('Silakan pilih minimal satu barang.'),
            ]);
        }

        $selectedPackages = $selectedPackageIds->map(fn ($id) => $packages->get($id));

        $totalPrice = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['harga'] ?? 0));
        $totalWeight = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['berat'] ?? 0));
        $kadarValues = $selectedPackages
            ->map(fn ($pkg) => $pkg['kode_group'] ?? $pkg['kode_intern'])
            ->filter()
            ->unique()
            ->values();

        $primaryPackage = $selectedPackages->first();
        $packageId = $selectedPackages->count() === 1
            ? $primaryPackage['id']
            : 'bundle-'.Str::uuid()->toString();
        $pabrikanLabel = $selectedPackages->count() === 1
            ? ($primaryPackage['nama_barang'] ?? 'Barang')
            : __('Gabungan :jumlah barang', ['jumlah' => $selectedPackages->count()]);
        $kadarLabel = $selectedPackages->count() === 1
            ? ($primaryPackage['kode_group'] ?? $primaryPackage['kode_intern'] ?? '—')
            : ($kadarValues->isEmpty() ? __('Campuran') : $kadarValues->implode(', '));
        $mode = $validated['down_payment_mode'];
        $downPaymentPercentageInput = (float) ($validated['down_payment_percentage'] ?? 0);
        $downPaymentValueInput = (float) ($validated['estimasi_uang_muka'] ?? 0);

        if ($mode === 'percentage') {
            $downPaymentPercentageInput = max(min($downPaymentPercentageInput, 100), 0);
            $downPayment = $totalPrice > 0
                ? round(($totalPrice * $downPaymentPercentageInput) / 100, 2)
                : 0.0;
        } else {
            $downPayment = $downPaymentValueInput;
        }

        if ($totalPrice > 0) {
            $downPayment = min(max($downPayment, 0), $totalPrice);
        } else {
            $downPayment = max($downPayment, 0);
        }
        $tenor = (int) $validated['tenor_bulan'];
        $administrationFee = round((float) ($validated['administrasi'] ?? 0), 2);

        if ($downPayment < 0) {
            throw ValidationException::withMessages([
                'estimasi_uang_muka' => __('Nilai uang muka tidak valid.'),
            ]);
        }

        $principalBalance = max($totalPrice - $downPayment, 0);
        $marginPercentage = $this->resolveMarginPercentage($tenor);
        $marginAmount = round($principalBalance * ($marginPercentage / 100), 2);
        $totalFinanced = $principalBalance + $marginAmount + $administrationFee;
        $installment = $tenor > 0
            ? round($totalFinanced / $tenor, 2)
            : 0.0;

        $dpPercentage = $totalPrice > 0
            ? round(($downPayment / $totalPrice) * 100, 2)
            : 0.0;

        $tenorLabel = __(':bulan bulan', ['bulan' => $tenor]);

        $nasabah = Nasabah::find($validated['nasabah_id']);

        $transaction = CicilEmasTransaction::create([
            'nasabah_id' => $nasabah?->id,
            'package_id' => $packageId,
            'pabrikan' => $pabrikanLabel,
            'berat_gram' => $totalWeight,
            'kadar' => $kadarLabel,
            'harga_emas' => $totalPrice,
            'dp_percentage' => $dpPercentage,
            'estimasi_uang_muka' => $downPayment,
            'pokok_pembiayaan' => $principalBalance,
            'margin_percentage' => $marginPercentage,
            'margin_amount' => $marginAmount,
            'administrasi' => $administrationFee,
            'total_pembiayaan' => $totalFinanced,
            'tenor_bulan' => $tenor,
            'besaran_angsuran' => $installment,
            'option_id' => 'manual-tenor-'.$tenor,
            'option_label' => __('DP Rp :dp (:percent%) • Tenor :tenor', [
                'dp' => number_format($downPayment, 0, ',', '.'),
                'percent' => number_format($dpPercentage, 2, ',', '.'),
                'tenor' => $tenorLabel,
            ]),
        ]);

        $transaction->items()->createMany($selectedPackages->map(function ($pkg) use ($transaction) {
            return [
                'transaction_id' => $transaction->getKey(),
                'barang_id' => $pkg['barang_id'] ?? null,
                'kode_barcode' => $pkg['kode_barcode'] ?? null,
                'nama_barang' => $pkg['nama_barang'] ?? __('Barang'),
                'kode_intern' => $pkg['kode_intern'] ?? null,
                'kode_group' => $pkg['kode_group'] ?? null,
                'berat' => (float) ($pkg['berat'] ?? 0),
                'harga' => (float) ($pkg['harga'] ?? 0),
            ];
        })->all());

        $this->generateInstallments($transaction, $tenor, $installment);

        return redirect()
            ->route('cicil-emas.transaksi-emas')
            ->with('status', __('Simulasi cicil emas berhasil disimpan.'))
            ->with('transaction_summary', [
                'nasabah' => $nasabah?->nama,
                'kode_member' => $nasabah?->kode_member,
                'paket' => $selectedPackages->map(function ($pkg) {
                    $label = $pkg['nama_barang'] ?? __('Barang');
                    $group = $pkg['kode_group'] ?? $pkg['kode_intern'] ?? '—';
                    return $label.' • '.number_format((float) ($pkg['berat'] ?? 0), 3, ',', '.').' gr • '.$group;
                })->implode(PHP_EOL),
                'packages' => $selectedPackages->map(function ($pkg) {
                    return [
                        'nama_barang' => $pkg['nama_barang'] ?? __('Barang'),
                        'kode' => $pkg['kode_group'] ?? $pkg['kode_intern'],
                        'berat' => (float) ($pkg['berat'] ?? 0),
                        'harga' => (float) ($pkg['harga'] ?? 0),
                    ];
                })->all(),
                'jangka_waktu' => __('Jangka waktu :bulan bulan', ['bulan' => $tenor]),
                'dp' => $downPayment,
                'dp_percentage' => $dpPercentage,
                'tenor' => $tenor,
                'angsuran' => $installment,
                'margin_percentage' => $marginPercentage,
                'margin_amount' => $marginAmount,
                'total_pembiayaan' => $totalFinanced,
                'pokok_pembiayaan' => $principalBalance,
                'administrasi' => $administrationFee,
                'total' => $totalPrice,
                'transaksi_id' => $transaction->id,
            ]);
    }

    public function searchPackages(Request $request): JsonResponse
    {
        $queryInput = (string) $request->input('q', '');
        $query = Str::of($queryInput)->lower()->trim();
        $page = max((int) $request->integer('page', 1), 1);
        $perPage = min(max((int) $request->integer('per_page', 20), 1), 50);
        $exclude = collect($request->input('exclude', []))
            ->map(fn ($value) => $this->decodePackageKey($value))
            ->filter(fn ($value) => $value !== null)
            ->unique()
            ->values();

        $builder = Barang::query()->orderBy('nama_barang');

        if ($query->isNotEmpty()) {
            $term = '%'.$query->value().'%';
            $builder->where(function ($q) use ($term) {
                $q->where('nama_barang', 'like', $term)
                    ->orWhere('kode_barcode', 'like', $term)
                    ->orWhere('kode_intern', 'like', $term)
                    ->orWhere('kode_group', 'like', $term);
            });
        }

        if ($exclude->isNotEmpty()) {
            $builder->whereNotIn('id', $exclude->all());
        }

        $offset = ($page - 1) * $perPage;

        $items = (clone $builder)
            ->skip($offset)
            ->take($perPage + 1)
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga']);

        $hasMore = $items->count() > $perPage;
        $items = $items->take($perPage);

        return response()->json([
            'data' => $items->map(fn (Barang $barang) => $this->transformBarang($barang))->values(),
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'has_more' => $hasMore,
                'query' => $query->value(),
            ],
        ]);
    }

    private function packageCollectionForIds(Collection $barangIds): Collection
    {
        if ($barangIds->isEmpty()) {
            return collect();
        }

        $packages = Barang::query()
            ->whereIn('id', $barangIds->all())
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga'])
            ->mapWithKeys(fn (Barang $barang) => [$this->encodePackageKey($barang->id) => $this->transformBarang($barang)]);

        $ordered = collect();

        foreach ($barangIds as $id) {
            $key = $this->encodePackageKey($id);
            if ($packages->has($key)) {
                $ordered->put($key, $packages->get($key));
            }
        }

        return $ordered;
    }

    private function transformBarang(Barang $barang): array
    {
        $packageId = $this->encodePackageKey($barang->id);

        return [
            'id' => $packageId,
            'barang_id' => $barang->id,
            'kode_barcode' => $barang->kode_barcode,
            'nama_barang' => $barang->nama_barang,
            'kode_intern' => $barang->kode_intern,
            'kode_group' => $barang->kode_group,
            'berat' => $barang->berat,
            'harga' => $barang->harga,
        ];
    }

    private function encodePackageKey(int $id): string
    {
        return 'barang-'.$id;
    }

    private function decodePackageKey($value): ?int
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return null;
        }

        $stringValue = (string) $value;

        if (Str::startsWith($stringValue, 'barang-')) {
            $stringValue = Str::after($stringValue, 'barang-');
        }

        return ctype_digit($stringValue) ? (int) $stringValue : null;
    }

    private function generateInstallments(CicilEmasTransaction $transaction, int $tenor, float $amount): void
    {
        $penaltyRate = (float) config('cicil_emas.late_fee_percentage_per_day', 0.5);
        $baseDate = Carbon::now()->startOfDay();

        for ($sequence = 1; $sequence <= $tenor; $sequence++) {
            $dueDate = $baseDate->copy()->addMonthsNoOverflow($sequence);

            $transaction->installments()->create([
                'sequence' => $sequence,
                'due_date' => $dueDate,
                'amount' => $amount,
                'penalty_rate' => $penaltyRate,
            ]);
        }
    }

    private function marginConfiguration(): array
    {
        $config = config('cicil_emas.margin', []);

        return [
            'default_percentage' => (float) ($config['default_percentage'] ?? 0),
            'tenor_overrides' => collect($config['tenor_overrides'] ?? [])
                ->filter(fn ($value, $key) => is_numeric($key) && is_numeric($value))
                ->mapWithKeys(fn ($value, $key) => [(int) $key => (float) $value])
                ->all(),
        ];
    }

    private function resolveMarginPercentage(int $tenor): float
    {
        $config = $this->marginConfiguration();
        $overrides = $config['tenor_overrides'] ?? [];

        if (array_key_exists($tenor, $overrides)) {
            return (float) $overrides[$tenor];
        }

        return (float) ($config['default_percentage'] ?? 0);
    }
}
