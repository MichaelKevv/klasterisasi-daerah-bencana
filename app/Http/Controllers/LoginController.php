<?php

namespace App\Http\Controllers;

use App\Models\TbKepalaSekolah;
use App\Models\TbPengguna;
use App\Models\TbPetuga;
use App\Models\TbSiswa;
use App\Models\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }
    public function showRegisterForm()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $pengguna = TbUser::where('email', $credentials['email'])
            ->first();

        if ($pengguna && Hash::check($credentials['password'], $pengguna->password)) {
            Auth::login($pengguna);
            session(['userdata' => $pengguna]);
            return redirect()->intended('dashboard');
        }

        return redirect()->back()->with('error', 'Email atau Password salah');
    }

    // public function register(Request $request)
    // {
    //     $messages = [
    //         'required' => 'Field :attribute wajib diisi.',
    //         'username.unique' => 'Username telah dipakai.',
    //         'email.unique' => 'Email telah dipakai.',
    //         'password.min' => 'Password harus terdiri dari minimal :min karakter.',
    //     ];
    //     $validator = Validator::make($request->all(), [
    //         'nama' => 'required|string|max:255',
    //         'kelas' => 'required|string|max:255',
    //         'gender' => 'required|string|max:255',
    //         'alamat' => 'required|string|max:255',
    //         'no_telp' => 'required|string|max:15',
    //         'username' => 'required|string|unique:tb_pengguna,username',
    //         'email' => 'required|email|unique:tb_pengguna,email',
    //         'password' => 'required|string|min:6',
    //         'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ], $messages);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $penggunaData = $request->only(['username', 'email']);
    //         $penggunaData['password'] = Hash::make($request->password);
    //         $penggunaData['role'] = 'siswa';

    //         $pengguna = TbPengguna::create($penggunaData);

    //         $siswaData = $request->only(['nama', 'kelas', 'jurusan', 'alamat', 'no_telp', 'gender']);
    //         $siswaData['id_pengguna'] = $pengguna->id_pengguna;
    //         if ($request->hasFile('foto')) {
    //             $image = $request->file('foto');
    //             $image->storeAs('public/foto-siswa', $image->hashName());
    //             $siswaData['foto'] = $image->hashName();
    //         }
    //         TbSiswa::create($siswaData);

    //         Alert::success("Success", "Registrasi berhasil! Silakan Login");

    //         DB::commit();

    //         return redirect("login");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
    //     }
    // }

    public function logout(Request $request)
    {
        Session::forget('userdata');
        Auth::logout();
        return redirect('login');
    }
}
