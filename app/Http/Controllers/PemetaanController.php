<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemetaanController extends Controller
{
    public function showMap()
    {
        // Fetch unique years for dropdown
        $tahunList = DB::table('tb_clustering')
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        // Load GeoJSON file
        $geojsonFile = file_get_contents(public_path('data/kec_jatim.geojson'));
        $geojsonData = json_decode($geojsonFile);

        // Ambil data clustering
        $clusters = DB::table('tb_clustering')
            ->join('tb_kecamatan', 'tb_clustering.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->select('tb_kecamatan.id as id_kecamatan', 'tb_kotakab.id as id_kotakab', 'tb_kotakab.nama_kotakab', 'tb_kecamatan.nama_kecamatan', 'tb_clustering.cluster')
            ->get();

        // Membuat asosiatif array untuk mapping data cluster
        $clusterMap = [];
        foreach ($clusters as $cluster) {
            // Mengubah nama_kecamatan ke uppercase
            $cleanedNamaKecamatan = strtoupper(str_replace(' ', '', $cluster->nama_kecamatan));
            $clusterMap[$cleanedNamaKecamatan] = [
                'id_kotakab' => $cluster->id_kotakab,
                'nama_kotakab' => $cluster->nama_kotakab,
                'id_kecamatan' => $cluster->id_kecamatan,
                'nama_kecamatan' => $cluster->nama_kecamatan,
                'cluster' => $cluster->cluster,
            ];
        }

        // Fungsi logging
        Log::info("GeoJSON Features Count: ", ['count' => count($geojsonData->features)]);

        foreach ($geojsonData->features as $feature) {
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

        // Mengubah GeoJSON terupdate ke JSON
        $modifiedGeojsonString = json_encode($geojsonData);

        return view('map.index', ['geojson' => $modifiedGeojsonString, 'tahunList' => $tahunList]);
    }


    public function filterByYear(Request $request)
    {
        // Load GeoJSON file
        $geojsonFile = file_get_contents(public_path('data/kec_jatim.geojson'));
        $geojsonData = json_decode($geojsonFile);

        $year = $request->input('year');

        // Ambil data clustering
        $clusters = DB::table('tb_clustering')
            ->join('tb_kecamatan', 'tb_clustering.id_kecamatan', '=', 'tb_kecamatan.id')
            ->join('tb_kotakab', 'tb_kecamatan.id_kotakab', '=', 'tb_kotakab.id')
            ->select('tb_kecamatan.id as id_kecamatan', 'tb_kotakab.id as id_kotakab', 'tb_kotakab.nama_kotakab', 'tb_kecamatan.nama_kecamatan', 'tb_clustering.cluster');

        if ($year) {
            $clusters = $clusters->where('tb_clustering.tahun', $year);
        }

        $clusters = $clusters->get();

        // Membuat asosiatif array untuk mapping data cluster
        $clusterMap = [];
        foreach ($clusters as $cluster) {
            // Mengubah nama_kecamatan ke uppercase
            $cleanedNamaKecamatan = strtoupper(str_replace(' ', '', $cluster->nama_kecamatan));
            $clusterMap[$cleanedNamaKecamatan] = [
                'id_kotakab' => $cluster->id_kotakab,
                'nama_kotakab' => $cluster->nama_kotakab,
                'id_kecamatan' => $cluster->id_kecamatan,
                'nama_kecamatan' => $cluster->nama_kecamatan,
                'cluster' => $cluster->cluster,
            ];
        }

        // Fungsi logging
        Log::info("GeoJSON Features Count: ", ['count' => count($geojsonData->features)]);

        foreach ($geojsonData->features as $feature) {
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

        // Mengubah GeoJSON terupdate ke JSON
        $modifiedGeojsonString = json_encode($geojsonData);

        return response()->json($geojsonData);
    }
}
