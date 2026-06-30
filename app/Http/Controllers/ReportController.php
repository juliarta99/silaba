<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use App\Models\ReportEvidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('status', '!=', 'rejected')
            ->with(['category', 'district', 'tags', 'evidences', 'citizen.user']);

        // Filter: pencarian judul atau nomor tiket
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        // Filter: kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter: kecamatan
        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports    = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();

        return view('public.reports.index', compact('reports', 'categories', 'districts'));
    }

    public function create()
    {
        $categories = Category::all();
        $districts  = District::all();
        return view('public.reports.create', compact('categories', 'districts'));
    }

    public function createStep2()
    {
        if (! session()->has('report.category_id')) {
            return redirect()->route('reports.create');
        }
        return view('public.reports.create_step2');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'district_id' => 'required|exists:districts,id',
            'title'       => 'required|max:200',
            'description' => 'required',
            'address'     => 'required',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|max:4096',
            'guest_name'  => 'required_if:is_guest,1|nullable|max:100',
            'guest_phone' => 'required_if:is_guest,1|nullable|max:20',
        ]);

        $citizenId = Auth::check() ? Auth::user()->citizen?->id : null;

        $report = Report::create([
            'ticket_number' => 'TKT-' . now()->format('Y') . '-' . str_pad(Report::count() + 1, 3, '0', STR_PAD_LEFT),
            'citizen_id'    => $citizenId,
            'category_id'   => $request->category_id,
            'district_id'   => $request->district_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'address'       => $request->address,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
            'guest_name'    => $request->guest_name,
            'guest_phone'   => $request->guest_phone,
            'status'        => 'pending',
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('evidences', 'public');
                ReportEvidence::create(['report_id' => $report->id, 'photo_url' => $path]);
            }
        }

        session(['last_report_ticket' => $report->id]);
        return redirect()->route('reports.success');
    }

    public function success()
    {
        $reportId = session('last_report_ticket');
        $report   = $reportId ? Report::find($reportId) : null;
        return view('public.reports.success', compact('report'));
    }
}