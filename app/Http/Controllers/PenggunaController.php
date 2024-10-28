<?php

namespace App\Http\Controllers;

use App\Models\TbPengguna;
use App\Models\TbPetuga;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TbPengguna::all();
        $title = 'Hapus Pengguna';
        $text = "Apakah anda yakin untuk hapus?";
        confirmDelete($title, $text);
        return view('pengguna/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
    public function edit(TbPengguna $pengguna)
    {
        return view('pengguna/edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TbPengguna $pengguna)
    {
        $messages = [
            'required' => 'Field :attribute wajib diisi.',
            'username.unique' => 'Username telah dipakai.',
            'email.unique' => 'Email telah dipakai.',
            'password.min' => 'Password harus terdiri dari minimal :min karakter.',
        ];
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:tb_pengguna,username,' . $pengguna->id_pengguna . ',id_pengguna',
            'email' => 'required|email|unique:tb_pengguna,email,' . $pengguna->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $pengguna->username = $request->username;
            $pengguna->email = $request->email;
            if ($request->filled('password')) {
                $pengguna->password = Hash::make($request->password);
            }
            $pengguna->save();

            DB::commit();

            Alert::success("Success", "Data berhasil diperbarui");

            return redirect("pengguna");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TbPengguna $pengguna)
    {
        $pengguna->delete();
        Alert::success("Success", "Data berhasil dihapus");

        return redirect("pengguna");
    }

    public function export()
    {
        $pengguna = TbPengguna::all();
        $pdf = Pdf::loadview('pengguna.export_pdf', ['data' => $pengguna]);
        return $pdf->download('laporan-pengguna.pdf');
    }
}
