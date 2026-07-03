<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    const RATING_POINTS = 25;

    // ── Index: Laporan Saya ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $userId = Auth::user()->id;

        $query = Report::where('user_id', $userId)
            ->with([
                'category',
                'district',
                'tags',
                'evidences',
                'review',
                // Pakai progresses (bukan latestProgress) supaya blade bisa ->first()
                'progresses' => fn ($q) => $q->latest()->limit(1),
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('period')) {
            $query->where('created_at', '>=', now()->subDays((int) $request->period));
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('citizen.reports.index', compact('reports'));
    }

    // ── Show: Detail Laporan ──────────────────────────────────────────────
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
                'progresses' => fn ($q) => $q->with([
                    'employee.user',
                    'employee.department',
                ])->latest(),
                'assignments.employee.user',
                'assignments.employee.department',
            ])
            ->firstOrFail();

        $isOwner = $report->user_id === Auth::user()->id
            || $report->childReports->contains('user_id', Auth::user()->id);

        abort_unless($isOwner, 403);

        return view('public.reports.show', compact('report', 'isOwner'));
    }

    // ── Konfirmasi selesai ────────────────────────────────────────────────
    public function confirm(string $code)
    {
        $report = $this->findOwnedReport($code, 'under_review');
        $report->update(['status' => 'completed']);

        return redirect()
            ->route('citizen.reports.index')
            ->with('success', 'Laporan dikonfirmasi selesai.');
    }

    // ── Belum selesai ─────────────────────────────────────────────────────
    public function rejectCompletion(string $code)
    {
        $report = $this->findOwnedReport($code, 'under_review');
        $report->update(['status' => 'in_progress']);

        return redirect()
            ->route('citizen.reports.index')
            ->with('info', 'Laporan dikembalikan ke status Diproses.');
    }

    // ── Dispute duplikat ──────────────────────────────────────────────────
    public function disputeDuplicate(string $code)
    {
        Report::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->whereNotNull('parent_report_id')
            ->firstOrFail();

        return back()->with('info', 'Pengajuan pemisahan laporan telah dikirim.');
    }

    // ── Form rating ───────────────────────────────────────────────────────
    public function rateForm(string $code)
    {
        $report = Report::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with([
                'category',
                'assignments.employee.user',
                'assignments.employee.department',
            ])
            ->firstOrFail();

        $firstAssignment = $report->assignments->first();
        $petugas    = $firstAssignment?->employee?->user?->name ?? '—';
        $department = $firstAssignment?->employee?->department?->name ?? '—';

        return view('citizen.reports.rate', compact('report', 'petugas', 'department'));
    }

    // ── Simpan rating ─────────────────────────────────────────────────────
    public function storeRating(Request $request, string $code)
    {
        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Pilih rating bintang terlebih dahulu.',
            'rating.between'  => 'Rating harus antara 1 sampai 5 bintang.',
        ]);

        $report = Report::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->firstOrFail();

        DB::transaction(function () use ($request, $report) {
            Review::create([
                'report_id' => $report->id,
                'user_id'   => Auth::user()->id,
                'rating'    => $request->rating,
                'comment'   => $request->comment,
            ]);

            Auth::user()?->citizen?->increment('points', self::RATING_POINTS);
        });

        session([
            'rating_stars'  => $request->rating,
            'rating_points' => self::RATING_POINTS,
        ]);

        return redirect()->route('citizen.reports.rate.success', $code);
    }

    // ── Halaman sukses rating ─────────────────────────────────────────────
    public function rateSuccess(string $code)
    {
        $stars  = session()->pull('rating_stars', 5);
        $points = session()->pull('rating_points', self::RATING_POINTS);

        $report = Report::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('citizen.reports.rate-success', compact('report', 'stars', 'points'));
    }

    // ── Helper ────────────────────────────────────────────────────────────
    private function findOwnedReport(string $code, string $status): Report
    {
        return Report::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->where('status', $status)
            ->firstOrFail();
    }
}