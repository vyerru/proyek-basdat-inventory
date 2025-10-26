<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->nama_role; // Mengambil nama role dari relasi

        // Contoh: Menampilkan view berbeda berdasarkan role
        if ($role === 'Super Admin') {
            return view('home.superadmin', ['user' => $user]);
        } elseif ($role === 'Admin') {
            return view('home.admin', ['user' => $user]);
        } else {
            // Fallback jika ada role lain atau error
            Auth::logout();
            return redirect('/login')->withErrors('Role tidak dikenal.');
        }
    }
}