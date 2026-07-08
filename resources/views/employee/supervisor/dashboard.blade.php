@extends('layouts.app')
@section('title', 'Dashboard Supervisor — SILABA')

@section('content')

@php
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$medals = [
    '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 27.395 27.395 0 0 0 2.208.036v1.442a3.326 3.326 0 0 0-2.112 1.345 3.323 3.323 0 0 0-1.085 2.126.75.75 0 0 0 .322.756c.429.278.882.525 1.353.738l.617.27a.75.75 0 0 0 .916-.164l2.126-2.48a.75.75 0 0 0 .177-.488v-3.56a27.395 27.395 0 0 0 2.208-.036 6.753 6.753 0 0 0 6.138-5.6.75.75 0 0 0-.584-.859 4.316 4.316 0 0 0-3.071-.543v-.858a.75.75 0 0 0-.75-.75H5.916a.75.75 0 0 0-.75.75Zm-1.666 2.947c-.963.21-1.9.47-2.81.776a5.253 5.253 0 0 0 4.57 4.195 28.91 28.91 0 0 1-1.76-4.971Zm15 0c.91.306 1.847.565 2.81.776a5.253 5.253 0 0 1-4.57 4.195 28.91 28.91 0 0 0 1.76-4.971Z" clip-rule="evenodd"/>
    </svg>',
    '<svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 27.395 27.395 0 0 0 2.208.036v1.442a3.326 3.326 0 0 0-2.112 1.345 3.323 3.323 0 0 0-1.085 2.126.75.75 0 0 0 .322.756c.429.278.882.525 1.353.738l.617.27a.75.75 0 0 0 .916-.164l2.126-2.48a.75.75 0 0 0 .177-.488v-3.56a27.395 27.395 0 0 0 2.208-.036 6.753 6.753 0 0 0 6.138-5.6.75.75 0 0 0-.584-.859 4.316 4.316 0 0 0-3.071-.543v-.858a.75.75 0 0 0-.75-.75H5.916a.75.75 0 0 0-.75.75Zm-1.666 2.947c-.963.21-1.9.47-2.81.776a5.253 5.253 0 0 0 4.57 4.195 28.91 28.91 0 0 1-1.76-4.971Zm15 0c.91.306 1.847.565 2.81.776a5.253 5.253 0 0 1-4.57 4.195 28.91 28.91 0 0 0 1.76-4.971Z" clip-rule="evenodd"/>
    </svg>',
    '<svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 27.395 27.395 0 0 0 2.208.036v1.442a3.326 3.326 0 0 0-2.112 1.345 3.323 3.323 0 0 0-1.085 2.126.75.75 0 0 0 .322.756c.429.278.882.525 1.353.738l.617.27a.75.75 0 0 0 .916-.164l2.126-2.48a.75.75 0 0 0 .177-.488v-3.56a27.395 27.395 0 0 0 2.208-.036 6.753 6.753 0 0 0 6.138-5.6.75.75 0 0 0-.584-.859 4.316 4.316 0 0 0-3.071-.543v-.858a.75.75 0 0 0-.75-.75H5.916a.75.75 0 0 0-.75.75Zm-1.666 2.947c-.963.21-1.9.47-2.81.776a5.253 5.253 0 0 0 4.57 4.195 28.91 28.91 0 0 1-1.76-4.971Zm15 0c.91.306 1.847.565 2.81.776a5.253 5.253 0 0 1-4.57 4.195 28.91 28.91 0 0 0 1.76-4.971Z" clip-rule="evenodd"/>
    </svg>',
];
@endphp

{{-- ════ HERO HEADER ════ --}}
<div class="bg-primary-500 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold leading-snug">Dashboard Supervisor</h1>
                <p class="text-sm text-primary-200 mt-0.5">Kelola dan pantau tugas laporan instansi</p>
            </div>
            {{-- Profile chip --}}
            <div class="shrink-0 text-right bg-white/15 border border-white/30 rounded-2xl px-4 py-3">
                <p class="text-xs text-primary-200">Selamat datang,</p>
                <p class="text-sm font-bold text-white leading-snug">{{ $user->name }}</p>
                <p class="text-xs text-primary-200 mt-0.5">Supervisor Dinas</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- ════ STAT CARDS ROW 1 ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 -mt-5 mb-4">

            {{-- Total Laporan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 16 16">
                        <path d="M3 10l4-6 4 4 3-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tabular-nums">{{ $totalLaporan }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Total Laporan</p>
            </div>

            {{-- Sedang Diproses --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-blue-600 tabular-nums">{{ $sedangDiproses }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Sedang Diproses</p>
            </div>

            {{-- Selesai Bulan Ini --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    @if ($growthPct !== null)
                    <span class="text-xs font-semibold {{ $growthPct >= 0 ? 'text-success' : 'text-error' }} flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                            <path d="{{ $growthPct >= 0 ? 'M2 9l4-6 4 4 2-4' : 'M2 3l4 6 4-4 2 4' }}"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ abs($growthPct) }}%
                    </span>
                    @endif
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-success tabular-nums">{{ $selesaiBulanIni }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Selesai Bulan Ini</p>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white rounded-2xl border {{ $terlambat > 0 ? 'border-red-100' : 'border-gray-100' }} shadow-sm p-4 sm:p-5">
                <div class="w-9 h-9 rounded-xl {{ $terlambat > 0 ? 'bg-red-50 border-red-100' : 'bg-gray-10 border-gray-100' }}
                            border flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 {{ $terlambat > 0 ? 'text-error' : 'text-gray-300' }}" fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold {{ $terlambat > 0 ? 'text-error' : 'text-gray-300' }} tabular-nums">{{ $terlambat }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Terlambat</p>
            </div>
        </div>

        {{-- ════ STAT CARDS ROW 2 (warna) ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            <div class="bg-primary-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-primary-200 mb-1">Rata-rata Waktu Selesai</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $avgDays }} <span class="text-base font-semibold">hari</span></p>
            </div>
            <div class="bg-green-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-green-100 mb-1">Tingkat Kepuasan</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $satisfactionPct }}<span class="text-base font-semibold">%</span></p>
            </div>
            <div class="bg-blue-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-blue-100 mb-1">Total Petugas</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $totalPetugas }}</p>
            </div>
            <div class="bg-yellow-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-yellow-100 mb-1">Petugas Aktif</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $petugasAktif }}</p>
            </div>
        </div>

        {{-- ════ AKSI CEPAT ════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                $quickActions = [
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg' => 'bg-red-50', 'clr' => 'text-primary-500', 'label' => 'Lihat Laporan', 'sub' => 'Semua laporan', 'route' => 'employee.supervisor.reports.index'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'bg' => 'bg-blue-50', 'clr' => 'text-blue-500', 'label' => 'Penugasan', 'sub' => 'Kelola petugas', 'route' => 'employee.supervisor.assignments.index'],
                    ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'bg' => 'bg-purple-50', 'clr' => 'text-purple-500', 'label' => 'Peta Sebaran', 'sub' => 'Visualisasi lokasi', 'route' => 'employee.supervisor.reports.map'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'bg' => 'bg-green-50', 'clr' => 'text-success', 'label' => 'Performa', 'sub' => 'Analitik instansi', 'route' => 'employee.supervisor.reviews.index'],
                ];
                @endphp
                @foreach ($quickActions as $action)
                <a href="{{ route($action['route']) }}"
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-gray-100
                          {{ $action['bg'] }} hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 {{ $action['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $action['icon'] }}" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $action['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $action['sub'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- ════ MAIN 2-COL GRID ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── KOLOM KIRI ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Laporan Perlu Perhatian --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-gray-900">Laporan Perlu Perhatian</h2>
                        <a href="{{ route('employee.supervisor.reports.index') }}"
                           class="text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                                <path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>

                    @if ($urgentReports->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach ($urgentReports as $report)
                        @php
                        $p          = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
                        $hasAssign  = $report->assignments->count() > 0;
                        $officerNames = $report->assignments->take(2)
                            ->map(fn ($a) => $a->employee?->user?->name)
                            ->filter()->implode(', ');
                        $totalAssign  = $report->assignments->count();

                        // SLA badge
                        $slaExpired = false;
                        $slaBadge   = null;
                        if ($report->sla_deadline) {
                            $deadline  = \Carbon\Carbon::parse($report->sla_deadline);
                            $remaining = now()->diffInHours($deadline, false);
                            if ($remaining <= 0) {
                                $slaBadge   = 'SLA';
                                $slaExpired = true;
                            } elseif ($remaining <= 24 * 3) {
                                $slaBadge = 'SLA';
                            }
                        }
                        @endphp

                        <a href="{{ route('employee.supervisor.reports.index') }}?code={{ $report->code }}"
                           class="flex flex-col gap-2 py-3.5 first:pt-0 last:pb-0
                                  hover:bg-gray-10 -mx-5 px-5 transition-colors">

                            {{-- Row 1: kode + SLA badge + duplikat badge + prioritas --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs text-gray-400 font-mono">{{ $report->code }}</span>
                                @if ($slaBadge)
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-500 text-white">
                                    SLA
                                </span>
                                @endif
                                @if ($report->childReports?->count() > 0)
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                             bg-blue-100 text-blue-700 flex items-center gap-1">
                                    📋 {{ $report->childReports->count() }} Laporan
                                </span>
                                @endif
                                <span class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-full
                                             {{ $p['bg'] }} {{ $p['text'] }}">
                                    {{ $p['label'] }}
                                </span>
                            </div>

                            {{-- Row 2: Judul --}}
                            <p class="text-sm font-bold text-gray-900 leading-snug">{{ $report->title }}</p>

                            {{-- Row 3: Meta --}}
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                                @if ($report->category)
                                <span>{{ $report->category->name }}</span>
                                @endif
                                @if ($report->district)
                                <span>• {{ $report->district->name }}</span>
                                @endif
                                <span>• {{ $report->created_at->translatedFormat('j M Y') }}</span>
                            </div>

                            {{-- Row 4: Petugas / Belum ditugaskan --}}
                            <div class="flex items-center gap-2">
                                @if ($hasAssign)
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 14 14">
                                        <circle cx="7" cy="5" r="2.5" stroke="currentColor" stroke-width="1.1"/>
                                        <path d="M1.5 13c0-3.038 2.462-5.5 5.5-5.5s5.5 2.462 5.5 5.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                    </svg>
                                    Tim:
                                    <span class="font-medium text-gray-700">{{ $totalAssign }} Petugas</span>
                                    @if ($officerNames)
                                    <span class="text-gray-400">({{ $officerNames }}{{ $totalAssign > 2 ? ', ...' : '' }})</span>
                                    @endif
                                </div>
                                @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold
                                             text-error bg-red-50 px-2 py-0.5 rounded-full">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                        <circle cx="6" cy="5" r="2" stroke="currentColor" stroke-width="1.1"/>
                                        <path d="M2 11.5c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                    </svg>
                                    Belum ada petugas
                                </span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-10">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada laporan yang perlu perhatian khusus</p>
                    </div>
                    @endif
                </div>

                {{-- Statistik per Kecamatan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Statistik per Kecamatan</h2>
                    @if ($districtStats->count() > 0)
                    <div class="space-y-4">
                        @foreach ($districtStats as $dist)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $dist->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $dist->completed_reports }} selesai dari {{ $dist->total_reports }} laporan
                                    </p>
                                </div>
                                <span class="text-sm font-bold {{ $dist->completion_pct >= 90 ? 'text-success' : ($dist->completion_pct >= 70 ? 'text-yellow-600' : 'text-error') }} tabular-nums">
                                    {{ $dist->completion_pct }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-primary-500 transition-all"
                                     style="width: {{ $dist->completion_pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-6">Belum ada data statistik kecamatan</p>
                    @endif
                </div>

            </div>

            {{-- ── KOLOM KANAN ── --}}
            <div class="space-y-5">

                {{-- Profil Supervisor --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 text-primary-600 text-2xl font-bold
                                flex items-center justify-center mx-auto mb-3 overflow-hidden">
                        @if ($user->picture)
                        <img src="{{ Storage::url($user->picture) }}"
                             class="w-full h-full object-cover" alt="{{ $user->name }}">
                        @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">NIP: {{ $employee->nip }}</p>
                    <p class="text-xs font-medium text-gray-600 mt-0.5">Supervisor Dinas</p>
                    <div class="border-t border-gray-50 mt-4 pt-4 text-left space-y-2">
                        <div>
                            <p class="text-xs text-gray-400">OPD</p>
                            <p class="text-sm font-semibold text-gray-900 leading-snug">
                                {{ $employee->department?->name ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Top Petugas Bulan Ini --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Top Petugas Bulan Ini
                    </h2>

                    @if ($topOfficers->count() > 0)
                    <div class="space-y-3.5">
                        @foreach ($topOfficers as $idx => $officer)
                        <div class="flex items-center gap-3">
                            <div class="shrink-0">
                                {!! $medals[$idx] ?? '' !!}
                            </div>

                            {{-- Avatar --}}
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-xs font-bold text-gray-500 overflow-hidden">
                                @if ($officer->user?->picture)
                                <img src="{{ Storage::url($officer->user->picture) }}" class="w-full h-full object-cover" alt="">
                                @else
                                {{ strtoupper(substr($officer->user?->name ?? '?', 0, 1)) }}
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $officer->user?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $officer->completed_tasks }}/{{ $officer->total_tasks }} tugas
                                    @if ($officer->avg_days) • {{ $officer->avg_days }} hari @endif
                                </p>
                                @if ($officer->satisfaction)
                                <p class="text-xs font-semibold text-yellow-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 pb-[0.5px]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $officer->satisfaction }}%
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada data petugas bulan ini</p>
                    @endif
                </div>

                {{-- Periode Laporan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.75"/>
                            <path d="M3 9h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                        Periode Laporan
                    </h2>
                    <p class="text-xl font-bold text-primary-500 text-center mb-1">
                        {{ now()->translatedFormat('F Y') }}
                    </p>
                    <p class="text-xs text-gray-400 text-center mb-4">Periode aktif</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">Bulan Ini</p>
                            <p class="text-xl font-bold text-gray-900 tabular-nums">{{ $periodeBulanIni }}</p>
                        </div>
                        <div class="text-center px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">Bulan Lalu</p>
                            <p class="text-xl font-bold text-gray-900 tabular-nums">{{ $periodeBulanLalu }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection