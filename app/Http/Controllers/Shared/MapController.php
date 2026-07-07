<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $role     = $user->role;
        $position = $user->employee?->position;

        $query = Report::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with([
                'category.department',
                'district',
                'assignments.employee.user',
            ]);

        // ── SCOPE berdasarkan role & posisi ───────────────────────────────

        // Petugas Lapangan → hanya laporan yang ditugaskan kepadanya
        if ($role === 'employee' && $position === 'field_officer') {
            $empId = $user->employee?->id;
            $query->whereHas('assignments', fn ($q) =>
                $q->where('employee_id', $empId)
            );
        }

        // Supervisor & Kepala Dinas → laporan di departemen mereka
        elseif ($role === 'employee' && in_array($position, ['supervisor', 'head_of_department'])) {
            $deptId = $user->employee?->department_id;
            $query->whereHas('category', fn ($q) =>
                $q->where('department_id', $deptId)
            );
        }

        // Camat (District Chief) → laporan di kecamatannya saja
        elseif ($role === 'district_chief') {
            $districtId = $user->districtChief?->district_id;
            abort_if(! $districtId, 403, 'Data kecamatan tidak ditemukan.');
            $query->where('district_id', $districtId);
        }

        // Bupati & Admin → semua laporan (tidak ada scope tambahan)
        // else: tidak ada where tambahan

        // ── User filters ──────────────────────────────────────────────────
        if ($request->filled('status'))    $query->where('status',      $request->status);
        if ($request->filled('priority'))  $query->where('priority',    $request->priority);
        if ($request->filled('district'))  $query->where('district_id', $request->district);
        if ($request->filled('category'))  $query->where('category_id', $request->category);

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reports = $query->orderByDesc('created_at')->get();

        // ── Markers JSON ──────────────────────────────────────────────────
        $markers = $reports->map(function ($r) {
            $allOfficers = $r->assignments
                ->map(fn ($a) => $a->employee?->user?->name)
                ->filter()->values()->toArray();

            return [
                'id'           => $r->id,
                'code'         => $r->code,
                'title'        => $r->title,
                'status'       => $r->status,
                'priority'     => $r->priority,
                'lat'          => (float) $r->latitude,
                'lng'          => (float) $r->longitude,
                'location'     => $r->location,
                'category'     => $r->category?->name,
                'district'     => $r->district?->name,
                'all_officers' => $allOfficers,
                'officer'      => $allOfficers[0] ?? '-',
                'color'        => $this->statusColor($r->status),
                'created_at'   => $r->created_at->translatedFormat('j M Y'),
                'url'          => $this->detailUrl($r->code),
            ];
        });

        // ── Stats ─────────────────────────────────────────────────────────
        $stats = [
            'total'      => $reports->count(),
            'pending'    => $reports->whereIn('status', ['pending'])->count(),
            'inProgress' => $reports->whereIn('status', ['in_progress','waiting_for_materials','under_review'])->count(),
            'completed'  => $reports->where('status', 'completed')->count(),
        ];

        // ── Filter options ────────────────────────────────────────────────
        $districts  = District::orderBy('name')->get();

        $catQuery = Category::orderBy('name');
        if ($role === 'employee' && in_array($position, ['supervisor','head_of_department'])) {
            $catQuery->where('department_id', $user->employee?->department_id);
        }
        $categories = $catQuery->get();

        // ── UI meta per role ──────────────────────────────────────────────
        [$dashboardRoute, $roleLabel, $scopeNote] = $this->roleMeta($role, $position, $user);

        // Tampilkan filter kecamatan? (tidak untuk camat — sudah fixed)
        $showDistrictFilter = ! ($role === 'district_chief');

        // Tampilkan filter kategori? (semua role, tapi relevan untuk camat)
        $showCategoryFilter = true;

        return view('shared.map', compact(
            'markers', 'stats', 'districts', 'categories',
            'dashboardRoute', 'roleLabel', 'scopeNote',
            'showDistrictFilter', 'showCategoryFilter',
            'role', 'position'
        ));
    }

    // ── Warna marker per status ───────────────────────────────────────────
    private function statusColor(string $status): string
    {
        return match($status) {
            'pending'               => 'red',
            'in_progress'           => 'blue',
            'waiting_for_materials' => 'orange',
            'under_review'          => 'yellow',
            'completed'             => 'green',
            'rejected'              => 'gray',
            default                 => 'red',
        };
    }

    // ── URL detail laporan sesuai role ────────────────────────────────────
    private function detailUrl(string $code): string
    {
        $user     = Auth::user();
        $role     = $user->role;
        $position = $user->employee?->position;

        return match(true) {
            $role === 'regent'
                => route('regent.reports.show', $code),

            $role === 'district_chief'
                => route('reports.show', $code),

            $role === 'employee' && $position === 'field_officer'
                => route('employee.field-officer.assignments.show', $code),

            $role === 'employee' && in_array($position, ['supervisor','head_of_department'])
                => route('employee.supervisor.reports.show', $code),

            default => route('reports.show', $code),
        };
    }

    // ── Meta UI per role ──────────────────────────────────────────────────
    private function roleMeta(string $role, ?string $position, $user): array
    {
        return match(true) {

            $role === 'regent' => [
                route('regent.dashboard'),
                'Bupati Badung',
                'Menampilkan semua laporan di Kabupaten Badung',
            ],

            $role === 'district_chief' => [
                route('district-chief.dashboard'),
                'Camat — ' . ($user->districtChief?->district?->name ?? ''),
                'Menampilkan laporan di kecamatan ' . ($user->districtChief?->district?->name ?? ''),
            ],

            $role === 'employee' && $position === 'head_of_department' => [
                route('employee.head.dashboard'),
                'Kepala Dinas — ' . ($user->employee?->department?->name ?? ''),
                'Menampilkan laporan di lingkup ' . ($user->employee?->department?->name ?? ''),
            ],

            $role === 'employee' && $position === 'supervisor' => [
                route('employee.supervisor.dashboard'),
                'Supervisor — ' . ($user->employee?->department?->name ?? ''),
                'Menampilkan laporan di lingkup ' . ($user->employee?->department?->name ?? ''),
            ],

            $role === 'employee' && $position === 'field_officer' => [
                route('employee.field-officer.dashboard'),
                'Petugas Lapangan',
                'Menampilkan laporan yang ditugaskan kepada Anda',
            ],

            default => [route('home'), 'SILABU', 'Peta sebaran laporan'],
        };
    }
}