<?php

namespace App\Http\Controllers;

use App\Models\TbDatabencana;
use App\Models\TbJenisbencana;
use App\Models\TbKecamatan;
use App\Models\TbKotakab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunList = TbDataBencana::selectRaw('tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');
        $kotakab = TbKotakab::count();
        $kecamatan = TbKecamatan::count();
        $jenis_bencana = TbJenisbencana::count();
        $data_bencana = TbDatabencana::count();
        $bencanaByJenis = TbDatabencana::select(DB::raw('tb_jenisbencana.nama_bencana, COUNT(*) as total'))
            ->join('tb_jenisbencana', 'tb_jenisbencana.id', 'tb_databencana.id_jenisbencana')
            ->groupBy('tb_jenisbencana.nama_bencana')
            ->get();
        $bencanaByTahun = TbDatabencana::select(DB::raw('tahun, COUNT(*) as total'))
            ->groupBy(DB::raw('tahun'))
            ->get();
        $colors = [];
        foreach ($bencanaByJenis as $bencana) {
            $colors[] = $this->generateRandomColor();
        }
        return view('dashboard', compact('colors', 'kotakab', 'kecamatan', 'data_bencana', 'jenis_bencana', 'bencanaByJenis', 'bencanaByTahun', 'tahunList'));
    }

    private function generateRandomColor()
    {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }
        return $color;
    }
}
