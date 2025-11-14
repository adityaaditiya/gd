<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Models\Nasabah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
            ->limit(100)
            ->get();

        return view('cicil-emas.daftar-cicilan', [
            'transactions' => $transactions,
        ]);
    }

    public function create(): View
    {
        $packages = Barang::query()
            ->orderBy('nama_barang')
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga'])
            ->map(fn (Barang $barang) => $this->transformBarang($barang))
            ->values();
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
                    $code = $pkg['kode_intern'] ?? $pkg['kode_group'] ?? '—';
                    $barcode = $pkg['kode_barcode'] ?? '—';
                    return $label.' • '.number_format((float) ($pkg['berat'] ?? 0), 3, ',', '.').' gr • '.$code.' • '.$barcode;
                })->implode(PHP_EOL),
                'packages' => $selectedPackages->map(function ($pkg) {
                    return [
                        'nama_barang' => $pkg['nama_barang'] ?? __('Barang'),
                        'kode' => $pkg['kode_intern'] ?? $pkg['kode_group'],
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
            ]);
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
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga'])
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
            'kode_group' => $barang->kode_group,
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

    public function cancel(Request $request, CicilEmasTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_id' => ['required', 'integer', Rule::in([$transaction->getKey()])],
            'alasan_pembatalan' => ['required', 'string', 'max:1000'],
        ], [
            'transaction_id.in' => __('Transaksi cicilan tidak valid.'),
        ], [
            'alasan_pembatalan' => __('Alasan pembatalan'),
        ]);

        if ($transaction->dibatalkan_pada) {
            return redirect()
                ->route('cicil-emas.daftar-cicilan')
                ->withInput($request->all())
                ->withErrors([
                    'alasan_pembatalan' => __('Transaksi cicilan ini sudah dibatalkan sebelumnya.'),
                ]);
        }

        $transaction->loadMissing('installments');

        $hasPayments = $transaction->installments->contains(function ($installment) {
            return ($installment->paid_at !== null)
                || (($installment->paid_amount ?? 0) > 0);
        });

        if ($hasPayments) {
            return redirect()
                ->route('cicil-emas.daftar-cicilan')
                ->withInput($request->all())
                ->withErrors([
                    'alasan_pembatalan' => __('Cicilan tidak dapat dibatalkan karena sudah memiliki riwayat pembayaran.'),
                ]);
        }

        $userId = Auth::id();

        if (! $userId) {
            abort(403, __('Pengguna tidak dikenali.'));
        }

        $reason = trim((string) $validated['alasan_pembatalan']);

        DB::transaction(function () use ($transaction, $userId, $reason) {
            $transaction->update([
                'dibatalkan_pada' => Carbon::now(),
                'dibatalkan_oleh' => $userId,
                'alasan_pembatalan' => $reason,
            ]);
        });

        return redirect()
            ->route('cicil-emas.daftar-cicilan')
            ->with('status', __('Transaksi cicilan berhasil dibatalkan.'));
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
