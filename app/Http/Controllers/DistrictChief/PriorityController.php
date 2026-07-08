<?php

namespace App\Http\Controllers\DistrictChief;

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

    // ── Halaman utama ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user          = Auth::user();
        $districtChief = $user->districtChief;
        abort_if(! $districtChief, 403);

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district?->name ?? '—';
        $now          = Carbon::now();

        $base = fn () => Report::where('district_id', $districtId);

        // ── Statistik umum ────────────────────────────────────────────────
        $total         = ($base)()->count();
        $selesai       = ($base)()->where('status','completed')->count();
        $sedangDiproses= ($base)()->whereIn('status',['in_progress','waiting_for_materials','under_review'])->count();
        $terlambat     = ($base)()->whereNotIn('status',['completed','rejected'])
                                  ->whereNotNull('sla_deadline')
                                  ->where('sla_deadline','<',$now)->count();
        $completionRate= $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

        $avgH = DB::table('reports')
            ->where('district_id', $districtId)
            ->where('status','completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_h')
            ->value('avg_h');
        $avgHari = $avgH ? round($avgH / 24, 1) : 0;

        $avgRating = DB::table('reviews')
            ->join('reports','reports.id','=','reviews.report_id')
            ->where('reports.district_id', $districtId)
            ->avg('reviews.rating');
        $kepuasan = $avgRating ? round(($avgRating / 5) * 100, 1) : 0;

        // ── Kategori terbanyak ────────────────────────────────────────────
        $kategoriRaw = DB::table('reports')
            ->join('categories','categories.id','=','reports.category_id')
            ->where('reports.district_id', $districtId)
            ->selectRaw('
                categories.id,
                categories.name,
                COUNT(*) as total,
                SUM(CASE WHEN reports.status="completed" THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN reports.status NOT IN ("completed","rejected") AND reports.sla_deadline IS NOT NULL AND reports.sla_deadline < NOW() THEN 1 ELSE 0 END) as terlambat,
                AVG(CASE WHEN reports.status="completed" THEN TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at) END) as avg_jam
            ')
            ->groupBy('categories.id','categories.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($r) => [
                'id'         => $r->id,
                'nama'       => $r->name,
                'total'      => (int)$r->total,
                'selesai'    => (int)$r->selesai,
                'terlambat'  => (int)$r->terlambat,
                'completion' => $r->total > 0 ? round(($r->selesai / $r->total) * 100, 1) : 0,
                'avg_hari'   => $r->avg_jam ? round($r->avg_jam / 24, 1) : 0,
                'pct_of_total'=> $total > 0 ? round(($r->total / $total) * 100, 1) : 0,
            ]);

        // ── Dinas paling banyak terlambat di kecamatan ini ────────────────
        $dinasTerlambat = Department::get()->map(function ($dept) use ($districtId, $now) {
            $base = DB::table('reports')
                ->join('categories','categories.id','=','reports.category_id')
                ->where('categories.department_id', $dept->id)
                ->where('reports.district_id', $districtId);

            $total     = (clone $base)->count();
            if ($total === 0) return null;

            $terlambat = (clone $base)
                ->whereNotIn('reports.status',['completed','rejected'])
                ->whereNotNull('reports.sla_deadline')
                ->where('reports.sla_deadline','<',$now->toDateTimeString())
                ->count();

            $avgH = (clone $base)
                ->where('reports.status','completed')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, reports.created_at, reports.updated_at)) as avg_h')
                ->value('avg_h');

            $petugas = DB::table('employees')
                ->where('department_id', $dept->id)
                ->where('position','field_officer')->count();
            $petugasAktif = DB::table('employees')
                ->where('department_id', $dept->id)
                ->where('position','field_officer')->where('status','active')->count();

            return [
                'id'           => $dept->id,
                'nama'         => $dept->name,
                'total'        => $total,
                'terlambat'    => $terlambat,
                'pct_terlambat'=> $total > 0 ? round(($terlambat / $total) * 100, 1) : 0,
                'avg_hari'     => $avgH ? round($avgH / 24, 1) : 0,
                'petugas_total'=> $petugas,
                'petugas_aktif'=> $petugasAktif,
            ];
        })
        ->filter(fn ($d) => $d !== null && $d['terlambat'] > 0)
        ->sortByDesc('pct_terlambat')
        ->take(3)
        ->values();

        // ── Distribusi per kecamatan (semua kecamatan, highlight kita) ────
        // Untuk camat: tampilkan distribusi dari kecamatannya ke perbandingan kabupaten
        $distrikStats = District::withCount([
            'reports as total_r' => fn ($q) => $q,
            'reports as done_r'  => fn ($q) => $q->where('status','completed'),
        ])
        ->having('total_r','>',0)
        ->get()
        ->map(fn ($d) => [
            'id'        => $d->id,
            'nama'      => $d->name,
            'total'     => $d->total_r,
            'selesai'   => $d->done_r,
            'pct'       => $d->total_r > 0 ? round(($d->done_r / $d->total_r) * 100, 1) : 0,
            'is_mine'   => $d->id === $districtId,
        ])
        ->sortByDesc('pct')
        ->values();

        // ── Trend 6 bulan ─────────────────────────────────────────────────
        $trend = collect(range(5, 0))->map(function ($i) use ($districtId, $now) {
            $month = $now->copy()->subMonths($i);
            $t = DB::table('reports')->where('district_id',$districtId)
                ->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            $s = DB::table('reports')->where('district_id',$districtId)->where('status','completed')
                ->whereMonth('updated_at',$month->month)->whereYear('updated_at',$month->year)->count();
            $l = DB::table('reports')->where('district_id',$districtId)
                ->whereNotIn('status',['completed','rejected'])
                ->whereNotNull('sla_deadline')->where('sla_deadline','<',$now)
                ->whereMonth('created_at',$month->month)->whereYear('created_at',$month->year)->count();
            return [
                'bulan'        => $month->translatedFormat('F Y'),
                'bulan_short'  => $month->translatedFormat('M Y'),
                'total'        => $t,
                'selesai'      => $s,
                'terlambat'    => $l,
                'pct_terlambat'=> $t > 0 ? round(($l/$t)*100,1) : 0,
            ];
        });

        // ── Kumpulkan stats untuk Gemini ──────────────────────────────────
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

        // ── Gemini Insights (di-cache 5 jam) ─────────────────────────────
        $forceRefresh = $request->boolean('refresh_ai');
        $insights = $forceRefresh
            ? $this->gemini->refreshInsights($districtId, $districtName, $statsForAI)
            : $this->gemini->getInsights($districtId, $districtName, $statsForAI);

        $cacheInfo = $this->gemini->getCacheInfo($districtId);

        return view('district_chief.priority', compact(
            'user','districtChief','districtName',
            'total','selesai','sedangDiproses','terlambat','completionRate','avgHari','kepuasan',
            'kategoriRaw','dinasTerlambat','distrikStats','trend',
            'insights','cacheInfo','statsForAI'
        ));
    }

    // ── Export PDF/Excel ──────────────────────────────────────────────────
    public function export(Request $request)
    {
        $user          = Auth::user();
        $districtChief = $user->districtChief;
        abort_if(! $districtChief, 403);

        $districtId   = $districtChief->district_id;
        $districtName = $districtChief->district?->name ?? '—';
        $now          = Carbon::now();

        $format = $request->get('format', 'csv');

        // Ambil insights dari cache (tidak generate ulang)
        $statsForAI = session("priority_stats_{$districtId}", []);
        $insights   = $this->gemini->getInsights($districtId, $districtName, $statsForAI ?: [
            'total' => 0, 'selesai' => 0, 'sedang_diproses' => 0, 'terlambat' => 0,
            'completion_rate' => 0, 'avg_hari' => 0, 'kepuasan' => 0,
            'kategori' => [], 'dinas_terlambat' => [], 'trend' => [],
        ]);

        $filename = 'rekomendasi-prioritas-' . str($districtName)->slug() . '-' . $now->format('Ymd-His');

        if ($format === 'json') {
            return response()->json([
                'kecamatan'    => $districtName,
                'generated_at' => $now->toIso8601String(),
                'insights'     => $insights,
                'source'       => $insights['source'] ?? 'unknown',
            ])->withHeaders(['Content-Disposition' => "attachment; filename={$filename}.json"]);
        }

        // Default: CSV
        return response()->streamDownload(function () use ($districtName, $insights, $now) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            fputcsv($h, ['LAPORAN ANALISIS & REKOMENDASI PRIORITAS'], ';');
            fputcsv($h, ["Kecamatan: {$districtName}"], ';');
            fputcsv($h, ["Dibuat: {$now->translatedFormat('j F Y, H:i')} WITA"], ';');
            fputcsv($h, ["Sumber AI: " . ($insights['source'] ?? '—')], ';');
            fputcsv($h, [''], ';');

            fputcsv($h, ['KEY INSIGHTS'], ';');
            fputcsv($h, ['No', 'Tipe', 'Judul', 'Ringkasan', 'Detail', 'Data Pendukung'], ';');
            foreach (($insights['insights'] ?? []) as $i => $insight) {
                fputcsv($h, [
                    $i + 1,
                    strtoupper($insight['type'] ?? '—'),
                    $insight['title']      ?? '—',
                    $insight['summary']    ?? '—',
                    $insight['detail']     ?? '—',
                    $insight['data_point'] ?? '—',
                ], ';');
            }

            fputcsv($h, [''], ';');
            fputcsv($h, ['REKOMENDASI UTAMA'], ';');
            fputcsv($h, [$insights['rekomendasi_utama'] ?? '—'], ';');
            fputcsv($h, [''], ';');
            fputcsv($h, ["Laporan ini dibuat otomatis oleh sistem SILABA menggunakan AI."], ';');
            fclose($h);
        }, "{$filename}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}