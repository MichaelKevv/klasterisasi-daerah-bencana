<?php

namespace App\Http\Controllers;

use App\Models\TbUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

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
            session()->put('userdata', [
                'id' => $pengguna->id,
                'nama_user' => $pengguna->nama_user,
                'email' => $pengguna->email,
                'role' => $pengguna->role,
            ]);
            return redirect()->intended('dashboard');
        }

        return redirect()->back()->with('error', 'Email atau Password salah');
    }

    public function register(Request $request)
    {

        DB::beginTransaction();

        try {
            $penggunaData = $request->only(['nama_user', 'email']);
            $penggunaData['password'] = Hash::make($request->password);
            $penggunaData['role'] = 'user';

            TbUser::create($penggunaData);

            Alert::success("Success", "Registrasi berhasil! Silakan Login");

            DB::commit();

            return redirect("login");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget('userdata');
        return redirect('login');
    }
}
