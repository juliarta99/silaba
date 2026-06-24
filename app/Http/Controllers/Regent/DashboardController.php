<?php
namespace App\Http\Controllers\Regent;
use App\Http\Controllers\Controller;
use App\Models\{Report, Department, Review};

class DashboardController extends Controller
{
    public function index()
    {
        $total      = Report::count();
        $pending    = Report::where('status','pending')->count();
        $completed  = Report::where('status','completed')->count();
        $topDepts   = Department::withCount(['employees'])->take(5)->get(); // TODO: order by performa
        $avgRating  = Review::avg('rating');
        return view('regent.dashboard', compact('total','pending','completed','topDepts','avgRating'));
    }
}
