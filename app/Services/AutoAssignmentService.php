<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Employee;
use App\Models\Report;
use Carbon\Carbon;

class AutoAssignmentService
{
    /**
     * Cari field officer terbaik dan buat assignment untuk laporan.
     *
     * @param  Report $report  Laporan yang akan di-assign (harus sudah punya category)
     * @return Employee|null   Employee yang ditugaskan, null jika tidak ada officer tersedia
     */
    public function assign(Report $report): ?Employee
    {
        // Ambil department dari kategori
        $category     = $report->category;
        $departmentId = $category?->department_id;

        if ($departmentId === null) {
            return null;
        }

        /**
         * Cari field officer aktif di department yang sesuai
         * dengan beban tugas (active_assignments_count) terendah.
         *
         * @var Employee|null $officer
         */
        $officer = Employee::query()
            ->where('department_id', $departmentId)
            ->where('position', 'field_officer')
            ->where('status', 'active')
            ->with('user')   // eager load supaya ->user tidak null
            ->withCount([
                'assignments as active_assignments_count' => function ($q) {
                    $q->whereHas('report', function ($r) {
                        $r->whereNotIn('status', ['completed', 'rejected']);
                    });
                },
            ])
            ->orderBy('active_assignments_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($officer === null) {
            return null;
        }

        // Cegah double-assignment ke laporan yang sama
        $alreadyAssigned = Assignment::query()
            ->where('report_id', $report->id)
            ->where('employee_id', $officer->id)
            ->exists();

        if (! $alreadyAssigned) {
            Assignment::create([
                'report_id'   => $report->id,
                'employee_id' => $officer->id,
            ]);
        }

        return $officer;
    }

    /**
     * Hitung SLA deadline berdasarkan prioritas (hari kerja Senin–Jumat).
     *
     * @param  string $priority  low|medium|high|critical
     * @return Carbon
     */
    public function calculateSlaDeadline(string $priority): Carbon
    {
        $workingDays = match($priority) {
            'critical' => 1,
            'high'     => 3,
            'medium'   => 7,
            'low'      => 14,
            default    => 7,
        };

        return $this->addWorkingDays(now(), $workingDays);
    }

    /**
     * Tambah N hari kerja ke tanggal awal, lewati Sabtu & Minggu.
     * Deadline = jam 17:00 hari kerja terakhir.
     */
    private function addWorkingDays(Carbon $start, int $days): Carbon
    {
        $date  = $start->copy();
        $added = 0;

        while ($added < $days) {
            $date->addDay();
            if ($date->dayOfWeek !== Carbon::SATURDAY
                && $date->dayOfWeek !== Carbon::SUNDAY) {
                $added++;
            }
        }

        return $date->setTime(17, 0, 0);
    }
}