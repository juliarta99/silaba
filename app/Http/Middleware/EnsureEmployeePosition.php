<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureEmployeePosition
 *
 * Helper middleware khusus employee — redirect ke dashboard
 * yang sesuai posisinya jika mencoba akses halaman salah posisi.
 *
 * Dipakai sebagai penjaga setelah auth + role:employee.
 *
 * Contoh:
 *   ->middleware(['auth', 'role:employee', 'employee.position:supervisor'])
 */
class EnsureEmployeePosition
{
    private array $dashboardRoutes = [
        'field_officer'      => 'employee.field-officer.dashboard',
        'supervisor'         => 'employee.supervisor.dashboard',
        'head_of_department' => 'employee.head.dashboard',
    ];

    public function handle(Request $request, Closure $next, string ...$positions): Response
    {
        $employee = auth()->user()?->employee;

        if (! $employee) {
            abort(403);
        }

        if (! in_array($employee->position, $positions)) {
            // Redirect ke dashboard yang sesuai posisi mereka
            $route = $this->dashboardRoutes[$employee->position] ?? 'home';
            return redirect()->route($route)
                ->with('error', 'Akses ditolak. Anda diarahkan ke dashboard Anda.');
        }

        return $next($request);
    }
}
