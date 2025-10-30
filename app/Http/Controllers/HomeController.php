<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-use
use Illuminate\Support\Facades\Log; // Pastikan Log di-use

class HomeController extends Controller
{
    public function index()
    {
        Log::info('HomeController accessed. Checking Auth status...');

        // Cek apakah user login SAAT TIBA di HomeController
        if (!Auth::check()) {
            Log::warning('HomeController: Auth::check() is FALSE. User not logged in. Redirecting to login.');
            return redirect()->route('login'); // Gunakan route()
        }

        $user = Auth::user();
        Log::info('HomeController: User ' . $user->username . ' is logged in.');

        // Cek relasi role
        if (!$user->role) {
             Log::warning('HomeController: User ' . $user->username . ' has NO ROLE (idrole probably NULL). Logging out.');
             Auth::logout();
             return redirect('/login')->withErrors('User tidak memiliki role.');
        }

        $role = $user->role->nama_role;
        Log::info('HomeController: User role found: ' . $role); // INI KUNCINYA

        // Cek nama role
        if ($role === 'Super Admin') {
            Log::info('HomeController: Role is "Super Admin". Loading superadmin view.');
            return view('home.superadmin', ['user' => $user]);
        } elseif ($role === 'Admin') {
            Log::info('HomeController: Role is "Admin". Loading admin view.');
            return view('home.admin', ['user' => $user]);
        } else {
            Log::warning('HomeController: Role "' . $role . '" is NOT RECOGNIZED. Logging out.');
            Auth::logout();
            return redirect('/login')->withErrors('Role tidak dikenal.');
        }
    }
}