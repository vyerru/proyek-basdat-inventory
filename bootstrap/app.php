<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- Tambahkan alias middleware di sini ---
        $middleware->alias([
            'role' => \App\Http\Middleware\CekRole::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class, // Biasanya sudah ada bawaan
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Biasanya sudah ada bawaan
            // Tambahkan alias lain jika perlu
        ]);

        // Anda juga bisa menambahkan middleware global atau grup di sini jika diperlukan
        // $middleware->web(append: [ ... ]);
        // $middleware->api(prepend: [ ... ]);

    }) // <-- Tutup kurung withMiddleware
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();