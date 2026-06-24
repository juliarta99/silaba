<?php
namespace App\Http\Controllers;
use App\Models\{Report, Category};

class PublicController extends Controller
{
    public function index()
    {
        $latestReports = Report::where('status','!=','rejected')
            ->with(['category','district','evidences'])
            ->latest()->take(3)->get();
        $stats = [
            'completed' => Report::where('status','completed')->count(),
            'avg_days'  => 7, // TODO: hitung real avg
        ];
        return view('public.index', compact('latestReports','stats'));
    }

    public function about()
    {
        return view('public.about');
    }
}
