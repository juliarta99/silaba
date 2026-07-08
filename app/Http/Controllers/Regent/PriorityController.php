<?php

namespace App\Http\Controllers\Regent;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Report;
use App\Services\GeminiInsightService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PriorityController extends Controller
{
    public function __construct(private GeminiInsightService $gemini) {}

    public function index(Request $request)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403);

        $now = Carbon::now();

        // ── Setup Filter Bulan (1 Tahun Kebelakang) ──────────────────────
        $selectedMonth = $request->input('month');
        $sYear  = null;
        $sMonth = null;
        if ($selectedMonth && preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            [$sYear, $sMonth] = explode('-', $selectedMonth);
        } else {
            $selectedMonth = null;
        }

        // Pilihan bulan 12 bulan terakhir (0 s/d 11)
        $availableMonths = collect(range(0, 11))->map(function ($i) use ($now) {
            $dt = $now->copy()->subMonths($i);
            return [
                'value' => $dt->format('Y-m'),
                'label' => $dt->translatedFormat('F Y'),
            ];
        });

        // ── Statistik umum se-Kabupaten ───────────────────────────────────
        $total = Report::when($selectedMonth, fn($q) => $q->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth))->count();
        $selesai = Report::where('status','completed')
                        ->when($selectedMonth, fn($q) => $q->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth))
                        ->count();
        $sedangDiproses = Report::whereIn('status',['in_progress','waiting_for_materials','under_review'])
                        ->when($selectedMonth, fn($q) => $q->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth))
                        ->count();
        $terlambat = Report::whereNotIn('status',['completed','rejected'])
                        ->whereNotNull('sla_deadline')
                        ->where('sla_deadline','<',$now)
                        ->when($selectedMonth, fn($q) => $q->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth))
                        ->count();
        
        $completionRate = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

        $avgH = DB::table('reports')->where('status','completed')
            ->when($selectedMonth, fn($q) => $q->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth))
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_h')
            ->value('avg_h');
        $avgHari = $avgH ? round($avgH / 24, 1) : 0;

        $avgRating = DB::table('reviews')
            ->join('reports','reports.id','=','reviews.report_id')
            ->when($selectedMonth, fn($q) => $q->whereYear('reports.created_at', $sYear)->whereMonth('reports.created_at', $sMonth))
            ->avg('reviews.rating');
        $kepuasan = $avgRating ? round(($avgRating / 5) * 100, 1) : 0;

        // ── Kategori terbanyak se-Kabupaten ───────────────────────────────
        $kategoriRaw = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->when($selectedMonth, fn($q) => $q->whereYear('reports.created_at', $sYear)->whereMonth('reports.created_at', $sMonth))
            ->selectRaw('
                categories.id,
                categories.name,
                COUNT(*) as total,
                SUM(CASE WHEN reports.status="completed" THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN reports.status NOT IN ("completed","rejected")
                    AND reports.sla_deadline IS NOT NULL
                    AND reports.sla_deadline < NOW() THEN 1 ELSE 0 END) as terlambat,
                AVG(CASE WHEN reports.status="completed"
                    THEN TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at) END) as avg_jam
            ')
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($r) => [
                'id'          => $r->id,
                'nama'        => $r->name,
                'total'       => (int) $r->total,
                'selesai'     => (int) $r->selesai,
                'terlambat'   => (int) $r->terlambat,
                'completion'  => $r->total > 0 ? round(($r->selesai / $r->total) * 100, 1) : 0,
                'avg_hari'    => $r->avg_jam ? round($r->avg_jam / 24, 1) : 0,
                'pct_of_total'=> $total > 0 ? round(($r->total / $total) * 100, 1) : 0,
            ]);

        // ── Dinas paling banyak terlambat (se-Kabupaten) ─────────────────
        $dinasTerlambat = Department::get()->map(function ($dept) use ($now, $selectedMonth, $sYear, $sMonth) {
            $base = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->when($selectedMonth, fn($q) => $q->whereYear('reports.created_at', $sYear)->whereMonth('reports.created_at', $sMonth));

            $total = (clone $base)->count();
            if ($total === 0) return null;

            $terlambat = (clone $base)
                ->whereNotIn('reports.status',['completed','rejected'])
                ->whereNotNull('reports.sla_deadline')
                ->where('reports.sla_deadline','<',$now->toDateTimeString())
                ->count();

            if ($terlambat === 0) return null;

            $avgH = (clone $base)
                ->where('reports.status','completed')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                ->value('avg_h');

            $petugas      = DB::table('employees')->where('department_id',$dept->id)->where('position','field_officer')->count();
            $petugasAktif = DB::table('employees')->where('department_id',$dept->id)->where('position','field_officer')->where('status','active')->count();

            return [
                'id'            => $dept->id,
                'nama'          => $dept->name,
                'total'         => $total,
                'terlambat'     => $terlambat,
                'pct_terlambat' => round(($terlambat / $total) * 100, 1),
                'avg_hari'      => $avgH ? round($avgH / 24, 1) : 0,
                'petugas_total' => $petugas,
                'petugas_aktif' => $petugasAktif,
            ];
        })
        ->filter()
        ->sortByDesc('pct_terlambat')
        ->take(3)
        ->values();

        // ── Distribusi per kecamatan (semua, tidak ada highlight) ─────────
        $distrikStats = District::withCount([
            'reports as total_r' => fn($q) => $q->when($selectedMonth, fn($q2) => $q2->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth)),
            'reports as done_r'  => fn($q) => $q->where('status','completed')->when($selectedMonth, fn($q2) => $q2->whereYear('created_at', $sYear)->whereMonth('created_at', $sMonth)),
        ])
        ->having('total_r','>',0)
        ->get()
        ->map(fn ($d) => [
            'id'      => $d->id,
            'nama'    => $d->name,
            'total'   => $d->total_r,
            'selesai' => $d->done_r,
            'pct'     => $d->total_r > 0 ? round(($d->done_r / $d->total_r) * 100, 1) : 0,
        ])
        ->sortByDesc('pct')
        ->values();

        // ── Trend 6 bulan se-Kabupaten ────────────────────────────────────
        $trendBaseDate = $selectedMonth ? Carbon::createFromFormat('Y-m-d', "$sYear-$sMonth-01") : $now;
        $trend = collect(range(5, 0))->map(function ($i) use ($trendBaseDate, $now) {
            $month = $trendBaseDate->copy()->subMonths($i);
            $t = DB::table('reports')->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            $s = DB::table('reports')->where('status','completed')->whereMonth('updated_at',$month->month)->whereYear('updated_at',$month->year)->count();
            $l = DB::table('reports')
                ->whereNotIn('status',['completed','rejected'])
                ->whereNotNull('sla_deadline')->where('sla_deadline','<',$now)
                ->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            return [
                'bulan'         => $month->translatedFormat('F Y'),
                'bulan_short'   => $month->translatedFormat('M Y'),
                'total'         => $t,
                'selesai'       => $s,
                'terlambat'     => $l,
                'pct_terlambat' => $t > 0 ? round(($l / $t) * 100, 1) : 0,
            ];
        });

        // ── Stats untuk Gemini ────────────────────────────────────────────
        $statsForAI = [
            'total'          => $total,
            'selesai'        => $selesai,
            'sedang_diproses'=> $sedangDiproses,
            'terlambat'      => $terlambat,
            'completion_rate'=> $completionRate,
            'avg_hari'       => $avgHari,
            'kepuasan'       => $kepuasan,
            'kategori'       => $kategoriRaw->toArray(),
            'dinas_terlambat'=> $dinasTerlambat->toArray(),
            'trend'          => $trend->toArray(),
        ];

        // Format nama bulan untuk AI & Identifikasi cache ID aman (ex: 2026-07 -> 202607)
        $regentCacheId = $selectedMonth ? (int) str_replace('-', '', $selectedMonth) : 0;
        $scopeName     = 'Kabupaten Badung' . ($selectedMonth ? ' (Bulan ' . Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') . ')' : '');

        $forceRefresh = $request->boolean('refresh_ai');
        $insights = $forceRefresh
            ? $this->gemini->refreshInsights($regentCacheId, $scopeName, $statsForAI)
            : $this->gemini->getInsights($regentCacheId, $scopeName, $statsForAI);

        $cacheInfo = $this->gemini->getCacheInfo($regentCacheId);

        return view('regent.priority', compact(
            'user','regent', 'availableMonths',
            'total','selesai','sedangDiproses','terlambat','completionRate','avgHari','kepuasan',
            'kategoriRaw','dinasTerlambat','distrikStats','trend',
            'insights','cacheInfo',
        ));
    }

    public function export(Request $request)
    {
        $user   = Auth::user();
        $regent = $user->regent;
        abort_if(! $regent, 403);

        $now = Carbon::now();
        
        $selectedMonth = $request->input('month');
        $regentCacheId = $selectedMonth ? (int) str_replace('-', '', $selectedMonth) : 0;
        $scopeName     = 'Kabupaten Badung' . ($selectedMonth ? ' (Bulan ' . Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') . ')' : '');
        
        $insights   = $this->gemini->getInsights($regentCacheId, $scopeName, [
            'total' => 0,'selesai' => 0,'sedang_diproses' => 0,'terlambat' => 0,
            'completion_rate' => 0,'avg_hari' => 0,'kepuasan' => 0,
            'kategori' => [],'dinas_terlambat' => [],'trend' => [],
        ]);
        
        $filename = 'rekomendasi-prioritas-' . str_replace(' ', '-', strtolower($scopeName)) . '-' . $now->format('Ymd-His');

        return response()->streamDownload(function () use ($insights, $now, $scopeName) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($h, ['LAPORAN ANALISIS & REKOMENDASI PRIORITAS'], ';');
            fputcsv($h, ["Wilayah: {$scopeName}"], ';');
            fputcsv($h, ["Dibuat: {$now->translatedFormat('j F Y, H:i')} WITA"], ';');
            fputcsv($h, ["Sumber AI: " . ($insights['source'] ?? '—')], ';');
            fputcsv($h, [''], ';');
            fputcsv($h, ['KEY INSIGHTS'], ';');
            fputcsv($h, ['No','Tipe','Judul','Ringkasan','Detail','Data Pendukung'], ';');
            foreach (($insights['insights'] ?? []) as $i => $ins) {
                fputcsv($h, [
                    $i + 1,
                    strtoupper($ins['type'] ?? '—'),
                    $ins['title']      ?? '—',
                    $ins['summary']    ?? '—',
                    $ins['detail']     ?? '—',
                    $ins['data_point'] ?? '—',
                ], ';');
            }
            fputcsv($h, [''], ';');
            fputcsv($h, ['REKOMENDASI UTAMA'], ';');
            fputcsv($h, [$insights['rekomendasi_utama'] ?? '—'], ';');
            fclose($h);
        }, "{$filename}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}