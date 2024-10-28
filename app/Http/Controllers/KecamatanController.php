<?php

namespace App\Http\Controllers;

use App\Models\TbKecamatan;
use App\Models\TbKotakab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KecamatanController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbKecamatan::all();
        $title = 'Hapus Kecamatan';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('kecamatan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['kotakab'] = TbKotakab::all();
        return view('kecamatan.create', $data);
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

        TbKecamatan::create($data);

        Alert::success('Success', 'Kecamatan berhasil disimpan');

        return redirect()->route('kecamatan.index');
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
    public function edit(TbKecamatan $kecamatan)
    {
        $kotakab = TbKotakab::all();
        return view('kecamatan/edit', compact('kecamatan', 'kotakab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbKecamatan $kecamatan)
    {
        $data = $request->all();
        $kecamatan->update($data);
        Alert::success('Success', 'Kecamatan berhasil diupdate');

        return redirect()->route('kecamatan.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbKecamatan $kecamatan)
    {
        $kecamatan->delete();

        Alert::success('Success', 'Kecamatan berhasil dihapus');

        return redirect()->route('kecamatan.index');
    }

    // public function export()
    // {
    //     $kecamatan = TbArtikel::all();
    //     $pdf = Pdf::loadview('kecamatan.export_pdf', ['data' => $kecamatan]);
    //     return $pdf->download('laporan-kecamatan.pdf');
    // }
}
