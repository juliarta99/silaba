<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportPdfController extends Controller
{
    /**
     * Aturan akses download PDF:
     *  citizen             → hanya laporan miliknya sendiri
     *  employee            → laporan di departemen yang sama
     *  supervisor          → laporan di departemen yang sama
     *  head_of_department  → laporan di departemen yang sama
     *  district_chief      → laporan di kecamatannya
     *  regent              → semua laporan
     *  admin / super_admin → semua laporan
     */
    public function download(string $code)
    {
        $report = Report::where('code', $code)
            ->with([
                'user.citizen',
                'category.department',
                'district',
                'evidences',
                'progresses.employee.user',
                'assignments.employee.user',
                'review',
                'parentReport',
            ])
            ->firstOrFail();

        $user = Auth::user();
        abort_if(! $this->canDownload($user, $report), 403, 'Anda tidak memiliki izin mengunduh PDF laporan ini.');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.report', [
            'report'        => $report,
            'downloaded_by' => $user,
            'downloaded_at' => now()->translatedFormat('j F Y, H:i'),
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-' . $report->code . '.pdf');
    }

    private function canDownload($user, Report $report): bool
    {
        $role = $user->role;

        // Citizen: hanya laporan milik sendiri
        if ($role === 'citizen') {
            return $report->user_id === $user->id;
        }

        // Employee, Supervisor, Kepala Dinas: laporan di departemen yang sama
        if (in_array($role, ['employee', 'supervisor', 'head_of_department'])) {
            $deptId = $user->employee?->department_id;
            return $report->category?->department_id === $deptId;
        }

        // Camat: laporan di kecamatannya
        if ($role === 'district_chief') {
            $districtId = $user->districtChief?->district_id;
            return $report->district_id === $districtId;
        }

        // Regent, Admin, Super Admin: semua laporan
        return in_array($role, ['regent', 'admin', 'super_admin']);
    }
}