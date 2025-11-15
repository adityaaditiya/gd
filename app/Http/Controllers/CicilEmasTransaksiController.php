<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Models\CicilEmasTransactionItem;
use App\Models\Nasabah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CicilEmasTransaksiController extends Controller
{
    public function index(): View
    {
        $transactions = CicilEmasTransaction::with([
                'nasabah',
                'items',
                'installments' => fn ($query) => $query->orderBy('due_date'),
            ])
            ->latest()
            ->get();

        return view('cicil-emas.daftar-cicilan', [
            'transactions' => $transactions,
        ]);
    }

    public function create(): View
    {
        $oldPackageIds = collect(session()->getOldInput('package_ids', []))
            ->map(fn ($value) => $this->normalizePackageId($value))
            ->filter(fn ($value) => $value !== null)
            ->unique()
            ->values();

        $usedBarangIds = CicilEmasTransactionItem::query()
            ->whereNotNull('barang_id')
            ->whereHas('transaction')
            ->pluck('barang_id')
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();

        $packagesQuery = Barang::query()
            ->orderBy('nama_barang');

        if ($usedBarangIds->isNotEmpty()) {
            $packagesQuery->whereNotIn('id', $usedBarangIds->all());
        }

        $packages = $packagesQuery
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_baki', 'kode_jenis', 'berat', 'harga'])
            ->map(fn (Barang $barang) => $this->transformBarang($barang));

        $missingOldPackageIds = $oldPackageIds->reject(function ($id) use ($packages) {
            return $packages->contains(fn ($pkg) => (int) ($pkg['barang_id'] ?? 0) === $id);
        });

        if ($missingOldPackageIds->isNotEmpty()) {
            $additionalPackages = Barang::query()
                ->whereIn('id', $missingOldPackageIds->all())
                ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_baki', 'kode_jenis', 'berat', 'harga'])
                ->map(fn (Barang $barang) => $this->transformBarang($barang));

            $packages = $packages
                ->concat($additionalPackages)
                ->unique(fn ($pkg) => $pkg['barang_id'])
                ->values();
        } else {
            $packages = $packages->values();
        }

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
            ->map(fn ($value) => $this->normalizePackageId($value))
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
            'package_ids.*' => ['distinct', 'integer', 'exists:barangs,id'],
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

            if ($normalizedPackageIds->isNotEmpty()) {
                $conflictingItems = CicilEmasTransactionItem::query()
                    ->whereNotNull('barang_id')
                    ->whereIn('barang_id', $normalizedPackageIds->all())
                    ->whereHas('transaction')
                    ->exists();

                if ($conflictingItems) {
                    $validator->errors()->add(
                        'package_ids',
                        __('Beberapa barang yang dipilih sudah digunakan dalam transaksi cicil emas lainnya.'),
                    );
                }
            }
        });

        $validated = $validator->validate();

        $selectedPackageIds = collect($validated['package_ids'] ?? [])
            ->map(fn ($id) => $this->normalizePackageId($id))
            ->filter(fn ($id) => $id !== null && $packages->has((string) $id))
            ->unique()
            ->values();

        if ($selectedPackageIds->isEmpty()) {
            throw ValidationException::withMessages([
                'package_ids' => __('Silakan pilih minimal satu barang.'),
            ]);
        }

        $selectedPackages = $selectedPackageIds->map(fn ($id) => $packages->get((string) $id));

        $totalPrice = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['harga'] ?? 0));
        $totalWeight = (float) $selectedPackages->sum(fn ($pkg) => (float) ($pkg['berat'] ?? 0));
        $kadarValues = $selectedPackages
            ->map(fn ($pkg) => $pkg['kode_baki'] ?? $pkg['kode_intern'])
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
            ? ($primaryPackage['kode_baki'] ?? $primaryPackage['kode_intern'] ?? '—')
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

        $optionLabel = __('DP Rp :dp (:percent%) • Tenor :tenor', [
            'dp' => number_format($downPayment, 0, ',', '.'),
            'percent' => number_format($dpPercentage, 2, ',', '.'),
            'tenor' => $tenorLabel,
        ]);

        $itemsPayload = $selectedPackages->map(function ($pkg) {
            return [
                'barang_id' => $pkg['barang_id'] ?? null,
                'kode_barcode' => $pkg['kode_barcode'] ?? null,
                'nama_barang' => $pkg['nama_barang'] ?? __('Barang'),
                'kode_intern' => $pkg['kode_intern'] ?? null,
                'kode_baki' => $pkg['kode_baki'] ?? null,
                'berat' => (float) ($pkg['berat'] ?? 0),
                'harga' => (float) ($pkg['harga'] ?? 0),
            ];
        })->all();

        $transaction = DB::transaction(function () use (
            $nasabah,
            $packageId,
            $pabrikanLabel,
            $totalWeight,
            $kadarLabel,
            $totalPrice,
            $dpPercentage,
            $downPayment,
            $principalBalance,
            $marginPercentage,
            $marginAmount,
            $administrationFee,
            $totalFinanced,
            $tenor,
            $installment,
            $optionLabel,
            $itemsPayload,
            $selectedPackageIds
        ) {
            $now = Carbon::now();
            $transactionNumber = $this->generateCicilanNumber($now);

            if ($selectedPackageIds->isNotEmpty()) {
                $conflictExists = CicilEmasTransactionItem::query()
                    ->whereNotNull('barang_id')
                    ->whereIn('barang_id', $selectedPackageIds->all())
                    ->whereHas('transaction')
                    ->lockForUpdate()
                    ->exists();

                if ($conflictExists) {
                    throw ValidationException::withMessages([
                        'package_ids' => __('Beberapa barang yang dipilih sudah digunakan dalam transaksi cicil emas lainnya.'),
                    ]);
                }
            }

            $transaction = CicilEmasTransaction::create([
                'nomor_cicilan' => $transactionNumber,
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
                'option_label' => $optionLabel,
            ]);

            if (! empty($itemsPayload)) {
                $transaction->items()->createMany($itemsPayload);
            }

            $this->generateInstallments($transaction, $tenor, $installment);

            return $transaction;
        });

        return redirect()
            ->route('cicil-emas.transaksi-emas')
            ->with('status', __('Simulasi cicil emas berhasil disimpan.'))
            ->with('transaction_summary', [
                'nasabah' => $nasabah?->nama,
                'kode_member' => $nasabah?->kode_member,
                'paket' => $selectedPackages->map(function ($pkg) {
                    $label = $pkg['nama_barang'] ?? __('Barang');
                    $code = $pkg['kode_intern'] ?? $pkg['kode_baki'] ?? '—';
                    $barcode = $pkg['kode_barcode'] ?? '—';
                    return $label.' • '.number_format((float) ($pkg['berat'] ?? 0), 3, ',', '.').' gr • '.$code.' • '.$barcode;
                })->implode(PHP_EOL),
                'packages' => $selectedPackages->map(function ($pkg) {
                    return [
                        'nama_barang' => $pkg['nama_barang'] ?? __('Barang'),
                        'kode' => $pkg['kode_intern'] ?? $pkg['kode_baki'],
                        'barcode' => $pkg['kode_barcode'] ?? null,
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
                'nomor_cicilan' => $transaction->nomor_cicilan,
            ]);
    }

    private function generateCicilanNumber(Carbon $date): string
    {
        $date = $date->copy()->startOfDay();
        $prefix = 'GE03';
        $datePart = $date->format('ymd');
        $base = $prefix.$datePart;

        $query = CicilEmasTransaction::query()
            ->whereDate('created_at', $date->toDateString())
            ->where('nomor_cicilan', 'like', $base.'%')
            ->orderByDesc('nomor_cicilan');

        if (DB::transactionLevel() > 0) {
            $query->lockForUpdate();
        }

        $latestNumber = $query->value('nomor_cicilan');

        $sequence = 1;

        if ($latestNumber) {
            $sequencePart = substr($latestNumber, -3);

            if (ctype_digit($sequencePart)) {
                $sequence = (int) $sequencePart + 1;
            }
        }

        return $base.str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    private function packageCollectionForIds($barangIds)
    {
        $ids = collect($barangIds)
            ->map(fn ($value) => $this->normalizePackageId($value))
            ->filter(fn ($value) => $value !== null)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $packages = Barang::query()
            ->whereIn('id', $ids->all())
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_baki', 'kode_jenis', 'berat', 'harga'])
            ->mapWithKeys(fn (Barang $barang) => [
                (string) $barang->id => $this->transformBarang($barang),
            ]);

        $ordered = collect();

        foreach ($ids as $id) {
            $key = (string) $id;
            if ($packages->has($key)) {
                $ordered->put($key, $packages->get($key));
            }
        }

        return $ordered;
    }

    private function transformBarang(Barang $barang): array
    {
        return [
            'id' => (string) $barang->id,
            'barang_id' => $barang->id,
            'kode_barcode' => $barang->kode_barcode,
            'nama_barang' => $barang->nama_barang,
            'kode_intern' => $barang->kode_intern,
            'kode_baki' => $barang->kode_baki,
            'kode_jenis' => $barang->kode_jenis,
            'berat' => $barang->berat,
            'harga' => $barang->harga,
        ];
    }

    private function normalizePackageId($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                return null;
            }

            if (ctype_digit($trimmed)) {
                return (int) $trimmed;
            }
        }

        if (is_numeric($value) && ctype_digit((string) $value)) {
            return (int) $value;
        }

        return null;
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
