<?php

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
        // ── Alias middleware ──────────────────────────────────────────
        $middleware->alias([
            'role'              => \App\Http\Middleware\RoleMiddleware::class,
            'employee.position' => \App\Http\Middleware\EnsureEmployeePosition::class,
        ]);

        // ── Middleware groups (opsional) ──────────────────────────────
        // Jika ingin shortcut per posisi:
        $middleware->alias([
            'field_officer' => fn($req, $next) => (new \App\Http\Middleware\EnsureEmployeePosition)
                                    ->handle($req, $next, 'field_officer'),
            'supervisor'    => fn($req, $next) => (new \App\Http\Middleware\EnsureEmployeePosition)
                                    ->handle($req, $next, 'supervisor'),
            'head_of_dept'  => fn($req, $next) => (new \App\Http\Middleware\EnsureEmployeePosition)
                                    ->handle($req, $next, 'head_of_department'),
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
