<?php

namespace App\Http\Controllers;

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

        $totalPrice = (float) $package['berat_gram'] * (float) $package['price_per_gram'];
        $downPayment = round($totalPrice * (float) ($option['dp_percentage'] ?? 0), 2);
        $tenor = (int) ($option['tenor'] ?? $validated['tenor_bulan']);
        $installment = $tenor > 0
            ? round(max($totalPrice - $downPayment, 0) / $tenor, 2)
            : 0.0;

        $nasabah = Nasabah::find($validated['nasabah_id']);

        $transaction = CicilEmasTransaction::create([
            'nasabah_id' => $nasabah?->id,
            'package_id' => $package['id'],
            'pabrikan' => $package['pabrikan'],
            'berat_gram' => $package['berat_gram'],
            'kadar' => $package['kadar'],
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
                'paket' => $package['pabrikan'].' • '.$package['berat_gram'].' gr • '.$package['kadar'],
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
        return collect(config('cicil_emas.packages', []))
            ->mapWithKeys(function (array $package, int $index) {
                $package['id'] = $package['id'] ?? 'package-'.($index + 1);
                $package['options'] = collect($package['options'] ?? [])
                    ->map(function (array $option, int $optionIndex) use ($package) {
                        $option['id'] = $option['id'] ?? $package['id'].'-option-'.($optionIndex + 1);

                        return $option;
                    })
                    ->values()
                    ->all();

                return [$package['id'] => $package];
            });
    }
}
