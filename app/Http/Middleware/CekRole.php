<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     * Memeriksa apakah user yang login memiliki salah satu role yang diizinkan.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Nama role yang diizinkan (contoh: 'Admin', 'Super Admin').
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        \Log::info('Authenticate middleware running. Auth check: ' . (Auth::check() ? 'true' : 'false'));
        // Jika tidak login, redirect ke halaman login
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar $roles yang diizinkan
        foreach ($roles as $role) {
            // Gunakan method hasRole() dari model User
            if ($user->hasRole($role)) {
                // Jika cocok, lanjutkan request
                return $next($request);
            }
        }

        // Jika tidak ada role yang cocok, tampilkan halaman error 403
        abort(403, 'AKSES DITOLAK');
    }
}