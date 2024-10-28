<?php

namespace App\Http\Controllers;

use App\Imports\BencanaImport;
use App\Models\TbDataBencana;
use App\Models\TbJenisbencana;
use App\Models\TbKecamatan;
use App\Models\TbKotakab;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class DataBencanaController extends Controller
{
    public function getKecamatan($id)
    {
        // Ambil kecamatan berdasarkan id kota/kabupaten yang dipilih
        $kecamatan = TbKecamatan::where('id_kotakab', $id)->get();

        // Return response dalam format JSON
        return response()->json($kecamatan);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbDataBencana::all();
        $title = 'Hapus Data Bencana';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('data_bencana.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['kotakab'] = TbKotakab::all();
        $data['kecamatan'] = TbKecamatan::all();
        $data['jenis_bencana'] = TbJenisbencana::all();
        return view('data_bencana.create', $data);
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

        TbDataBencana::create($data);

        Alert::success('Success', 'Data Bencana berhasil disimpan');

        return redirect()->route('data_bencana.index');
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
    public function edit(TbDataBencana $data_bencana)
    {
        $kotakab = TbKotakab::all();
        $kecamatan = TbKecamatan::all();
        $jenis_bencana = TbJenisbencana::all();
        return view('data_bencana/edit', compact('data_bencana', 'kotakab', 'kecamatan', 'jenis_bencana'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbDataBencana $data_bencana)
    {
        $data = $request->all();
        $data_bencana->update($data);
        Alert::success('Success', 'Data Bencana berhasil diupdate');

        return redirect()->route('data_bencana.index');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbDataBencana $data_bencana)
    {
        $data_bencana->delete();

        Alert::success('Success', 'Data Bencana berhasil dihapus');

        return redirect()->route('data_bencana.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new BencanaImport, $request->file('file'));

        Alert::success('Success', 'Data Bencana berhasil diimport');

        return redirect('data_bencana');
    }

    // public function export()
    // {
    //     $data_bencana = TbArtikel::all();
    //     $pdf = Pdf::loadview('data_bencana.export_pdf', ['data' => $data_bencana]);
    //     return $pdf->download('laporan-data_bencana.pdf');
    // }
}
