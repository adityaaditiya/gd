<?php

use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\Admin\UserController;
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
