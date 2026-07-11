@extends('layouts.app')

@section('title', $report->code . ' — Detail Laporan SILABA')

@section('content')

@php
$statusLabel = [
    'pending'               => 'Baru',
    'in_progress'           => 'Diproses',
    'under_review'          => 'Menunggu Verifikasi',
    'waiting_for_materials' => 'Menunggu Material',
    'completed'             => 'Selesai',
    'rejected'              => 'Ditolak',
][$report->status] ?? 'Baru';

$statusBadge = [
    'pending'               => 'bg-blue-100 text-blue-700',
    'in_progress'           => 'bg-yellow-100 text-yellow-700',
    'under_review'          => 'bg-orange-100 text-orange-700',
    'waiting_for_materials' => 'bg-purple-100 text-purple-700',
    'completed'             => 'bg-green-100 text-green-700',
    'rejected'              => 'bg-red-100 text-red-700',
][$report->status] ?? 'bg-gray-100 text-gray-700';

$priorityLabel = [
    'low'      => 'Rendah',
    'medium'   => 'Sedang',
    'high'     => 'Tinggi',
    'critical' => 'Kritis',
][$report->priority] ?? 'Sedang';

$priorityBadge = [
    'low'      => 'bg-gray-100 text-gray-600',
    'medium'   => 'bg-blue-100 text-blue-700',
    'high'     => 'bg-orange-100 text-orange-700',
    'critical' => 'bg-red-100 text-red-700',
][$report->priority] ?? 'bg-blue-100 text-blue-700';

$childReports = $report->childReports ?? collect();
$isGrouped    = $childReports->count() > 0;

// SLA
$slaRemaining = null;
$slaOverdue   = false;
if ($report->sla_deadline) {
    $slaOverdue   = now()->gt($report->sla_deadline);
    $diff         = now()->diff($report->sla_deadline);
    $slaRemaining = $slaOverdue
        ? 'Terlambat ' . $diff->days . ' hari'
        : $diff->days . ' hari lagi';
}

$progresses        = $report->progresses()->latest()->with(['employee.user', 'employee.department'])->get();
$assignedEmployees = $report->assignments()->with(['employee.user', 'employee.department'])->get();
$evidences         = $report->evidences;
$tags              = $report->tags;
$category          = $report->category;
$department        = $category?->department;

$needsVerification = $isOwner && $report->status === 'under_review';
$needsRating       = $isOwner && $report->status === 'completed' && !$report->review;
@endphp

{{-- Leaflet CSS — inline di sini, tidak bergantung @push/@stack --}}
@if ($report->latitude && $report->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endif

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ════ BACK + HEADER ════ --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500
                      hover:text-gray-800 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali
            </a>

            <div class="flex flex-wrap items-start gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            {{ $report->code }}
                        </h1>
                        <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $statusBadge }}">
                            {{ $statusLabel }}
                        </span>
                        @if ($report->priority !== 'medium')
                        <span class="text-sm font-semibold px-3 py-1 rounded-full {{ $priorityBadge }}">
                            Prioritas {{ $priorityLabel }}
                        </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500">{{ $category?->name ?? '—' }}</p>
                </div>

                {{-- Tombol aksi pemilik --}}
                @if ($needsVerification || $needsRating)
                <div class="flex flex-col sm:flex-row gap-2 shrink-0">
                    @if ($needsVerification)
                    <form method="POST" action="{{ route('citizen.reports.confirm', $report->code) }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-success
                                       hover:opacity-90 text-white text-sm font-semibold transition-opacity">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Konfirmasi Selesai
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('citizen.reports.reject-completion', $report->code) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700
                                       text-sm font-semibold hover:bg-gray-10 transition-colors">
                            Belum Selesai
                        </button>
                    </form>
                    @endif
                    @if ($needsRating)
                    <a href="{{ route('citizen.reports.rate', $report->code) }}"
                       class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-yellow-400
                              hover:bg-yellow-500 text-gray-900 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M8 1l1.9 4.5L15 6l-3.6 3.2L12.4 14 8 11.5 3.6 14l1-4.8L1 6l5.1-.5L8 1z"/>
                        </svg>
                        Beri Rating
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Banner perlu verifikasi --}}
        @if ($needsVerification)
        <div class="flex items-start gap-3 px-5 py-4 rounded-2xl bg-orange-50
                    border-2 border-orange-200 mb-5">
            <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center
                         justify-center shrink-0 text-xs font-bold mt-0.5">!</span>
            <div>
                <p class="text-sm font-semibold text-orange-800">Perlu Verifikasi</p>
                <p class="text-sm text-orange-700 mt-0.5">
                    Petugas telah menyatakan laporan ini selesai. Mohon konfirmasi apakah
                    masalah benar-benar sudah teratasi.
                </p>
            </div>
        </div>
        @endif

        {{-- ════ GRID 2 KOLOM ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">

            {{-- ════ LEFT ════ --}}
            <div class="flex flex-col gap-5">

                {{-- Banner Laporan Gabungan --}}
                @if ($isGrouped)
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-orange-500 shrink-0" fill="none"
                             viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100 8 4 4 0 000-8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                                  stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold text-sm text-orange-800">
                            Laporan Gabungan dari {{ $childReports->count() + 1 }} Warga
                        </span>
                        @if (in_array($report->priority, ['high', 'critical']))
                        <span class="flex items-center gap-1 text-xs font-medium text-orange-700 ml-auto">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 1v8M8 11v1" stroke="currentColor" stroke-width="2"
                                      stroke-linecap="round"/>
                            </svg>
                            Prioritas Naik
                        </span>
                        @endif
                    </div>

                    <p class="text-sm text-orange-700 mb-4 leading-relaxed">
                        Sistem mendeteksi {{ $childReports->count() + 1 }} laporan serupa dalam
                        radius 50 meter dan menggabungkannya. Prioritas otomatis dinaikkan.
                    </p>

                    <div class="bg-white rounded-xl border border-orange-100 p-4 mb-3">
                        <p class="text-xs font-semibold text-gray-600 mb-3">
                            Detail Laporan yang Digabungkan:
                        </p>
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                                    <span class="text-xs font-mono text-gray-400 shrink-0">
                                        {{ $report->code }}-A
                                    </span>
                                    <span class="text-sm font-medium text-primary-600 truncate">
                                        {{ $report->user?->name ?? $report->guest_name ?? 'Tamu' }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">
                                    {{ $report->created_at->translatedFormat('j M Y, H:i') }}
                                    <span class="text-gray-300">• 0m</span>
                                </span>
                            </div>
                            @foreach ($childReports as $i => $child)
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-2 h-2 rounded-full bg-primary-300 shrink-0"></span>
                                    <span class="text-xs font-mono text-gray-400 shrink-0">
                                        {{ $report->code }}-{{ chr(66 + $i) }}
                                    </span>
                                    <span class="text-sm font-medium text-primary-600 truncate">
                                        {{ $child->user?->name ?? $child->guest_name ?? 'Tamu' }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">
                                    {{ $child->created_at->translatedFormat('j M Y, H:i') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- @if ($isOwner)
                    <div class="bg-white rounded-xl border border-orange-100 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-orange-400 shrink-0" fill="none"
                                 viewBox="0 0 16 16" aria-hidden="true">
                                <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/>
                                <path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.3"
                                      stroke-linecap="round"/>
                            </svg>
                            <span class="text-sm font-semibold text-orange-700">
                                Laporan Anda Salah Digabungkan?
                            </span>
                        </div>
                        <p class="text-xs text-orange-600 mb-3 leading-relaxed">
                            Jika Anda merasa laporan ini berbeda, Anda dapat mengajukan pemisahan.
                        </p>
                        <form method="POST"
                              action="{{ route('citizen.reports.dispute', $report->code) }}"
                              onsubmit="return confirm('Yakin ingin melaporkan bahwa ini bukan duplikat?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg
                                           border border-orange-300 text-sm font-medium text-orange-700
                                           hover:bg-orange-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                    <circle cx="8" cy="8" r="6.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.3"
                                          stroke-linecap="round"/>
                                </svg>
                                Laporkan Bukan Duplikat
                            </button>
                        </form>
                    </div>
                    @endif --}}
                </div>
                @endif

                {{-- Detail Laporan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-1">Detail Laporan</h2>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $report->title }}</h3>

                    {{-- @if ($tags->count() > 0)
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-400 mb-2">AI Analysis Tags:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                            <span class="text-xs font-medium px-3 py-1.5 rounded-full
                                         border border-blue-200 text-blue-700 bg-blue-50">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif --}}

                    <p class="text-sm text-gray-700 leading-relaxed mb-5">
                        {{ $report->description }}
                    </p>

                    @if ($evidences->count() > 0)
                    <div>
                        <p class="text-sm font-semibold text-gray-900 flex items-center gap-1.5 mb-3">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 16 16"
                                 aria-hidden="true">
                                <rect x="1.5" y="1.5" width="13" height="13" rx="2"
                                      stroke="currentColor" stroke-width="1.3"/>
                                <path d="M1.5 11l3.5-3.5 2.5 2.5 2-2 4.5 4.5" stroke="currentColor"
                                      stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="5.5" cy="5.5" r="1" fill="currentColor"/>
                            </svg>
                            Foto/Video Bukti ({{ $evidences->count() }})
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach ($evidences as $ev)
                            <a href="{{ Storage::url($ev->file_path) }}" target="_blank" rel="noopener"
                               class="block aspect-video rounded-xl overflow-hidden bg-gray-100
                                      hover:opacity-90 transition-opacity">
                                @if ($ev->file_type === 'photo')
                                    <img src="{{ Storage::url($ev->file_path) }}" alt="Bukti"
                                     loading="lazy" class="w-full h-full object-cover">
                                @else
                                    {{-- Video thumbnail dengan play button overlay --}}
                                    <div class="relative w-full h-full bg-gray-900 flex items-center justify-center group">
                                        {{-- Frame pertama video sebagai thumbnail --}}
                                        <video src="{{ Storage::url($ev->file_path) }}"
                                            class="absolute inset-0 w-full h-full object-cover opacity-60"
                                            muted preload="metadata">
                                        </video>
                                        {{-- Overlay gelap agar icon kontras --}}
                                        <div class="absolute inset-0 bg-black/30"></div>
                                        {{-- Play button --}}
                                        <div class="relative z-10 w-12 h-12 rounded-full bg-white/90 flex items-center
                                                    justify-center shadow-lg group-hover:bg-white transition-colors">
                                            <svg class="w-5 h-5 text-gray-900 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                        {{-- Badge VIDEO --}}
                                        <span class="absolute bottom-2 left-2 z-10 text-[10px] font-bold text-white
                                                    bg-black/60 px-1.5 py-0.5 rounded">VIDEO</span>
                                    </div>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Riwayat Progress --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 mb-5">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                             aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Riwayat Progress
                    </h2>

                    @if ($progresses->count() > 0)
                    <div class="relative">
                        <div class="absolute left-5 top-5 bottom-5 w-px bg-gray-100"></div>
                        <div class="flex flex-col gap-5">
                            @foreach ($progresses as $prog)
                            @php
                            $dotColor = match($prog->status) {
                                'completed'   => 'bg-success',
                                'rejected'    => 'bg-error',
                                'in_progress' => 'bg-yellow-400',
                                default       => 'bg-gray-300',
                            };
                            @endphp
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full {{ $dotColor }} text-white
                                            flex items-center justify-center shrink-0 z-10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16"
                                         aria-hidden="true">
                                        <path d="M3 8l3.5 3.5L13 5" stroke="currentColor"
                                              stroke-width="2" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pb-1">
                                    <div class="flex flex-wrap items-start justify-between gap-1 mb-1">
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            {{ $prog->title }}
                                        </h4>
                                        <span class="text-xs text-gray-400 whitespace-nowrap">
                                            {{ $prog->created_at->translatedFormat('j M Y, H:i') }}
                                        </span>
                                    </div>
                                    @if ($prog->description)
                                    <p class="text-sm text-gray-600 leading-relaxed mb-1.5">
                                        {{ $prog->description }}
                                    </p>
                                    @endif
                                    @if ($prog->photo)
                                        <a href="{{ Storage::url($prog->photo) }}" target="_blank"
                                        class="block w-20 h-16 rounded-lg overflow-hidden mb-2 hover:opacity-90 transition-opacity">
                                            <img src="{{ Storage::url($prog->photo) }}" class="w-full h-full object-cover" alt="">
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="none"
                                             viewBox="0 0 12 12" aria-hidden="true">
                                            <circle cx="6" cy="6" r="5" stroke="currentColor"
                                                    stroke-width="1.2"/>
                                            <path d="M6 3.5V6l1.5 1.5" stroke="currentColor"
                                                  stroke-width="1.2" stroke-linecap="round"/>
                                        </svg>
                                        {{ $prog->employee?->user?->name ?? 'Sistem' }}
                                        @if ($prog->employee?->position)
                                        · {{ match($prog->employee->position) {
                                            'field_officer'      => 'Petugas Lapangan',
                                            'supervisor'         => 'Supervisor',
                                            'head_of_department' => 'Kepala Dinas',
                                            default              => 'Petugas',
                                        } }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-8">
                        Belum ada riwayat progress untuk laporan ini.
                    </p>
                    @endif
                </div>

            </div>{{-- end left --}}

            {{-- ════ RIGHT SIDEBAR ════ --}}
            <div class="flex flex-col gap-5">

                {{-- SLA --}}
                @if ($report->sla_deadline)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-900 mb-3">Informasi SLA</h3>
                    <div class="space-y-2.5">
                        <div>
                            <p class="text-xs text-gray-400">Batas Waktu</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->sla_deadline->translatedFormat('j M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Sisa Waktu</p>
                            <p class="text-sm font-bold {{ $slaOverdue
                                ? 'text-error'
                                : ($report->sla_deadline->diffInDays(now()) <= 2
                                    ? 'text-orange-500' : 'text-success') }}">
                                {{ $slaRemaining }}
                            </p>
                        </div>
                        @php
                        $totalDays = max(1, $report->created_at->diffInDays($report->sla_deadline));
                        $usedDays  = $report->created_at->diffInDays(now());
                        $pct       = min(100, round($usedDays / $totalDays * 100));
                        $barColor  = $slaOverdue ? 'bg-error' : ($pct >= 80 ? 'bg-orange-400' : 'bg-success');
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="{{ $barColor }} h-1.5 rounded-full"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tim Petugas --}}
                @if ($assignedEmployees->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                             aria-hidden="true">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM2 8a2 2 0 114 0A2 2 0 012 8zM14 18v-1a5 5 0 00-10 0v1H2v-1a7 7 0 0116 0v1h-4z"/>
                        </svg>
                        Tim Petugas ({{ $assignedEmployees->count() }})
                    </h3>
                    <div class="flex flex-col gap-3">
                        @foreach ($assignedEmployees as $assign)
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden
                                        flex items-center justify-center shrink-0">
                                @if ($assign->employee->user?->picture)
                                <img src="{{ Storage::url($assign->employee->user->picture) }}"
                                     alt="{{ $assign->employee->user->name }}"
                                     class="w-full h-full object-cover">
                                @else
                                <span class="text-xs font-bold text-gray-500">
                                    {{ strtoupper(substr($assign->employee->user?->name ?? 'P', 0, 2)) }}
                                </span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ $assign->employee->user?->name ?? '—' }}
                                </p>
                                <div class="flex flex-wrap gap-1 mt-0.5">
                                    @if ($assign->employee->department)
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                                 bg-primary-50 text-primary-500 font-medium">
                                        {{ $assign->employee->department->name }}
                                    </span>
                                    @endif
                                    @if ($assign->employee->position)
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                                 bg-gray-100 text-gray-600 font-medium">
                                        {{ match($assign->employee->position) {
                                            'field_officer'      => 'Lapangan',
                                            'supervisor'         => 'Supervisor',
                                            'head_of_department' => 'Kepala Dinas',
                                            default              => $assign->employee->position,
                                        } }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Informasi Pelapor --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                             aria-hidden="true">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zM2 17a8 8 0 1116 0H2z"/>
                        </svg>
                        Informasi Pelapor
                        @if ($childReports->count() > 0)
                            ({{ $childReports->count() + 1 }})
                        @endif
                    </h3>
                    <div class="space-y-3">
                        <div>
                            @if ($childReports->count() > 0)
                            <p class="text-xs text-gray-400 mb-0.5">Pelapor 1</p>
                            @endif
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->user?->name ?? $report->guest_name ?? 'Tamu' }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 12 12"
                                     aria-hidden="true">
                                    <rect x="1" y="1.5" width="10" height="9" rx="1"
                                          stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M1 4.5h10M4 1.5v1M8 1.5v1" stroke="currentColor"
                                          stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                {{ $report->created_at->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        @foreach ($childReports as $i => $child)
                        <div class="border-t border-gray-50 pt-3">
                            <p class="text-xs text-gray-400 mb-0.5">Pelapor {{ $i + 2 }}</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $child->user?->name ?? $child->guest_name ?? 'Tamu' }}
                            </p>
                            <p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 12 12"
                                     aria-hidden="true">
                                    <rect x="1" y="1.5" width="10" height="9" rx="1"
                                          stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M1 4.5h10M4 1.5v1M8 1.5v1" stroke="currentColor"
                                          stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                {{ $child->created_at->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ════ LOKASI + MINI MAP ════ --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 16 16"
                             aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8 1.5a5.5 5.5 0 00-5.5 5.5c0 3.513 4.424 7.976 5.14 8.67a.5.5 0 00.72 0C9.076 14.976 13.5 10.513 13.5 7A5.5 5.5 0 008 1.5zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Lokasi Kejadian
                    </h3>

                    <div class="space-y-2.5 mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Alamat</p>
                            <p class="text-sm font-semibold text-gray-900 leading-snug">
                                {{ $report->location }}
                            </p>
                        </div>
                        @if ($report->latitude && $report->longitude)
                        <div>
                            <p class="text-xs text-gray-400">Koordinat</p>
                            <p class="text-sm font-mono text-gray-700">
                                {{ $report->latitude }}, {{ $report->longitude }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if ($report->latitude && $report->longitude)
                    {{-- Container map — dimensi via inline style supaya tidak bisa di-override CSS lain --}}
                    <div style="width:100%;height:160px;border-radius:12px;border:1px solid #e5e7eb;
                                margin-bottom:12px;overflow:hidden;position:relative;background:#f3f4f6;">
                        <div id="detail-map"
                             style="width:100%;height:100%;position:relative;z-index:0;"></div>
                    </div>

                    {{-- Leaflet JS + init — inline langsung setelah container, tanpa @push --}}
                    <script>
                    (function () {
                        var LAT = {{ (float) $report->latitude }};
                        var LNG = {{ (float) $report->longitude }};

                        function buildMap() {
                            var el = document.getElementById('detail-map');
                            if (!el || el.dataset.mapReady === '1') return;
                            el.dataset.mapReady = '1';

                            var map = L.map(el, {
                                zoomControl:        true,
                                dragging:           true,
                                scrollWheelZoom:    false,
                                doubleClickZoom:    false,
                                touchZoom:          false,
                                attributionControl: false,
                            }).setView([LAT, LNG], 15);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                            }).addTo(map);

                            L.marker([LAT, LNG]).addTo(map);

                            setTimeout(function () { map.invalidateSize(); }, 200);
                        }

                        function loadAndBuild() {
                            if (window.L && window.L.map) {
                                buildMap();
                                return;
                            }
                            var s = document.createElement('script');
                            s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                            s.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                            s.crossOrigin = '';
                            s.onload = buildMap;
                            document.head.appendChild(s);
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', loadAndBuild);
                        } else {
                            requestAnimationFrame(function () {
                                requestAnimationFrame(loadAndBuild);
                            });
                        }
                    }());
                    </script>

                    <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}"
                       target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl
                              bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold
                              transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M6 2H2.5A.5.5 0 002 2.5V14a.5.5 0 00.5.5H10a.5.5 0 00.5-.5v-3"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                            <path d="M14 2H9.5A.5.5 0 009 2.5V7M14 2v4.5M14 2l-5 5"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                        Buka di Google Maps
                    </a>
                    @endif
                </div>

                {{-- OPD Berwenang --}}
                @if ($department)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-3">OPD Berwenang</h3>
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M2 14V6a1 1 0 011-1h10a1 1 0 011 1v8M1 14h14M6 13V9h4v4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $department->name }}</p>
                                <p class="text-xs text-gray-500">{{ $department->phone ?? 'Belum ada kontak' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Lihat Detail --}}
                    <a href="{{ route('departments.show', $department->id) }}" 
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors shrink-0">
                        Lihat Detail OPD
                        <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                @endif

                {{-- Notifikasi WA (laporan gabungan) --}}
                @if ($childReports->count() > 0)
                <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-success shrink-0" fill="none" viewBox="0 0 16 16"
                             aria-hidden="true">
                            <path d="M14 8A6 6 0 112 8a6 6 0 0112 0z" stroke="currentColor"
                                  stroke-width="1.3"/>
                            <path d="M5.5 8.5L7 10l3.5-4" stroke="currentColor" stroke-width="1.3"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Notifikasi WhatsApp</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Semua {{ $childReports->count() + 1 }} pelapor akan menerima update
                        otomatis via WhatsApp.
                    </p>
                </div>
                @endif

                {{-- CTA untuk pengunjung tidak login --}}
                @guest
                <div class="bg-primary-50 border border-primary-100 rounded-2xl p-5 text-center">
                    <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                        Ingin melaporkan masalah yang sama atau memantau laporan Anda?
                    </p>
                    <a href="{{ route('register') }}"
                       class="block w-full py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                              text-white text-sm font-semibold transition-colors mb-2">
                        Daftar Akun
                    </a>
                    <a href="{{ route('login') }}"
                       class="block w-full py-2.5 rounded-xl border border-primary-200
                              text-primary-600 text-sm font-semibold hover:bg-white transition-colors">
                        Masuk
                    </a>
                </div>
                @endguest

            </div>{{-- end right --}}

        </div>
    </div>
</div>

@endsection