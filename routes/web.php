<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataBencanaController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\JenisBencanaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\KlasterisasiController;
use App\Http\Controllers\KlasterisasiTestController;
use App\Http\Controllers\KotaKabController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::resource('kotakab', KotaKabController::class);
    Route::resource('kecamatan', KecamatanController::class);

    Route::get('kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::get('kriteria/create', [KriteriaController::class, 'create'])->name('kriteria.create');
    Route::post('kriteria/store', [KriteriaController::class, 'store'])->name('kriteria.store');
    Route::get('kriteria/{kriteria}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::put('kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');

    Route::resource('jenis_bencana', JenisBencanaController::class);
    Route::resource('data_bencana', DataBencanaController::class);
    Route::post('data_bencana/import', [DataBencanaController::class, 'import'])->name('data_bencana.import');
    Route::get('get-kecamatan/{id}', [DataBencanaController::class, 'getKecamatan']);

    Route::get('klasterisasi', [KlasterisasiController::class, 'index']);
    Route::post('klasterisasi/proses', [KlasterisasiController::class, 'prosesKlasterisasi'])->name('klasterisasi.proses');
    Route::get('klasterisasi/detail/{id}', [KlasterisasiController::class, 'show'])->name('klasterisasi.detail');

    // Route::get('export/artikel', [ArtikelController::class, 'export']);
    // Route::get('export/pengguna', [PenggunaController::class, 'export']);
    // Route::get('export/petugas', [PetugasController::class, 'export']);
    // Route::get('export/siswa', [SiswaController::class, 'export']);
    // Route::get('export/kepsek', [KepsekController::class, 'export']);
    // Route::get('export/pengaduan', [PengaduanController::class, 'export']);
    // Route::get('export/single/pengaduan/{id}', [PengaduanController::class, 'export_single']);
    // Route::get('export/feedback', [FeedbackController::class, 'export']);
});
