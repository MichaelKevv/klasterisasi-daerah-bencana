<?php

namespace App\Http\Controllers;

use App\Models\TbDatabencana;
use App\Models\TbJenisbencana;
use App\Models\TbKecamatan;
use App\Models\TbKotakab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $kotakab = TbKotakab::count();
        $kecamatan = TbKecamatan::count();
        $jenis_bencana = TbJenisbencana::count();
        $data_bencana = TbDatabencana::count();
        return view('dashboard', compact('kotakab', 'kecamatan', 'data_bencana', 'jenis_bencana'));
    }
}
