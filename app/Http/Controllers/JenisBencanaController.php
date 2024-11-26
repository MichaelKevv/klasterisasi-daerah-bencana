<?php

namespace App\Http\Controllers;

use App\Models\TbJenisBencana;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class JenisBencanaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbJenisBencana::orderBy('nama_bencana', 'asc')->get();
        $title = 'Hapus Jenis Bencana';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('jenis_bencana.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jenis_bencana.create');
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

        TbJenisBencana::create($data);

        Alert::success('Success', 'Jenis Bencana berhasil disimpan');

        return redirect()->route('jenis_bencana.index');
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
    public function edit(TbJenisBencana $jenis_bencana)
    {
        return view('jenis_bencana.edit', compact('jenis_bencana'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbJenisBencana $jenis_bencana)
    {
        $data = $request->all();
        $jenis_bencana->update($data);
        Alert::success('Success', 'Jenis Bencana berhasil diupdate');

        return redirect()->route('jenis_bencana.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbJenisBencana $jenis_bencana)
    {
        $jenis_bencana->delete();

        Alert::success('Success', 'Jenis Bencana berhasil dihapus');

        return redirect()->route('jenis_bencana.index');
    }

    // public function export()
    // {
    //     $jenis_bencana = TbArtikel::all();
    //     $pdf = Pdf::loadview('jenis_bencana.export_pdf', ['data' => $jenis_bencana]);
    //     return $pdf->download('laporan-jenis_bencana.pdf');
    // }
}
