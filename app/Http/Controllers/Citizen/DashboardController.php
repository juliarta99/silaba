<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $citizen = $user->citizen;

        $totalLaporan   = Report::where('user_id', $user->id)->count();

        $sedangDiproses = Report::where('user_id', $user->id)
            ->whereIn('status', ['in_progress', 'waiting_for_materials'])
            ->count();

        $selesai = Report::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $poinReward = $citizen?->points ?? 0;

        $laporanTerbaru = Report::where('user_id', $user->id)
            ->with([
                'category',
                'tags',
                'evidences',
                'latestProgress',
            ])
            ->latest()
            ->limit(3)
            ->get();

        $userReportIds = Report::where('user_id', $user->id)->pluck('id');

        $notifications = Notification::whereIn('report_id', $userReportIds)
            ->where('is_sent', true)          // hanya yang sudah terkirim
            ->latest('sent_at')
            ->limit(3)
            ->with('report:id,code,title')    // eager load info laporan
            ->get();

        $nextRewardThreshold = 200;

        return view('citizen.dashboard', compact(
            'user',
            'citizen',
            'totalLaporan',
            'sedangDiproses',
            'selesai',
            'poinReward',
            'laporanTerbaru',
            'notifications',
            'nextRewardThreshold',
        ));
    }
}