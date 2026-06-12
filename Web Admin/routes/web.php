<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\NasabahController;
use App\Http\Controllers\Web\Admin\TabunganController;
use App\Http\Controllers\Web\Admin\PenarikanController;
use App\Http\Controllers\Web\Admin\JenisSampahController;
use App\Http\Controllers\Web\Admin\LaporanController;
use App\Http\Controllers\Web\Admin\ArtikelController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\AdminManagementController;
use App\Http\Controllers\Web\Admin\NotificationController;
use App\Http\Controllers\Web\Admin\StokSampahController;

// ====== LANDING PAGE PUBLIK =====
Route::get('/', [LandingController::class, 'index'])->name('beranda');
Route::get('/baca/{slug}', [LandingController::class, 'bacaArtikel'])->name('publik.artikel.baca');
Route::get('/stok-tersedia', [LandingController::class, 'stokTersedia'])->name('publik.stok');
Route::get('/stok/{slug}', [LandingController::class, 'detailStok'])->name('publik.stok.detail');


// ====== ROUTE ADMIN ======
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('admin.auth')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // NOTIFIKASI
        Route::prefix('notifikasi')->name('notifikasi.')->controller(NotificationController::class)->group(function () {
            Route::get('/',                'index')->name('index');
            Route::get('/fetch',           'index')->name('fetch');
            Route::post('/read-all',       'markAllAsRead')->name('read-all');
            Route::post('/mark-all-read',  'markAllAsRead')->name('mark-all-read');
            Route::delete('/clear-read',   'destroyRead')->name('clear');
            Route::post('/{id}/read',      'markAsRead')->name('read');
            Route::post('/{id}/mark-read', 'markAsRead')->name('mark-read');
            Route::delete('/{id}',         'destroy')->name('destroy');
        });
        // Alias agar route('admin.notifikasi') tetap jalan di sidebar
        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');

        // Profile Admin
        Route::get('/profile', [AdminDashboardController::class, 'profileShow'])->name('profile');
        Route::put('/profile', [AdminDashboardController::class, 'profileUpdate'])->name('profile.update');
        Route::put('/profile/password', [AdminDashboardController::class, 'profileUpdatePassword'])->name('profile.password');

        // Kelola Admin
        Route::get('/kelola-admin', [AdminManagementController::class, 'index'])->name('kelola-admin.index');
        Route::get('/kelola-admin/create', [AdminManagementController::class, 'create'])->name('kelola-admin.create');
        Route::post('/kelola-admin', [AdminManagementController::class, 'store'])->name('kelola-admin.store');
        Route::get('/kelola-admin/{id}', [AdminManagementController::class, 'show'])->name('kelola-admin.view');
        Route::get('/kelola-admin/{id}/edit', [AdminManagementController::class, 'edit'])->name('kelola-admin.edit');
        Route::put('/kelola-admin/{id}', [AdminManagementController::class, 'update'])->name('kelola-admin.update');
        Route::delete('/kelola-admin/{id}', [AdminManagementController::class, 'destroy'])->name('kelola-admin.destroy');
        Route::post('/kelola-admin/{id}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('kelola-admin.reset-password');
        Route::post('/kelola-admin/{id}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('kelola-admin.toggle-status');

        // Artikel
        Route::get('/artikels',                              [ArtikelController::class, 'index'])->name('artikels.index');
        Route::get('/artikels/create',                       [ArtikelController::class, 'create'])->name('artikels.create');
        Route::post('/artikels',                             [ArtikelController::class, 'store'])->name('artikels.store');
        Route::get('/artikels/{artikel}',                    [ArtikelController::class, 'show'])->name('artikels.show');
        Route::get('/artikels/{artikel}/edit',               [ArtikelController::class, 'edit'])->name('artikels.edit');
        Route::put('/artikels/{artikel}',                    [ArtikelController::class, 'update'])->name('artikels.update');
        Route::delete('/artikels/{artikel}',                 [ArtikelController::class, 'destroy'])->name('artikels.destroy');
        Route::delete('/artikels/{artikel}/galeri/{galeri}', [ArtikelController::class, 'destroyGaleri'])->name('artikels.galeri.destroy');

        // Nasabah
        Route::get('/nasabah',                      [NasabahController::class, 'index'])->name('nasabah.index');
        Route::get('/nasabah/create',               [NasabahController::class, 'create'])->name('nasabah.create');
        Route::post('/nasabah',                     [NasabahController::class, 'store'])->name('nasabah.store');
        Route::get('/nasabah/{id}',                 [NasabahController::class, 'show'])->name('nasabah.show');
        Route::get('/nasabah/{id}/edit',            [NasabahController::class, 'edit'])->name('nasabah.edit');
        Route::put('/nasabah/{id}',                 [NasabahController::class, 'update'])->name('nasabah.update');
        Route::put('/nasabah/{id}/verifikasi',      [NasabahController::class, 'verifikasi'])->name('nasabah.verifikasi');
        Route::put('/nasabah/{id}/nonaktifkan',     [NasabahController::class, 'nonaktifkan'])->name('nasabah.nonaktifkan');
        Route::put('/nasabah/{id}/aktifkan',        [NasabahController::class, 'aktifkan'])->name('nasabah.aktifkan');
        Route::post('/nasabah/{id}/reset-password', [NasabahController::class, 'resetPassword'])->name('nasabah.reset-password');
        Route::get('/nasabah/{id}/kartu-tabungan',  [LaporanController::class, 'kartuTabungan'])->name('nasabah.kartu-tabungan');

        // Tabungan
        Route::get('/tabungan',           [TabunganController::class, 'index'])->name('tabungan.index');
        Route::get('/tabungan/create',    [TabunganController::class, 'create'])->name('tabungan.create');
        Route::post('/tabungan',          [TabunganController::class, 'store'])->name('tabungan.store');
        Route::get('/tabungan/{id}',      [TabunganController::class, 'show'])->name('tabungan.show');
        Route::get('/tabungan/{id}/edit', [TabunganController::class, 'edit'])->name('tabungan.edit');
        Route::put('/tabungan/{id}',      [TabunganController::class, 'update'])->name('tabungan.update');
        Route::delete('/tabungan/{id}',   [TabunganController::class, 'destroy'])->name('tabungan.destroy');
        Route::get('/tabungan/{id}/pdf', [TabunganController::class, 'downloadPdf'])->name('tabungan.pdf');

        // Penarikan
        Route::get('/penarikan',              [PenarikanController::class, 'index'])->name('penarikan.index');
        Route::get('/penarikan/{id}',         [PenarikanController::class, 'show'])->name('penarikan.show');
        Route::put('/penarikan/{id}/setujui', [PenarikanController::class, 'setujui'])->name('penarikan.setujui');
        Route::put('/penarikan/{id}/selesai', [PenarikanController::class, 'selesai'])->name('penarikan.selesai');
        Route::put('/penarikan/{id}/tolak',   [PenarikanController::class, 'tolak'])->name('penarikan.tolak');

        // Jenis Sampah
        Route::get('/jenis-sampah',              [JenisSampahController::class, 'index'])->name('jenis-sampah.index');
        Route::get('/jenis-sampah/create',       [JenisSampahController::class, 'create'])->name('jenis-sampah.create');
        Route::post('/jenis-sampah',             [JenisSampahController::class, 'store'])->name('jenis-sampah.store');
        Route::get('/jenis-sampah/{id}',         [JenisSampahController::class, 'show'])->name('jenis-sampah.show');
        Route::get('/jenis-sampah/{id}/edit',    [JenisSampahController::class, 'edit'])->name('jenis-sampah.edit');
        Route::put('/jenis-sampah/{id}',         [JenisSampahController::class, 'update'])->name('jenis-sampah.update');
        Route::put('/jenis-sampah/{id}/harga',   [JenisSampahController::class, 'updateHarga'])->name('jenis-sampah.harga');
        Route::put('/jenis-sampah/{id}/toggle',  [JenisSampahController::class, 'toggleStatus'])->name('jenis-sampah.toggle');
        Route::delete('/jenis-sampah/{id}',      [JenisSampahController::class, 'destroy'])->name('jenis-sampah.destroy');

        // Stok Sampah
        Route::get('/stok-sampah',              [StokSampahController::class, 'index'])->name('stok-sampah.index');
        Route::get('/stok-sampah/create',       [StokSampahController::class, 'create'])->name('stok-sampah.create');
        Route::post('/stok-sampah',             [StokSampahController::class, 'store'])->name('stok-sampah.store');
        Route::get('/stok-sampah/{id}',         [StokSampahController::class, 'show'])->name('stok-sampah.show');
        Route::get('/stok-sampah/{id}/edit',   [StokSampahController::class, 'edit'])->name('stok-sampah.edit');
        Route::put('/stok-sampah/{id}',          [StokSampahController::class, 'update'])->name('stok-sampah.update');
        Route::post('/stok-sampah/{id}/jual',   [StokSampahController::class, 'prosesJual'])->name('stok-sampah.jual');
        Route::post('/stok-sampah/{id}/toggle-publish', [StokSampahController::class, 'togglePublish'])->name('stok-sampah.toggle-publish');
        Route::post('/stok-sampah/{id}/toggle-press',   [StokSampahController::class, 'togglePress'])->name('stok-sampah.toggle-press');
        Route::delete('/stok-sampah/{id}',      [StokSampahController::class, 'destroy'])->name('stok-sampah.destroy');

        // Laporan
        Route::get('/laporan',                     [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/pdf-tabungan',        [LaporanController::class, 'exportPdfTabungan'])->name('laporan.pdf-tabungan');
        Route::get('/laporan/pdf-penarikan',       [LaporanController::class, 'exportPdfPenarikan'])->name('laporan.pdf-penarikan');
        Route::get('/laporan/pdf-nasabah',         [LaporanController::class, 'exportPdfNasabah'])->name('laporan.pdf-nasabah');
        Route::get('/laporan/pdf-bulanan',         [LaporanController::class, 'laporanBulanan'])->name('laporan.pdf-bulanan');
        Route::get('/laporan/pdf-tahunan',         [LaporanController::class, 'rekapTahunan'])->name('laporan.pdf-tahunan');
        Route::get('/laporan/kartu-tabungan/{id}', [LaporanController::class, 'kartuTabungan'])->name('laporan.kartu-tabungan');
        Route::get('/laporan/excel-tabungan',      [LaporanController::class, 'exportExcelTabungan'])->name('laporan.excel-tabungan');
        Route::get('/laporan/excel-penarikan',     [LaporanController::class, 'exportExcelPenarikan'])->name('laporan.excel-penarikan');
        Route::get('/laporan/excel-nasabah',       [LaporanController::class, 'exportExcelNasabah'])->name('laporan.excel-nasabah');
        Route::get('/laporan/excel-tahunan',       [LaporanController::class, 'exportExcelTahunan'])->name('laporan.excel-tahunan');
        Route::get('/laporan/excel-auditlog',      [LaporanController::class, 'exportExcelAuditLog'])->name('laporan.excel-auditlog');
        Route::get('/laporan/pdf-harian',          [LaporanController::class, 'laporanHarian'])->name('laporan.pdf-harian');
        Route::get('/laporan/pdf-mingguan',        [LaporanController::class, 'laporanMingguan'])->name('laporan.pdf-mingguan');
        Route::get('/laporan/excel-harian',        [LaporanController::class, 'exportExcelHarian'])->name('laporan.excel-harian');
        Route::get('/laporan/excel-mingguan',      [LaporanController::class, 'exportExcelMingguan'])->name('laporan.excel-mingguan');
        Route::get('/laporan/excel-bulanan',       [LaporanController::class, 'exportExcelBulanan'])->name('laporan.excel-bulanan');

    });
});
