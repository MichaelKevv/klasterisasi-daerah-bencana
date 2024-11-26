<?php

// app/Http/Controllers/PerhitunganController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerhitunganController extends Controller
{
    public function index()
    {
        // Mengambil daftar tahun yang tersedia
        $tahunList = DB::table('tb_log_iterasi')
            ->select('tahun')
            ->distinct()
            ->get();

        // Mengambil daftar iterasi untuk setiap tahun
        $dataPerTahun = [];

        foreach ($tahunList as $tahun) {
            $iterasiList = DB::table('tb_log_iterasi')
                ->select('iteration')
                ->where('tahun', $tahun->tahun)
                ->distinct()
                ->get();

            // Menyimpan data iterasi untuk setiap tahun
            $dataPerTahun[] = [
                'tahun' => $tahun->tahun,
                'iterasi' => $iterasiList
            ];
        }

        // Mengirimkan data tahun dan iterasi ke view
        return view('klasterisasi.perhitunganindex', compact('dataPerTahun'));
    }

    public function perhitungan($tahun, $iteration)
    {
        $logCentroid = DB::table('tb_log_iterasi')
            ->select('centroid_frekuensi', 'centroid_kerusakan', 'centroid_korban')
            ->where('tahun', $tahun)
            ->where('iteration', $iteration)
            ->where('type', 'centroid')
            ->get();

        $logIterasi = DB::table('tb_log_iterasi')
            ->select('centroid_frekuensi', 'centroid_kerusakan', 'tb_log_iterasi.id_kotakab', 'tb_log_iterasi.id_kecamatan', 'centroid_korban', 'frekuensi_kejadian', 'total_kerusakan', 'total_korban', 'c1', 'c2', 'c3', 'terdekat')
            ->where('tahun', $tahun)
            ->where('iteration', $iteration)  // Jika ingin filter berdasarkan iterasi
            ->join('tb_kecamatan', 'tb_log_iterasi.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->orderBy('tb_kotakab.nama_kotakab', 'asc')  // Mengurutkan berdasarkan nama kota/kabupaten
            ->orderBy('tb_kecamatan.nama_kecamatan', 'asc')  // Mengurutkan berdasarkan nama kecamatan
            ->get();


        // Kirim data ke view
        return view('klasterisasi.perhitungan', compact('logCentroid', 'logIterasi', 'tahun', 'iteration'));
    }
}
