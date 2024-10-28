<?php

namespace App\Http\Controllers;

use App\Models\TbDataBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KlasterisasiTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Step 1: Ambil dan akumulasi data bencana per kecamatan
        $dataBencana = TbDatabencana::selectRaw('
            tb_kotakab.nama_kotakab as nama_kabupaten,
            tb_kecamatan.nama_kecamatan,
            SUM(tb_databencana.frekuensi_kejadian) as total_frekuensi,
            SUM(tb_databencana.total_kerusakan) as total_kerusakan,
            SUM(tb_databencana.luas_terdampak) as luas_terdampak,
            SUM(tb_databencana.total_korban) as total_korban
        ')
            ->join('tb_kecamatan', 'tb_databencana.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->groupBy('tb_kecamatan.nama_kecamatan', 'tb_kotakab.nama_kotakab')
            ->orderBy('tb_kotakab.nama_kotakab')  // Order by kabupaten
            ->orderBy('tb_kecamatan.nama_kecamatan')  // Then order by kecamatan
            ->get();

        // Step 2: Format data untuk K-Means
        $data = [];
        foreach ($dataBencana as $item) {
            $data[] = [
                'nama_kabupaten' => $item->nama_kabupaten,
                'nama_kecamatan' => $item->nama_kecamatan,
                'total_frekuensi' => $item->total_frekuensi,
                'total_kerusakan' => $item->total_kerusakan,
                'luas_terdampak' => $item->luas_terdampak,
                'total_korban' => $item->total_korban,
            ];
        }

        // Step 3: Tentukan jumlah cluster
        $jumlahCluster = 3;

        // Step 4: Jalankan algoritma K-Means
        $kMeansResult = $this->kMeans($data, $jumlahCluster);

        // Step 5: Extract data from the result
        $clusters = $kMeansResult['clusters'];
        $centroids = $kMeansResult['centroids'];
        $iterationData = $kMeansResult['iterationData'];


        // Step 6: Tampilkan hasil di view
        return view('klasterisasi.proses', compact('clusters', 'centroids', 'iterationData'));
    }

    private function kMeans($data, $k)
    {
        // Step 1: Inisialisasi centroid secara acak
        $centroids = $this->initCentroids($data, $k);

        // Step 2: Mulai iterasi sampai konvergen
        $iterations = 100; // Batas maksimum iterasi
        $iterationData = []; // Initialize array to collect iteration data
        for ($i = 0; $i < $iterations; $i++) {
            $clusters = $this->assignClusters($data, $centroids);

            // Perbarui centroid
            $newCentroids = $this->updateCentroids($data, $clusters, $k);

            // Simpan data iterasi
            $iterationData[] = [
                'iteration' => $i + 1,
                'centroids' => $this->labelCentroids($centroids), // Tambahkan label ke centroids
                'clusters' => $clusters
            ];

            // Cek konvergensi
            if ($centroids == $newCentroids) {
                break;
            }
            $centroids = $newCentroids;
        }

        // Pastikan cluster selalu berurutan dengan label C1, C2, C3
        return [
            'clusters' => $clusters,
            'centroids' => $this->labelCentroids($centroids), // Berikan label pada hasil akhir centroids
            'iterationData' => $iterationData
        ];
    }

    private function labelCentroids($centroids)
    {
        $labeledCentroids = [];
        foreach ($centroids as $index => $centroid) {
            $label = '';

            switch ($index) {
                case 0:
                    $label = 'C1';  // Rendah
                    break;
                case 1:
                    $label = 'C2';  // Sedang
                    break;
                case 2:
                    $label = 'C3';  // Tinggi
                    break;
            }

            $labeledCentroids[] = [
                'label' => $label,
                'centroid' => $centroid
            ];
        }
        return $labeledCentroids;
    }

    // Fungsi untuk inisialisasi centroid secara acak
    private function initCentroids($data, $k)
    {
        // Sortir data berdasarkan total_frekuensi + total_kerusakan + luas_terdampak + total_korban
        usort($data, function ($a, $b) {
            $sumA = $a['total_frekuensi'] + $a['total_kerusakan'] + $a['luas_terdampak'] + $a['total_korban'];
            $sumB = $b['total_frekuensi'] + $b['total_kerusakan'] + $b['luas_terdampak'] + $b['total_korban'];
            return $sumA <=> $sumB; // Mengurutkan dari nilai terendah ke tertinggi
        });

        // Mengambil centroid awal berdasarkan urutan
        $centroids = [];

        // C1: Data dengan nilai terkecil (Cluster rendah)
        $centroids[] = [
            'nama_kotakab' => $data[0]['nama_kabupaten'],
            'nama_kecamatan' => $data[0]['nama_kecamatan'],
            'total_frekuensi' => $data[0]['total_frekuensi'],
            'total_kerusakan' => $data[0]['total_kerusakan'],
            'luas_terdampak' => $data[0]['luas_terdampak'],
            'total_korban' => $data[0]['total_korban'],
        ];

        // C2: Data dengan nilai median (Cluster sedang)
        $medianIndex = floor(count($data) / 2); // Mengambil indeks median
        $centroids[] = [
            'nama_kotakab' => $data[$medianIndex]['nama_kabupaten'],
            'nama_kecamatan' => $data[$medianIndex]['nama_kecamatan'],
            'total_frekuensi' => $data[$medianIndex]['total_frekuensi'],
            'total_kerusakan' => $data[$medianIndex]['total_kerusakan'],
            'luas_terdampak' => $data[$medianIndex]['luas_terdampak'],
            'total_korban' => $data[$medianIndex]['total_korban'],
        ];

        // C3: Data dengan nilai terbesar (Cluster tinggi)
        $lastIndex = count($data) - 1; // Mengambil indeks terakhir
        $centroids[] = [
            'nama_kotakab' => $data[$lastIndex]['nama_kabupaten'],
            'nama_kecamatan' => $data[$lastIndex]['nama_kecamatan'],
            'total_frekuensi' => $data[$lastIndex]['total_frekuensi'],
            'total_kerusakan' => $data[$lastIndex]['total_kerusakan'],
            'luas_terdampak' => $data[$lastIndex]['luas_terdampak'],
            'total_korban' => $data[$lastIndex]['total_korban'],
        ];

        return $centroids; // Mengembalikan centroid untuk C1, C2, dan C3
    }

    // Fungsi untuk menghitung centroid baru berdasarkan rata-rata titik di cluster
    private function assignClusters($data, $centroids)
    {
        //Array yang menampung titik-titik data untuk setiap cluster
        $clusters = [];
        foreach ($data as $point) {
            $minDistance = PHP_INT_MAX;
            $closestCentroid = null;
            //Iterasi dilakukan pada setiap titik data
            foreach ($centroids as $index => $centroid) {
                //Jarak dihitung menggunakan rumus Euclidean Distance
                $distance = sqrt(pow($point['total_frekuensi'] -
                    $centroid['total_frekuensi'], 2) + pow($point['total_kerusakan'] -
                    $centroid['total_kerusakan'], 2) + pow($point['luas_terdampak'] -
                    $centroid['luas_terdampak'], 2) + pow($point['total_korban'] -
                    $centroid['total_korban'], 2));
                //Variabel $minDistance digunakan untuk menyimpan jarak terdekat yang ditemukan
                //Variabel $closestCentroid digunakan untuk menyimpan indeks centroid yang paling dekat.
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $closestCentroid = $index;
                }
            }
            // Variabel $closestCentroid digunakan sebagai kunci untuk menyimpan titik data dalam array $clusters
            $clusters[$closestCentroid][] = $point;
        }
        return $clusters;
    }

    private function updateCentroids($data, $clusters, $k)
    {
        $newCentroids = [];
        for ($i = 0; $i < $k; $i++) {
            if (isset($clusters[$i]) && count($clusters[$i]) > 0) {
                $frekuensiTotal = array_sum(array_column($clusters[$i], 'total_frekuensi'));
                $kerusakanTotal = array_sum(array_column($clusters[$i], 'total_kerusakan'));
                $luasTotal = array_sum(array_column($clusters[$i], 'luas_terdampak'));
                $korbanTotal = array_sum(array_column($clusters[$i], 'total_korban'));
                $clusterSize = count($clusters[$i]);

                $newCentroids[] = [
                    'total_frekuensi' => $frekuensiTotal / $clusterSize,
                    'total_kerusakan' => $kerusakanTotal / $clusterSize,
                    'luas_terdampak' => $luasTotal / $clusterSize,
                    'total_korban' => $korbanTotal / $clusterSize,
                ];
            } else {
                // Jika cluster kosong, inisialisasi centroid baru
                $newCentroids[] = $this->initCentroids($data, 1)[0];
            }
        }
        return $newCentroids;
    }

    private function insertIterationData($iteration, $centroids, $clusters)
    {
        // Format data untuk setiap centroid, tambahkan label C1, C2, atau C3
        $labeledCentroids = [];
        foreach ($centroids as $index => $centroid) {
            $label = '';

            // Tentukan label berdasarkan index
            switch ($index) {
                case 0:
                    $label = 'C1';  // Rendah
                    break;
                case 1:
                    $label = 'C2';  // Sedang
                    break;
                case 2:
                    $label = 'C3';  // Tinggi
                    break;
            }

            // Tambahkan label ke centroid data
            $labeledCentroids[] = [
                'label' => $label,
                'centroid' => $centroid,
            ];
        }

        // Format cluster data sebagai JSON
        $centroidData = json_encode($labeledCentroids);
        $clusterData = json_encode($clusters);

        // Insert into tb_log_iterasi table
        DB::table('tb_log_iterasi')->insert([
            'iteration' => $iteration,
            'centroid_data' => $centroidData,
            'cluster_data' => $clusterData,
            'created_at' => now(),
        ]);
    }
}
