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

        return view('cicil-emas.transaksi-emas', [
            'packages' => $packages,
            'nasabahs' => $nasabahs,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $packages = $this->availablePackages();

        $validated = $request->validate([
            'nasabah_id' => ['required', 'exists:nasabahs,id'],
            'package_id' => ['required', Rule::in($packages->keys()->all())],
            'option_id' => ['required', 'string'],
            'estimasi_uang_muka' => ['required', 'numeric', 'min:0'],
            'tenor_bulan' => ['required', 'integer', 'min:1'],
            'besaran_angsuran' => ['required', 'numeric', 'min:0'],
        ]);

        $package = $packages->get($validated['package_id']);
        $option = collect($package['options'] ?? [])->firstWhere('id', $validated['option_id']);

        if (! $option) {
            throw ValidationException::withMessages([
                'option_id' => __('Pilihan kombinasi DP & tenor tidak valid untuk paket yang dipilih.'),
            ]);
        }

        $totalPrice = (float) ($package['harga'] ?? 0);
        $downPayment = round($totalPrice * (float) ($option['dp_percentage'] ?? 0), 2);
        $tenor = (int) ($option['tenor'] ?? $validated['tenor_bulan']);
        $installment = $tenor > 0
            ? round(max($totalPrice - $downPayment, 0) / $tenor, 2)
            : 0.0;

        $nasabah = Nasabah::find($validated['nasabah_id']);

        $transaction = CicilEmasTransaction::create([
            'nasabah_id' => $nasabah?->id,
            'package_id' => $package['id'],
            'pabrikan' => $package['nama_barang'],
            'berat_gram' => $package['berat'],
            'kadar' => $package['kode_group'] ?? $package['kode_intern'],
            'harga_emas' => $totalPrice,
            'dp_percentage' => round((float) ($option['dp_percentage'] ?? 0) * 100, 2),
            'estimasi_uang_muka' => $downPayment,
            'tenor_bulan' => $tenor,
            'besaran_angsuran' => $installment,
            'option_id' => $option['id'],
            'option_label' => $option['label'] ?? null,
        ]);

        return redirect()
            ->route('cicil-emas.transaksi-emas')
            ->with('status', __('Simulasi cicil emas berhasil disimpan.'))
            ->with('transaction_summary', [
                'nasabah' => $nasabah?->nama,
                'kode_member' => $nasabah?->kode_member,
                'paket' => $package['nama_barang'].' • '.number_format((float) $package['berat'], 3, ',', '.').' gr • '.($package['kode_group'] ?? $package['kode_intern']),
                'kombinasi' => $option['label'] ?? null,
                'dp' => $downPayment,
                'tenor' => $tenor,
                'angsuran' => $installment,
                'total' => $totalPrice,
                'transaksi_id' => $transaction->id,
            ]);
    }

    private function availablePackages(): Collection
    {
        $defaultOptions = collect(config('cicil_emas.default_options', []));

        return Barang::orderBy('nama_barang')
            ->get(['id', 'kode_barcode', 'nama_barang', 'kode_intern', 'kode_group', 'berat', 'harga'])
            ->mapWithKeys(function (Barang $barang) use ($defaultOptions) {
                $packageId = 'barang-'.$barang->id;
                $options = $defaultOptions
                    ->map(function (array $option, int $index) use ($packageId) {
                        $option['id'] = ! empty($option['id']) ? $packageId.'-'.$option['id'] : $packageId.'-option-'.($index + 1);
                        $option['label'] = $option['label']
                            ?? sprintf('DP %s%% • Tenor %s bulan', round(($option['dp_percentage'] ?? 0) * 100), $option['tenor'] ?? '—');

                        return $option;
                    })
                    ->values()
                    ->all();

                return [$packageId => [
                    'id' => $packageId,
                    'barang_id' => $barang->id,
                    'kode_barcode' => $barang->kode_barcode,
                    'nama_barang' => $barang->nama_barang,
                    'kode_intern' => $barang->kode_intern,
                    'kode_group' => $barang->kode_group,
                    'berat' => $barang->berat,
                    'harga' => $barang->harga,
                    'options' => $options,
                ]];
            });
    }
}
