<?php

namespace App\Http\Controllers;

use App\Models\TbClustering;
use App\Models\TbDataBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KlasterisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbClustering::all();
        return view('klasterisasi.index', compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clustering = TbClustering::selectRaw('
                tb_clustering.cluster,
                tb_kotakab.nama_kotakab,
                tb_kecamatan.nama_kecamatan,
                tb_jenisbencana.nama_bencana,
                tb_databencana.tahun,
                tb_databencana.frekuensi_kejadian,
                tb_databencana.total_kerusakan,
                tb_databencana.luas_terdampak,
                tb_databencana.total_korban
            ')
            ->join('tb_kecamatan', 'tb_clustering.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->join('tb_databencana', 'tb_databencana.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_jenisbencana', 'tb_jenisbencana.id', '=', 'tb_databencana.id_jenisbencana')
            ->where('tb_kecamatan.id', '=', $id)
            ->get();

        if ($clustering->isEmpty()) {
            Alert::error('Data not found', 'Data tidak ditemukan');
            return redirect('klasterisasi');
        }

        return view('klasterisasi.detail', compact('clustering'));
    }

    public function prosesKlasterisasi()
    {
        // Step 1: Ambil dan akumulasi data bencana per kecamatan
        $dataBencana = TbDatabencana::selectRaw('
                tb_kotakab.id as id_kotakab,
                tb_kotakab.nama_kotakab as nama_kabupaten,
                tb_kecamatan.id as id_kecamatan,
                tb_kecamatan.nama_kecamatan,
                SUM(tb_databencana.frekuensi_kejadian) as total_frekuensi,
                SUM(tb_databencana.total_kerusakan) as total_kerusakan,
                SUM(tb_databencana.total_korban) as total_korban
            ')
            ->join('tb_kecamatan', 'tb_databencana.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->groupBy('tb_kecamatan.nama_kecamatan', 'tb_kotakab.nama_kotakab', 'tb_kotakab.id', 'tb_kecamatan.id')
            ->orderBy('tb_kotakab.nama_kotakab')
            ->orderBy('tb_kecamatan.nama_kecamatan')
            ->get();

        // Step 2: Format data untuk K-Means
        $data = [];
        foreach ($dataBencana as $item) {
            $data[] = [
                'id_kotakab' => $item->id_kotakab,
                'nama_kabupaten' => $item->nama_kabupaten,
                'id_kecamatan' => $item->id_kecamatan,
                'nama_kecamatan' => $item->nama_kecamatan,
                'total_frekuensi' => $item->total_frekuensi,
                'total_kerusakan' => $item->total_kerusakan,
                'total_korban' => $item->total_korban,
            ];
        }



        // Fungsi untuk mengecek apakah ada perubahan data
        // Buat hash dari data bencana
        $currentHash = md5(json_encode($data)); // Hash data bencana

        // Ambil hash terakhir dari database
        $lastHash = TbClustering::orderBy('created_at', 'desc')->value('data_hash');

        // Cek apakah hash berubah
        if ($currentHash === $lastHash) {
            Alert::error('Peringatan', 'Tidak ada perubahan data bencana, proses klasterisasi tidak dilakukan.');
            return redirect('klasterisasi');
        }

        // Step 3: Tentukan jumlah cluster
        $jumlahCluster = 3;

        // Step 4: Jalankan algoritma K-Means
        $clusters = $this->kMeans($data, $jumlahCluster);
        $centroids = $this->initCentroids($data);

        // Step 5: Hapus data lama di tb_clustering jika ada perubahan data
        TbClustering::truncate(); // Hapus semua data di tabel tb_clustering

        // Step 6: Simpan hasil clustering ke database
        $this->insertHasilKlasterisasi($clusters, $currentHash);

        Alert::success('Success', 'Klasterisasi Berhasil');

        return redirect('klasterisasi');
    }

    private function kMeans($data, $k)
    {
        // Step 1: Inisialisasi centroid secara acak
        $centroids = $this->initCentroids($data, $k);

        // Step 2: Mulai iterasi sampai konvergen
        $iterations = 100; // Batas maksimum iterasi
        for ($i = 0; $i < $iterations; $i++) {
            $clusters = $this->assignClusters($data, $centroids);

            // Perbarui centroid
            $newCentroids = $this->updateCentroids($data, $clusters['clusters'], $k);

            // Simpan data iterasi
            $this->insertIterationData($i + 1, $this->labelCentroids($centroids), $clusters['clusters'], $clusters['euclidean_distances']);

            // Cek konvergensi
            if ($centroids == $newCentroids) {
                break;
            }
            $centroids = $newCentroids;
        }
        ksort($clusters['clusters']);

        // Pastikan cluster selalu berurutan dengan label C1, C2, C3
        return $clusters['clusters'];
    }

    // Fungsi untuk inisialisasi centroid secara acak
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

    // Fungsi untuk menyimpan hasil klasterisasi ke dalam tabel tb_hasil_klasterisasi
    private function insertHasilKlasterisasi($clusters, $currentHash)
    {
        foreach ($clusters as $index => $clusterData) {
            $clusterLabel = '';

            // Berikan label berdasarkan urutan cluster (C1, C2, C3)
            switch ($index) {
                case 0:
                    $clusterLabel = 'C1';  // Rendah
                    break;
                case 1:
                    $clusterLabel = 'C2';  // Sedang
                    break;
                case 2:
                    $clusterLabel = 'C3';  // Tinggi
                    break;
            }

            // Insert data setiap kecamatan dalam cluster ke dalam tabel
            foreach ($clusterData as $data) {
                DB::table('tb_clustering')->insert([
                    'id_kotakab' => $data['id_kotakab'],
                    'id_kecamatan' => $data['id_kecamatan'],
                    'frekuensi_kejadian' => $data['total_frekuensi'],
                    'total_kerusakan' => $data['total_kerusakan'],
                    'total_korban' => $data['total_korban'],
                    'cluster' => $clusterLabel, // Simpan label cluster (C1, C2, atau C3)
                    'created_at' => now(),
                    'data_hash' => $currentHash, // Simpan hash data
                ]);
            }
        }
    }

    // Fungsi untuk inisialisasi centroid secara acak
    private function initCentroids($data)
    {
        // Sortir data berdasarkan total_frekuensi + total_kerusakan + luas_terdampak + total_korban
        usort($data, function ($a, $b) {
            $sumA = $a['total_frekuensi'] + $a['total_kerusakan'] + $a['total_korban'];
            $sumB = $b['total_frekuensi'] + $b['total_kerusakan'] + $b['total_korban'];
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
            'total_korban' => $data[0]['total_korban'],
        ];

        // C2: Data dengan nilai median (Cluster sedang)
        $medianIndex = floor(count($data) / 2); // Mengambil indeks median
        $centroids[] = [
            'nama_kotakab' => $data[$medianIndex]['nama_kabupaten'],
            'nama_kecamatan' => $data[$medianIndex]['nama_kecamatan'],
            'total_frekuensi' => $data[$medianIndex]['total_frekuensi'],
            'total_kerusakan' => $data[$medianIndex]['total_kerusakan'],
            'total_korban' => $data[$medianIndex]['total_korban'],
        ];

        // C3: Data dengan nilai terbesar (Cluster tinggi)
        $lastIndex = count($data) - 1; // Mengambil indeks terakhir
        $centroids[] = [
            'nama_kotakab' => $data[$lastIndex]['nama_kabupaten'],
            'nama_kecamatan' => $data[$lastIndex]['nama_kecamatan'],
            'total_frekuensi' => $data[$lastIndex]['total_frekuensi'],
            'total_kerusakan' => $data[$lastIndex]['total_kerusakan'],
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
                    $centroid['total_kerusakan'], 2) + pow($point['total_korban'] -
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
        $datas = [
            'clusters' => $clusters,
            'euclidean_distances' => array_map(function ($point) use ($centroids) {
                return array_map(function ($centroid) use ($point) {
                    return sqrt(
                        pow($point['total_frekuensi'] - $centroid['total_frekuensi'], 2) +
                            pow($point['total_kerusakan'] - $centroid['total_kerusakan'], 2) +
                            pow($point['total_korban'] - $centroid['total_korban'], 2)
                    );
                }, $centroids);
            }, $data)
        ];
        return $datas;
    }

    private function updateCentroids($data, $clusters, $k)
    {
        $newCentroids = [];
        for ($i = 0; $i < $k; $i++) {
            if (isset($clusters[$i]) && count($clusters[$i]) > 0) {
                $frekuensiTotal = array_sum(array_column($clusters[$i], 'total_frekuensi'));
                $kerusakanTotal = array_sum(array_column($clusters[$i], 'total_kerusakan'));
                $korbanTotal = array_sum(array_column($clusters[$i], 'total_korban'));
                $clusterSize = count($clusters[$i]);

                $newCentroids[] = [
                    'total_frekuensi' => $frekuensiTotal / $clusterSize,
                    'total_kerusakan' => $kerusakanTotal / $clusterSize,
                    'total_korban' => $korbanTotal / $clusterSize,
                ];
            } else {
                // Jika cluster kosong, inisialisasi centroid baru
                $newCentroids[] = $this->initCentroids($data, 1)[0];
            }
        }
        return $newCentroids;
    }

    private function insertIterationData($iteration, $centroids, $clusters, $euclidean_distance)
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
        $euclideanDistanceData = json_encode($euclidean_distance);

        // Insert into tb_log_iterasi table
        DB::table('tb_log_iterasi')->insert([
            'iteration' => $iteration,
            'centroid_data' => $centroidData,
            'cluster_data' => $clusterData,
            'euclidean_distance' => $euclideanDistanceData,
            'created_at' => now(),
        ]);
    }

    public function showMap(Request $request)
    {
        // Load existing GeoJSON file
        $geojsonFile = file_get_contents(public_path('data/kec_jatim.geojson'));
        $geojsonData = json_decode($geojsonFile);

        // Fetch clustering data
        $clusters = DB::table('tb_clustering')
            ->join('tb_kecamatan', 'tb_clustering.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->select('tb_kecamatan.id as id_kecamatan', 'tb_kotakab.id as id_kotakab', 'tb_kotakab.nama_kotakab', 'tb_kecamatan.nama_kecamatan', 'tb_clustering.cluster')
            ->get();

        // Create an associative array to map clustering data
        $clusterMap = [];
        foreach ($clusters as $cluster) {
            // Convert nama_kecamatan to uppercase and remove spaces for the map
            $cleanedNamaKecamatan = strtoupper(str_replace(' ', '', $cluster->nama_kecamatan));
            $clusterMap[$cleanedNamaKecamatan] = [
                'id_kotakab' => $cluster->id_kotakab,
                'nama_kotakab' => $cluster->nama_kotakab,
                'id_kecamatan' => $cluster->id_kecamatan,
                'nama_kecamatan' => $cluster->nama_kecamatan,
                'cluster' => $cluster->cluster,
            ];
        }

        // Log the count of GeoJSON features
        Log::info("GeoJSON Features Count: ", ['count' => count($geojsonData->features)]);

        foreach ($geojsonData->features as $feature) {
            // Clean the feature's NAME_3 property
            $nama_kecamatan = strtoupper(str_replace(' ', '', $feature->properties->NAME_3));
            Log::info("Processing feature for: ", ['kabupaten' => $clusterMap[$nama_kecamatan]['nama_kotakab'] ?? null, 'kecamatan' => $nama_kecamatan]);

            if (isset($clusterMap[$nama_kecamatan])) {
                $feature->properties->cluster = $clusterMap[$nama_kecamatan]['cluster'];
                Log::info("Assigned cluster: ", ['cluster' => $feature->properties->cluster]);
            } else {
                Log::info("Cluster not found for: ", ['kabupaten' => $clusterMap[$nama_kecamatan]['nama_kotakab'] ?? null, 'kecamatan' => $nama_kecamatan]);
                $feature->properties->cluster = null;
            }
        }

        // Convert updated GeoJSON back to JSON
        $modifiedGeojsonString = json_encode($geojsonData);

        // Pass modified GeoJSON to the view
        return view('map.index', ['geojson' => $modifiedGeojsonString]);
    }
}
