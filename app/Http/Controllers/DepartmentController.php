<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Report;
use App\Models\Review;

class DepartmentController extends Controller
{
    public function show(Department $department)
    {
        // 1. STATISTIK SLA (Kode yang sama seperti sebelumnya)
        $baseQuery = Report::whereHas('category', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        });

        $total = (clone $baseQuery)->count();
        $completed = (clone $baseQuery)->where('status', 'completed')->count();
        
        $onTimeCompleted = (clone $baseQuery)
            ->where('status', 'completed')
            ->whereNotNull('sla_deadline')
            ->whereColumn('updated_at', '<=', 'sla_deadline')
            ->count();

        $failedSla = (clone $baseQuery)
            ->whereNotNull('sla_deadline')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('status', 'completed')
                      ->whereColumn('updated_at', '>', 'sla_deadline');
                })->orWhere(function ($q) {
                    $q->where('status', '!=', 'completed')
                      ->where('sla_deadline', '<', now());
                });
            })->count();

        $totalEvaluated = $onTimeCompleted + $failedSla;
        $onTimePercentage = $totalEvaluated > 0 ? ($onTimeCompleted / $totalEvaluated) * 100 : 0;

        $stats = [
            'total' => $total,
            'completed' => $completed,
            'on_time_percentage' => round($onTimePercentage, 1),
        ];

        // 2. DAFTAR ULASAN
        $reviews = Review::with(['user', 'report'])
            ->whereHas('report.category', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->latest()
            ->paginate(5, ['*'], 'reviews_page'); // Gunakan custom param 'reviews_page'

        $averageRating = Review::whereHas('report.category', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })->avg('rating') ?? 0;

        // 3. DAFTAR LAPORAN TERKAIT
        $reports = Report::with(['category']) // Load category untuk efisiensi query
            ->whereHas('category', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->latest()
            ->paginate(6, ['*'], 'reports_page'); // Gunakan custom param 'reports_page'

        return view('public.departments.show', compact('department', 'stats', 'reviews', 'averageRating', 'reports'));
    }
}