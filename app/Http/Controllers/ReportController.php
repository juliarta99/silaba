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
            ->with(['category', 'district', 'tags', 'evidences', 'user']);

        // Filter: pencarian judul atau nomor tiket (kolom di DB: code)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
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

        $reports    = $query->latest()->paginate(9)->onEachSide(0)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();

        return view('public.reports.index', compact('reports', 'categories', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'district_id' => 'required|exists:districts,id',
            'title'       => 'required|max:255',
            'description' => 'required|min:20',
            'location'    => 'required|max:255', // DB menggunakan 'location', bukan 'address'
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'photos'      => 'nullable|array',
            'photos.*'    => 'image|max:4096',
            'guest_name'  => 'required_if:is_guest,1|nullable|max:100',
            'guest_phone' => 'required_if:is_guest,1|nullable|max:20',
        ]);

        // DB menggunakan 'user_id' pada tabel reports, bukan 'citizen_id'
        $userId = Auth::check() ? Auth::id() : null;

        $report = Report::create([
            'code'        => 'TKT-' . now()->format('Y') . '-' . str_pad(Report::count() + 1, 3, '0', STR_PAD_LEFT), // DB menggunakan 'code', bukan 'ticket_number'
            'user_id'     => $userId,
            'category_id' => $request->category_id,
            'district_id' => $request->district_id,
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'guest_name'  => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'status'      => 'pending',
            'priority'    => 'medium', // Default priority sesuai struktur DB
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('evidences/' . $report->id, 'public');
                ReportEvidence::create([
                    'report_id' => $report->id,
                    'file_path' => $path, // DB menggunakan 'file_path', bukan 'photo_url'
                    'file_type' => 'photo' // Menambahkan enum file_type sesuai DB
                ]);
            }
        }

        session(['last_report_ticket' => $report->id]);
        return redirect()->route('reports.success');
    }

    public function show(string $code)
    {
        $report = Report::where('code', $code)
            ->with([
                'user',
                'category.department',
                'district',
                'tags',
                'evidences',
                'review',
                'childReports.user',
                'parentReport',
                'progresses' => fn ($q) => $q->with(['employee.user', 'employee.department'])->latest(),
                'assignments.employee.user',
                'assignments.employee.department',
            ])
            ->firstOrFail();
    
        // Cek apakah visitor adalah pemilik laporan ini (atau laporan gabungannya)
        $isOwner = false;
        if (Auth::check()) {
            $isOwner = $report->user_id === Auth::id()
                || $report->childReports->contains('user_id', Auth::id());
        }
    
        return view('public.reports.show', compact('report', 'isOwner'));
    }


    public function success()
    {
        $reportId = session('last_report_ticket');
        $report   = $reportId ? Report::find($reportId) : null;
        
        if (!$report) {
            return redirect()->route('reports.index');
        }

        return view('public.reports.success', compact('report'));
    }
}