<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\CicilEmasTransaction;
use App\Models\Nasabah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CicilEmasTransaksiController extends Controller
{
    public function create(): View
    {
        $packages = $this->availablePackages()->values()->all();
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
            'tenorOptions' => $tenorOptions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $packages = $this->availablePackages();
        $tenorOptions = collect(config('cicil_emas.tenor_options', []))
            ->filter(fn ($value) => is_numeric($value) && $value > 0)
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values();

        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'package_id' => ['required', Rule::in($packages->keys()->all())],
            'estimasi_uang_muka' => ['required', 'numeric', 'min:0'],
            'tenor_bulan' => ['required', 'integer', Rule::in($tenorOptions->all())],
            'besaran_angsuran' => ['required', 'numeric', 'min:0'],
        ]);

        $package = $packages->get($validated['package_id']);
        $totalPrice = (float) ($package['harga'] ?? 0);
        $downPayment = min((float) $validated['estimasi_uang_muka'], $totalPrice);
        $tenor = (int) $validated['tenor_bulan'];

        if ($downPayment < 0) {
            throw ValidationException::withMessages([
                'estimasi_uang_muka' => __('Nilai uang muka tidak valid.'),
            ]);
        }

        $remaining = max($totalPrice - $downPayment, 0);
        $installment = $tenor > 0
            ? round($remaining / $tenor, 2)
            : 0.0;

        $dpPercentage = $totalPrice > 0
            ? round(($downPayment / $totalPrice) * 100, 2)
            : 0.0;

        $tenorLabel = __(':bulan bulan', ['bulan' => $tenor]);

        $nasabah = Nasabah::find($validated['nasabah_id']);

        $transaction = CicilEmasTransaction::create([
            'nasabah_id' => $nasabah?->id,
            'package_id' => $package['id'],
            'pabrikan' => $package['nama_barang'],
            'berat_gram' => $package['berat'],
            'kadar' => $package['kode_group'] ?? $package['kode_intern'],
            'harga_emas' => $totalPrice,
            'dp_percentage' => $dpPercentage,
            'estimasi_uang_muka' => $downPayment,
            'tenor_bulan' => $tenor,
            'besaran_angsuran' => $installment,
            'option_id' => 'manual-tenor-'.$tenor,
            'option_label' => __('DP Rp :dp • Tenor :tenor', [
                'dp' => number_format($downPayment, 0, ',', '.'),
                'tenor' => $tenorLabel,
            ]),
        ]);

        return redirect()
            ->route('cicil-emas.transaksi-emas')
            ->with('status', __('Simulasi cicil emas berhasil disimpan.'))
            ->with('transaction_summary', [
                'nasabah' => $nasabah?->nama,
                'kode_member' => $nasabah?->kode_member,
                'paket' => $package['nama_barang'].' • '.number_format((float) $package['berat'], 3, ',', '.').' gr • '.($package['kode_group'] ?? $package['kode_intern']),
                'jangka_waktu' => __('Jangka waktu :bulan bulan', ['bulan' => $tenor]),
                'dp' => $downPayment,
                'tenor' => $tenor,
                'angsuran' => $installment,
                'total' => $totalPrice,
                'transaksi_id' => $transaction->id,
            ]);
    }

    private function availablePackages(): Collection
    {
        return Barang::orderBy('nama_barang')
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga'])
            ->mapWithKeys(function (Barang $barang) {
                $packageId = 'barang-'.$barang->id;

                return [$packageId => [
                    'id' => $packageId,
                    'barang_id' => $barang->id,
                    'kode_barcode' => $barang->kode_barcode,
                    'nama_barang' => $barang->nama_barang,
                    'kode_intern' => $barang->kode_intern,
                    'kode_group' => $barang->kode_group,
                    'berat' => $barang->berat,
                    'harga' => $barang->harga,
                ]];
            });
    }
}
