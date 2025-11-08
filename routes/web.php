<?php

use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\NasabahController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Route::prefix('gadai')
        ->as('gadai.')
        ->group(function () {
            Route::view('pemberian-kredit', 'gadai.pemberian-kredit')->name('pemberian-kredit');
            Route::view('lihat-gadai', 'gadai.lihat-gadai')->name('lihat-gadai');
            Route::view('lihat-data-lelang', 'gadai.lihat-data-lelang')->name('lihat-data-lelang');
        });

    Route::prefix('cicil-emas')
        ->as('cicil-emas.')
        ->group(function () {
            Route::view('transaksi-emas', 'cicil-emas.transaksi-emas')->name('transaksi-emas');
            Route::view('daftar-cicilan', 'cicil-emas.daftar-cicilan')->name('daftar-cicilan');
        });

    Route::prefix('jual-emas')
        ->as('jual-emas.')
        ->group(function () {
            Route::view('transaksi-penjualan', 'jual-emas.transaksi-penjualan')->name('transaksi-penjualan');
            Route::view('lihat-penjualan', 'jual-emas.lihat-penjualan')->name('lihat-penjualan');
            Route::view('batal-penjualan', 'jual-emas.batal-penjualan')->name('batal-penjualan');
        });

    Route::prefix('beli-emas')
        ->as('beli-emas.')
        ->group(function () {
            Route::view('transaksi-pembelian', 'beli-emas.transaksi-pembelian')->name('transaksi-pembelian');
            Route::view('lihat-pembelian', 'beli-emas.lihat-pembelian')->name('lihat-pembelian');
            Route::view('batal-pembelian', 'beli-emas.batal-pembelian')->name('batal-pembelian');
        });

    Route::prefix('titip-emas')
        ->as('titip-emas.')
        ->group(function () {
            Route::view('transaksi-titip-emas', 'titip-emas.transaksi-titip-emas')->name('transaksi-titip-emas');
            Route::view('lihat-titipan', 'titip-emas.lihat-titipan')->name('lihat-titipan');
        });

    Route::prefix('nasabah')
        ->as('nasabah.')
        ->group(function () {
            Route::get('tambah-nasabah', [NasabahController::class, 'create'])->name('tambah-nasabah');
            Route::get('data-nasabah', [NasabahController::class, 'index'])->name('data-nasabah');
            Route::post('data-nasabah', [NasabahController::class, 'store'])->name('data-nasabah.store');
            Route::view('cdd-nasabah', 'nasabah.cdd-nasabah')->name('cdd-nasabah');
            Route::get('nasabah-baru', [NasabahController::class, 'nasabahBaru'])->name('nasabah-baru');
            Route::view('lihat-transaksi-nasabah', 'nasabah.lihat-transaksi-nasabah')->name('lihat-transaksi-nasabah');
            Route::get('{nasabah}/edit', [NasabahController::class, 'edit'])->name('edit');
            Route::put('{nasabah}', [NasabahController::class, 'update'])->name('update');
            Route::delete('{nasabah}', [NasabahController::class, 'destroy'])->name('destroy');
        });

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::middleware(['admin'])
        ->prefix('admin')
        ->as('admin.')
        ->group(function () {
            Route::view('/', 'admin.dashboard')->name('dashboard');

            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

            Route::view('pages', 'admin.pages.index')->name('pages.index');

            Route::controller(UserAccessController::class)
                ->prefix('access')
                ->name('access.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('users/{user}', 'show')->name('show');
                    Route::post('users/{user}', 'update')->name('update');
                    Route::delete('users/{user}', 'reset')->name('reset');
                });
        });

    Route::get('welcome-user', function () {
        $user = Auth::user();

        if ($user?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('user.welcome', ['user' => $user]);
    })->name('user.welcome');
});
