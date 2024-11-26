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
use App\Http\Controllers\PemetaanController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PerhitunganController;
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

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::resource('kotakab', KotaKabController::class);
    Route::resource('kecamatan', KecamatanController::class);

    Route::resource('jenis_bencana', JenisBencanaController::class);
    Route::resource('data_bencana', DataBencanaController::class);
    Route::get('data_bencana/{id_kecamatan}/{tahun}', [DataBencanaController::class, 'show']);
    Route::post('data_bencana/import', [DataBencanaController::class, 'import'])->name('data_bencana.import');
    Route::get('get-kecamatan/{id}', [DataBencanaController::class, 'getKecamatan']);

    Route::get('klasterisasi/hasil', [KlasterisasiController::class, 'index'])->name('klasterisasi.hasil');
    Route::post('klasterisasi/proses', [KlasterisasiController::class, 'prosesKlasterisasi'])->name('klasterisasi.proses');
    Route::get('klasterisasi/detail/{id}', [KlasterisasiController::class, 'show'])->name('klasterisasi.detail');
    Route::get('klasterisasi/fetch', [KlasterisasiController::class, 'fetchData'])->name('klasterisasi.fetch');

    Route::get('klasterisasi/perhitungan', [PerhitunganController::class, 'index']);
    Route::get('klasterisasi/perhitungan/{tahun}/{iteration}', [PerhitunganController::class, 'perhitungan'])->name('klasterisasi.perhitungan');

    Route::get('pemetaan', [PemetaanController::class, 'showMap'])->name('klasterisasi.pemetaan');
    Route::get('pemetaan/fetch', [PemetaanController::class, 'filterByYear'])->name('map.fetch');
});
