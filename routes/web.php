<?php

use App\Http\Controllers\Admin\MasterKodeGroupController;
use App\Http\Controllers\Admin\MasterPerhitunganGadaiController;
use App\Http\Controllers\Admin\UserAccessController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangJaminanController;
use App\Http\Controllers\CicilEmasInstallmentController;
use App\Http\Controllers\CicilEmasPelunasanController;
use App\Http\Controllers\CicilEmasMonitoringController;
use App\Http\Controllers\CicilEmasTransaksiController;
use App\Http\Controllers\LaporanPelunasanGadaiController;
use App\Http\Controllers\LaporanTransaksiGadaiController;
use App\Http\Controllers\LaporanPembatalanGadaiController;
use App\Http\Controllers\LaporanLelangController;
use App\Http\Controllers\LaporanSaldoKasController;
use App\Http\Controllers\LaporanPerpanjanganGadaiController;
use App\Http\Controllers\LaporanCicilEmasController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\TransaksiGadaiController;
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
            Route::get('pemberian-kredit', [TransaksiGadaiController::class, 'create'])->name('pemberian-kredit');
            Route::post('pemberian-kredit', [TransaksiGadaiController::class, 'store'])->name('transaksi-gadai.store');
            Route::get('lihat-gadai', [TransaksiGadaiController::class, 'index'])->name('lihat-gadai');
            Route::post('transaksi-gadai/{transaksi}/batal', [TransaksiGadaiController::class, 'cancel'])
                ->whereNumber('transaksi')
                ->name('transaksi-gadai.cancel');
            Route::get('transaksi-gadai/{transaksi}/pelunasan', [TransaksiGadaiController::class, 'showSettlementForm'])
                ->whereNumber('transaksi')
                ->name('transaksi-gadai.settle-form');
            Route::post('transaksi-gadai/{transaksi}/pelunasan', [TransaksiGadaiController::class, 'settle'])
                ->whereNumber('transaksi')
                ->name('transaksi-gadai.settle');
            Route::get('transaksi-gadai/{transaksi}/perpanjangan', [TransaksiGadaiController::class, 'showExtensionForm'])
                ->whereNumber('transaksi')
                ->name('transaksi-gadai.extend-form');
            Route::post('transaksi-gadai/{transaksi}/perpanjangan', [TransaksiGadaiController::class, 'extend'])
                ->whereNumber('transaksi')
                ->name('transaksi-gadai.extend');
            Route::delete('transaksi-gadai/{transaksi}/perpanjangan/{perpanjangan}', [TransaksiGadaiController::class, 'cancelExtension'])
                ->whereNumber('transaksi')
                ->whereNumber('perpanjangan')
                ->name('transaksi-gadai.extend-cancel');
            Route::get('lihat-barang-gadai', [BarangJaminanController::class, 'index'])->name('lihat-barang-gadai');
            Route::get('barang-gadai/tambah', [BarangJaminanController::class, 'create'])->name('barang-jaminan.create');
            Route::post('barang-gadai', [BarangJaminanController::class, 'store'])->name('barang-jaminan.store');
            Route::get('barang-gadai/{barangJaminan}/edit', [BarangJaminanController::class, 'edit'])->name('barang-jaminan.edit');
            Route::put('barang-gadai/{barangJaminan}', [BarangJaminanController::class, 'update'])->name('barang-jaminan.update');
            Route::delete('barang-gadai/{barangJaminan}', [BarangJaminanController::class, 'destroy'])->name('barang-jaminan.destroy');
            Route::get('lihat-data-lelang', [LelangController::class, 'index'])->name('lihat-data-lelang');
            Route::put('jadwal-lelang/{jadwalLelang}', [LelangController::class, 'updateSchedule'])
                ->whereNumber('jadwalLelang')
                ->name('jadwal-lelang.update');
            Route::post('jadwal-lelang/{jadwalLelang}/finalisasi', [LelangController::class, 'finalize'])
                ->whereNumber('jadwalLelang')
                ->name('jadwal-lelang.finalize');
        });

    Route::prefix('laporan')
        ->as('laporan.')
        ->group(function () {
            Route::get('saldo-kas', [LaporanSaldoKasController::class, 'index'])->name('saldo-kas');
            Route::get('transaksi-gadai', [LaporanTransaksiGadaiController::class, 'index'])->name('transaksi-gadai');
            Route::get('pelunasan-gadai', [LaporanPelunasanGadaiController::class, 'index'])->name('pelunasan-gadai');
            Route::get('batal-gadai', [LaporanPembatalanGadaiController::class, 'index'])->name('batal-gadai');
            Route::get('perpanjangan-gadai', [LaporanPerpanjanganGadaiController::class, 'index'])->name('perpanjangan-gadai');
            Route::get('lelang', [LaporanLelangController::class, 'index'])->name('lelang');
            Route::get('cicil-emas', [LaporanCicilEmasController::class, 'index'])->name('cicil-emas');
        });

    Route::prefix('akuntansi')
        ->as('akuntansi.')
        ->group(function () {
            Route::view('jurnal', 'akuntansi.jurnal')->name('jurnal');
            Route::view('buku-besar', 'akuntansi.buku-besar')->name('buku-besar');
            Route::view('neraca-percobaan', 'akuntansi.neraca-percobaan')->name('neraca-percobaan');
            Route::view('laba-rugi', 'akuntansi.laba-rugi')->name('laba-rugi');
            Route::view('neraca', 'akuntansi.neraca')->name('neraca');
        });

    Route::prefix('cicil-emas')
        ->as('cicil-emas.')
        ->group(function () {
            Route::get('transaksi-emas', [CicilEmasTransaksiController::class, 'create'])->name('transaksi-emas');
            Route::post('transaksi-emas', [CicilEmasTransaksiController::class, 'store'])->name('transaksi-emas.store');
            Route::get('daftar-cicilan', [CicilEmasTransaksiController::class, 'index'])->name('daftar-cicilan');
            Route::post('transaksi/{transaction}/batal', [CicilEmasTransaksiController::class, 'cancel'])
                ->whereNumber('transaction')
                ->name('transaksi.cancel');
            Route::get('angsuran-rutin', [CicilEmasInstallmentController::class, 'index'])->name('angsuran-rutin');
            Route::post('angsuran-rutin/{installment}/bayar', [CicilEmasInstallmentController::class, 'pay'])
                ->whereNumber('installment')
                ->name('angsuran-rutin.pay');
            Route::post('angsuran-rutin/{installment}/batal', [CicilEmasInstallmentController::class, 'cancelPayment'])
                ->whereNumber('installment')
                ->name('angsuran-rutin.cancel');
            Route::get('riwayat-cicilan', [CicilEmasMonitoringController::class, 'index'])->name('riwayat-cicilan');
            Route::get('pelunasan-cicilan', [CicilEmasPelunasanController::class, 'index'])->name('pelunasan-cicilan');
            Route::post('pelunasan-cicilan', [CicilEmasPelunasanController::class, 'store'])->name('pelunasan-cicilan.store');
            Route::post('pelunasan-cicilan/{transaction}/batal', [CicilEmasPelunasanController::class, 'cancel'])
                ->whereNumber('transaction')
                ->name('pelunasan-cicilan.cancel');
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

    Route::prefix('barang')
        ->as('barang.')
        ->group(function () {
            Route::get('data-barang', [BarangController::class, 'index'])->name('data-barang');
            Route::get('data-barang/tambah', [BarangController::class, 'create'])->name('data-barang.create');
            Route::post('data-barang', [BarangController::class, 'store'])->name('data-barang.store');
            Route::get('data-barang/{barang}/edit', [BarangController::class, 'edit'])
                ->whereNumber('barang')
                ->name('data-barang.edit');
            Route::put('data-barang/{barang}', [BarangController::class, 'update'])
                ->whereNumber('barang')
                ->name('data-barang.update');
            Route::delete('data-barang/{barang}', [BarangController::class, 'destroy'])
                ->whereNumber('barang')
                ->name('data-barang.destroy');
        });

    Route::prefix('nasabah')
        ->as('nasabah.')
        ->group(function () {
            Route::get('tambah-nasabah', [NasabahController::class, 'create'])->name('tambah-nasabah');
            Route::get('data-nasabah', [NasabahController::class, 'index'])->name('data-nasabah');
            Route::post('data-nasabah', [NasabahController::class, 'store'])->name('data-nasabah.store');
            Route::view('cdd-nasabah', 'nasabah.cdd-nasabah')->name('cdd-nasabah');
            Route::get('nasabah-baru', [\App\Http\Controllers\NasabahController::class, 'nasabahBaru'])->name('nasabah-baru');
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

            Route::get('master-kode-group', [MasterKodeGroupController::class, 'index'])->name('master-kode-group.index');
            Route::post('master-kode-group', [MasterKodeGroupController::class, 'store'])->name('master-kode-group.store');
            Route::put('master-kode-group/{masterKodeGroup}', [MasterKodeGroupController::class, 'update'])->name('master-kode-group.update');
            Route::delete('master-kode-group/{masterKodeGroup}', [MasterKodeGroupController::class, 'destroy'])->name('master-kode-group.destroy');

            Route::get('master-perhitungan-gadai', [MasterPerhitunganGadaiController::class, 'index'])->name('master-perhitungan-gadai.index');
            Route::post('master-perhitungan-gadai', [MasterPerhitunganGadaiController::class, 'store'])->name('master-perhitungan-gadai.store');
            Route::put('master-perhitungan-gadai/{masterPerhitunganGadai}', [MasterPerhitunganGadaiController::class, 'update'])->name('master-perhitungan-gadai.update');
            Route::delete('master-perhitungan-gadai/{masterPerhitunganGadai}', [MasterPerhitunganGadaiController::class, 'destroy'])->name('master-perhitungan-gadai.destroy');

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

        if ($user?->hasAdminAccess()) {
            return redirect()->route('admin.dashboard');
        }

        return view('user.welcome', ['user' => $user]);
    })->name('user.welcome');
});
