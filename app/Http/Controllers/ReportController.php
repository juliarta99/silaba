<?php
namespace App\Http\Controllers;
use App\Models\{Report, Category, District, ReportEvidence};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::where('status','!=','rejected')->with(['category','district','evidences']);
        if ($request->status)   $query->where('status', $request->status);
        if ($request->category) $query->where('category_id', $request->category);
        if ($request->district) $query->where('district_id', $request->district);
        $reports    = $query->latest()->paginate(12);
        $categories = Category::all();
        $districts  = District::all();
        return view('public.reports.index', compact('reports','categories','districts'));
    }

    public function create()
    {
        $categories = Category::all();
        $districts  = District::all();
        return view('public.reports.create', compact('categories','districts'));
    }

    public function createStep2()
    {
        if (! session()->has('report.category_id')) return redirect()->route('reports.create');
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
            // Untuk tamu
            'guest_name'  => 'required_if:is_guest,1|nullable|max:100',
            'guest_phone' => 'required_if:is_guest,1|nullable|max:20',
        ]);

        $citizenId = auth()->check() ? auth()->user()->citizen?->id : null;
        $report = Report::create([
            'citizen_id'  => $citizenId,
            'category_id' => $request->category_id,
            'district_id' => $request->district_id,
            'title'       => $request->title,
            'description' => $request->description,
            'address'     => $request->address,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'guest_name'  => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'status'      => 'pending',
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
