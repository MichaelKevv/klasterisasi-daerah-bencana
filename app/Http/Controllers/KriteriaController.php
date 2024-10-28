<?php

namespace App\Http\Controllers;

use App\Models\TbKriteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class KriteriaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbKriteria::all();
        $title = 'Hapus Kriteria';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('kriteria.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kriteria.create');
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

        TbKriteria::create($data);

        Alert::success('Success', 'Kriteria berhasil disimpan');

        return redirect()->route('kriteria.index');
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
    public function edit(TbKriteria $kriteria)
    {
        return view('kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbKriteria $kriteria)
    {
        $data = $request->all();
        $kriteria->update($data);
        Alert::success('Success', 'Kriteria berhasil diupdate');

        return redirect()->route('kriteria.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbKriteria $kriteria)
    {
        $kriteria->delete();

        Alert::success('Success', 'Kriteria berhasil dihapus');

        return redirect()->route('kriteria.index');
    }

    // public function export()
    // {
    //     $kriteria = TbArtikel::all();
    //     $pdf = Pdf::loadview('kriteria.export_pdf', ['data' => $kriteria]);
    //     return $pdf->download('laporan-kriteria.pdf');
    // }
}
