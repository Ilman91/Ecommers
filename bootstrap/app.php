<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: '/'  // Ini yang bakal nembak localhost:8000/ bukan /home
        );
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // ↑ 'admin' adalah nama alias
            // ↑ AdminMiddleware::class adalah class yang dijalankan
        ]);

         $middleware->validateCsrfTokens(except: [
            'midtrans/notification', // Endpoint webhook kita
            'midtrans/*',            // Wildcard (jika ada route lain)
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();