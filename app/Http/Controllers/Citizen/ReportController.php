<?php
namespace App\Http\Controllers\Citizen;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $reports = Report::where('user_id', $userId)
            ->with(['category', 'district'])
            ->latest()->paginate(10);
        return view('citizen.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $this->authorizeReport($report);
        $report->load(['category','district','progresses.employee.user','assignment.employee.user','evidences']);
        return view('citizen.reports.show', compact('report'));
    }

    public function rating(Report $report)
    {
        $this->authorizeReport($report);
        abort_if($report->status !== 'completed', 403, 'Laporan belum selesai.');
        return view('citizen.reports.rating', compact('report'));
    }

    public function storeRating(Request $request, Report $report)
    {
        $this->authorizeReport($report);
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'comment' => 'nullable|string|max:500']);
        Review::create(['report_id' => $report->id, 'citizen_id' => auth()->user()->citizen->id,
                        'rating' => $request->rating, 'comment' => $request->comment]);
        return redirect()->route('citizen.reports.rating.success', $report);
    }

    public function ratingSuccess(Report $report)
    {
        return view('citizen.reports.rating_success', compact('report'));
    }

    private function authorizeReport(Report $report): void
    {
        abort_if($report->citizen_id !== auth()->user()->citizen->id, 403);
    }
}
