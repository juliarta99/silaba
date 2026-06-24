<?php
namespace App\Http\Controllers\Citizen;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\RewardClaim;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $citizen = auth()->user()->citizen;
        $reports  = Report::where('citizen_id', $citizen->id)->latest()->take(5)->get();
        $totalReports   = Report::where('citizen_id', $citizen->id)->count();
        $activeReports  = Report::where('citizen_id', $citizen->id)->whereIn('status', ['pending','in_progress'])->count();
        $completedCount = Report::where('citizen_id', $citizen->id)->where('status', 'completed')->count();
        $points         = $citizen->points ?? 0;
        return view('citizen.dashboard', compact('reports','totalReports','activeReports','completedCount','points'));
    }
}
