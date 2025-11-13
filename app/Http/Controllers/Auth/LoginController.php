<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User; // <-- Gunakan Model User yang baru

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function showLoginForm()
    {
        // Jika sudah login, lempar ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 1. Panggil SP untuk ambil data user
        $userData = DB::selectOne("CALL sp_get_user_by_username(?)", [$request->username]);

        // 2. Jika user tidak ditemukan
        if (!$userData) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // 3. Cek password (SANGAT TIDAK AMAN - Sesuai skema DB Anda)
        if ($request->password !== $userData->password) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // 4. Cari user menggunakan Eloquent (hanya untuk login)
        $user = User::find($userData->iduser);
        
        // 5. Loginkan user
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}