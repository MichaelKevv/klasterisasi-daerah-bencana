<?php

namespace App\Http\Controllers;

use App\Models\TbKotakab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KotaKabController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbKotakab::orderBy('nama_kotakab', 'asc')->get();
        $title = 'Hapus Kota/Kab';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('kotakab.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kotakab.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        TbKotakab::create($data);

        Alert::success('Success', 'Kota/Kab berhasil disimpan');

        return redirect()->route('kotakab.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(TbKotakab $kotakab)
    {
        return view('kotakab/edit', compact('kotakab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbKotakab $kotakab)
    {
        $data = $request->all();
        $kotakab->update($data);
        Alert::success('Success', 'Kota/Kab berhasil diupdate');

        return redirect()->route('kotakab.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbKotakab $kotakab)
    {
        $kotakab->delete();

        Alert::success('Success', 'Kota/Kab berhasil dihapus');

        return redirect()->route('kotakab.index');
    }

    // public function export()
    // {
    //     $kotakab = TbArtikel::all();
    //     $pdf = Pdf::loadview('kotakab.export_pdf', ['data' => $kotakab]);
    //     return $pdf->download('laporan-kotakab.pdf');
    // }
}
