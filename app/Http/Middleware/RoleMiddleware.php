<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Cek user.role + (opsional) employee.position.
 *
 * Cara pakai:
 *   ->middleware('role:citizen')
 *   ->middleware('role:employee,field_officer')
 *   ->middleware('role:employee,supervisor|head_of_department')
 *   ->middleware('role:admin|super_admin')
 *
 * Contoh di web.php:
 *   Route::middleware(['auth', 'role:employee,field_officer'])->group(...)
 *   Route::middleware(['auth', 'role:employee,supervisor|head_of_department'])->group(...)
 *   Route::middleware(['auth', 'role:admin|super_admin'])->group(...)
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$params): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // param[0] = roles (pipe-separated): "admin|super_admin" atau "employee"
        // param[1] = positions (pipe-separated, opsional): "supervisor|head_of_department"
        $allowedRoles     = explode('|', $params[0] ?? '');
        $allowedPositions = isset($params[1]) ? explode('|', $params[1]) : [];

        // Cek user.role
        if (! in_array($user->role, $allowedRoles)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Cek employee.position jika ada syarat position
        if (! empty($allowedPositions)) {
            $employee = $user->employee;

            if (! $employee || ! in_array($employee->position, $allowedPositions)) {
                abort(403, 'Posisi Anda tidak memiliki akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
