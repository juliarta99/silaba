<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\Report;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function index()
    {
        // Ambil 3 laporan terbaru yang bukan ditolak (dilengkapi relasi yang dibutuhkan)
        $latestReports = Report::where('status', '!=', 'rejected')
            ->with(['category', 'district', 'evidences', 'tags'])
            ->latest()
            ->take(3)
            ->get();

        // 1. Total Laporan Selesai
        $completedCount = Report::where('status', 'completed')->count();

        // 2. Rata-rata Hari Penyelesaian
        $avgHours = DB::table('reports')
            ->where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_h')
            ->value('avg_h');
        $avgDays = $avgHours ? max(1, round($avgHours / 24)) : 0;

        // 3. Tingkat Kepuasan (Persentase dari tabel reviews)
        $avgRating = DB::table('reviews')
            ->join('reports', 'reports.id', '=', 'reviews.report_id')
            ->avg('reviews.rating');
        $satisfactionRate = $avgRating ? round(($avgRating / 5) * 100) : 0;

        $stats = [
            'completed'    => number_format($completedCount),
            'satisfaction' => $satisfactionRate . '%',
            'avg_days'     => $avgDays . ' Hari',
        ];

        return view('public.index', compact('latestReports', 'stats'));
    }

    public function about()
    {
        // 1. Total Laporan Selesai
        $completedCount = Report::where('status', 'completed')->count();

        // 2. Rata-rata Waktu Respons (dalam Jam)
        $avgHours = DB::table('reports')
            ->where('status', 'completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_h')
            ->value('avg_h');
        $avgResponse = $avgHours ? max(1, round($avgHours)) : 0;

        // 3. Tingkat Kepuasan
        $avgRating = DB::table('reviews')
            ->join('reports', 'reports.id', '=', 'reviews.report_id')
            ->avg('reviews.rating');
        $satisfactionRate = $avgRating ? round(($avgRating / 5) * 100) : 0;

        // 4. Jumlah OPD Terlibat
        $opdCount = Department::count();

        $statsData = [
            'completed'    => number_format($completedCount),
            'avg_response' => $avgResponse . ' Jam',
            'satisfaction' => $satisfactionRate . '%',
            'opd_count'    => $opdCount,
        ];

        return view('public.about', compact('statsData'));
    }
}