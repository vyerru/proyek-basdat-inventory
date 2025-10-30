<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Gunakan jika password di-hash manual
use App\Models\User; // Pastikan model User diimport

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Log SEBELUM mencoba mencari user
        \Log::info('Login attempt received for username: ' . $credentials['username']);

        // 3. Cari User
        $user = User::where('username', $credentials['username'])->first();

        // 4. Log HASIL pencarian user
        if ($user) {
            \Log::info('User found in DB. Username: ' . $user->username . ' | DB Password: ' . $user->password);
        } else {
            \Log::warning('User NOT found in DB for username: ' . $credentials['username']);
        }

        // 5. Cek User dan Password (SEKARANG kita bisa cek log sebelumnya)
        if ($user && $user->password === $credentials['password']) {
            // Jika user ditemukan DAN password cocok (plain text)

            \Log::info('Password MATCH for ' . $user->username . '. Calling Auth::login().');

            Auth::login($user); // Login user

            // Cek apakah login berhasil disimpan ke session
            if (Auth::check()) {
                \Log::info('Auth::check() is TRUE after Auth::login(). User ID in session: ' . Auth::id());
                $request->session()->regenerate(); // Regenerate session
                \Log::info('Session regenerated. Attempting redirect to home.');

                return redirect()->route('home'); // Redirect ke dashboard
            } else {
                // Ini aneh jika terjadi setelah Auth::login()
                \Log::error('Auth::check() is FALSE after Auth::login() call for ' . $user->username . '. Session problem?');
                return back()->withErrors([
                    'username' => 'Gagal menyimpan sesi login. Coba lagi.',
                ])->onlyInput('username');
            }
        }

        // 6. Jika user tidak ditemukan ATAU password tidak cocok
        \Log::warning('Login failed for username: ' . $credentials['username'] . '. Either user not found or password mismatch.');
        return back()->withErrors([
            'username' => 'Username atau password salah.', // Pesan error umum
        ])->onlyInput('username');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

}