<?php

use App\Enums\UserRole;
use App\Http\Middleware\AturBahasa;
use App\Http\Middleware\PastikanPeran;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Percayai reverse proxy (ngrok) agar header X-Forwarded-Proto: https
        // dikenali. Tanpa ini Laravel mengira request masih http, sehingga URL
        // asset jadi http dan browser memblokirnya sebagai mixed content.
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            AturBahasa::class,
        ]);

        $middleware->alias([
            'role' => PastikanPeran::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));

        $middleware->redirectUsersTo(fn (Request $request) => $request->user()?->role === UserRole::Admin
            ? route('admin.dashboard')
            : route('officer.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
