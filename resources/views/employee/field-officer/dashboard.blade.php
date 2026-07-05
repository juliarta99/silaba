@extends('layouts.app')
@section('title', 'Dashboard Petugas — SILABU')

@section('content')

@php
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error',        'border' => 'border-red-200'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700',   'border' => 'border-orange-200'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700',   'border' => 'border-yellow-200'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-gray-100',   'text' => 'text-gray-600',     'border' => 'border-gray-200'],
];
@endphp

{{-- ════ HERO HEADER ════ --}}
<div class="bg-primary-500 text-white py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-20 pb-8 sm:pb-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold leading-snug">Dashboard Petugas</h1>
                <p class="text-sm text-gray-200 mb-0.5 font-medium">Kelola dan pantau tugas laporan Anda</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 min-h-[calc(100vh-68px)] pt-12 pb-24 sm:pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Tips --}}
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl
                    {{ $terlambat > 0 ? 'bg-red-50 border border-red-100' : 'bg-blue-50 border border-blue-100' }}">
            <svg class="w-4 h-4 {{ $terlambat > 0 ? 'text-error' : 'text-blue-500' }} shrink-0 mt-0.5"
                    fill="none" viewBox="0 0 24 24">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm {{ $terlambat > 0 ? 'text-red-700' : 'text-blue-700' }} leading-relaxed">
                <span class="font-semibold">Tips:</span> {{ $tips }}
            </p>
        </div>

        {{-- ════ STAT CARDS ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5 mb-6">

            {{-- Total Tugas --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100
                                flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 14 14">
                        <path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tabular-nums">{{ $totalTugas }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Total Tugas</p>
            </div>

            {{-- Sedang Dikerjakan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100
                            flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                        <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-blue-600 tabular-nums">{{ $sedangDikerjakan }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Sedang Dikerjakan</p>
            </div>

            {{-- Selesai --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="w-9 h-9 rounded-xl bg-green-50 border border-green-100
                            flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-success tabular-nums">{{ $selesai }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Selesai</p>
            </div>

            {{-- Terlambat --}}
            <div class="bg-white rounded-2xl border {{ $terlambat > 0 ? 'border-red-100' : 'border-gray-100' }} shadow-sm p-4 sm:p-5">
                <div class="w-9 h-9 rounded-xl {{ $terlambat > 0 ? 'bg-red-50 border-red-100' : 'bg-gray-50 border-gray-100' }}
                            border flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 {{ $terlambat > 0 ? 'text-error' : 'text-gray-300' }}"
                         fill="none" viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-2xl sm:text-3xl font-bold {{ $terlambat > 0 ? 'text-error' : 'text-gray-300' }} tabular-nums">
                    {{ $terlambat }}
                </p>
                <p class="text-xs text-gray-400 mt-0.5">Terlambat</p>
            </div>
        </div>

        {{-- ════ MAIN CONTENT ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── KOLOM KIRI (2/3) ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Aksi Cepat --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('employee.field-officer.assignments.index') }}"
                           class="flex items-center gap-3 px-4 py-3.5 rounded-xl border hover:border-primary-500
                                  hover:bg-primary-500 bg-primary-100 border-primary-100 transition-colors group">
                            <div class="w-9 h-9 rounded-xl bg-primary-50 border border-gray-200
                                        flex items-center justify-center shrink-0
                                        group-hover:border-primary-500 transition-colors">
                                <svg class="w-4.5 h-4.5 text-primary-500 transition-colors"
                                     fill="none" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-white transition-colors leading-snug">
                                    Lihat Semua Tugas
                                </p>
                                <p class="text-xs text-gray-700 group-hover:text-white">Daftar tugas yang ditugaskan</p>
                            </div>
                        </a>
                        <a href="{{ route('employee.profile') }}"
                           class="flex items-center gap-3 px-4 py-3.5 rounded-xl border hover:border-primary-500
                                  hover:bg-primary-500 bg-primary-100 border-primary-100 transition-colors group">
                            <div class="w-9 h-9 rounded-xl bg-primary-50 border border-gray-200
                                        flex items-center justify-center shrink-0
                                        group-hover:border-primary-500 transition-colors">
                                <svg class="w-4.5 h-4.5 text-primary-500 transition-colors"
                                     fill="none" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-white transition-colors leading-snug">
                                    Profil Saya
                                </p>
                                <p class="text-xs text-gray-700 group-hover:text-white">Kelola profil petugas</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Tugas Aktif Prioritas --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 pb-0 overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-gray-900">Tugas Aktif Prioritas</h2>
                        <a href="{{ route('employee.field-officer.assignments.index') }}"
                           class="text-sm font-semibold text-primary-500 hover:text-primary-700
                                  transition-colors flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                                <path d="M3 7h8M8 4l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>

                    @if ($activeAssignments->count() > 0)
                    <div class="flex flex-col divide-y divide-gray-50">
                        @foreach ($activeAssignments as $assignment)
                        @php
                        $report   = $assignment->report;
                        $p        = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
                        $evidence = $report->evidences->first();

                        // Hitung SLA
                        $slaText   = null;
                        $slaUrgent = false;
                        if ($report->sla_deadline) {
                            $deadline = \Carbon\Carbon::parse($report->sla_deadline);
                            if (now()->gt($deadline)) {
                                $slaText   = 'Terlambat';
                                $slaUrgent = true;
                            } elseif (now()->diffInHours($deadline) < 24) {
                                $h         = now()->diffInHours($deadline);
                                $slaText   = $h > 0 ? "SLA: {$h} jam" : 'SLA: < 1 jam';
                                $slaUrgent = true;
                            } else {
                                $d         = now()->diffInDays($deadline);
                                $slaText   = "SLA: {$d} hari";
                                $slaUrgent = $d <= 2;
                            }
                        }

                        $reporterName = $report->user?->name ?? $report->guest_name ?? 'Tamu';
                        @endphp

                        <a href="{{ route('employee.field-officer.assignments.show', $report->code) }}"
                           class="flex items-start gap-3 sm:gap-4 py-4
                                  hover:bg-gray-50 -mx-5 px-5 transition-colors">

                            {{-- Foto --}}
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                @if ($evidence)
                                <img src="{{ Storage::url($evidence->file_path) }}"
                                     class="w-full h-full object-cover" alt="">
                                @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-0.5">
                                    <p class="text-xs text-gray-400 font-mono">{{ $report->code }}</p>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full shrink-0
                                                 {{ $p['bg'] }} {{ $p['text'] }} border {{ $p['border'] }}">
                                        {{ $p['label'] }}
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-gray-900 line-clamp-1 mb-1">{{ $report->title }}</p>
                                <p class="text-xs text-gray-500 line-clamp-1 mb-2">
                                    {{ $report->location ?? $report->district?->name . ', Badung' }}
                                </p>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                            <circle cx="6" cy="4" r="2" stroke="currentColor" stroke-width="1.1"/>
                                            <path d="M2 11c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                        </svg>
                                        {{ $reporterName }}
                                    </span>
                                    @if ($report->sla_deadline)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                            <rect x="1" y="1.5" width="10" height="9" rx="1" stroke="currentColor" stroke-width="1.1"/>
                                            <path d="M1 4.5h10M4 1.5v1M8 1.5v1" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                        </svg>
                                        Deadline: {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y') }}
                                    </span>
                                    @if ($slaText)
                                    <span class="font-semibold px-2 py-0.5 rounded-full
                                                 {{ $slaUrgent ? 'bg-red-100 text-error' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $slaText }}
                                    </span>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-success" fill="none" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-700 mb-1">Tidak ada tugas aktif</p>
                        <p class="text-xs text-gray-400">Semua tugas telah diselesaikan!</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── KOLOM KANAN (1/3) ── --}}
            <div class="space-y-5">

                {{-- Profil Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-20 h-20 rounded-2xl bg-gray-100 border border-gray-200
                                flex items-center justify-center overflow-hidden mx-auto mb-3">
                        @if ($user->picture)
                            <img src="{{ Storage::url($user->picture) }}"
                                 class="w-full h-full object-cover" alt="{{ $user->name }}">
                        @else
                            <span class="text-2xl font-bold text-gray-300 select-none">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-base font-bold text-gray-900 mb-0.5">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-400 mb-0.5">NIP: {{ $employee->nip }}</p>
                    <p class="text-xs font-medium text-gray-600 mb-4">Petugas Lapangan</p>

                    <div class="text-left border-t border-gray-50 pt-4 space-y-2.5">
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">OPD</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $employee->department?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Kontak</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $employee->phone ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Status</p>
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold
                                         px-2.5 py-1 rounded-full
                                         {{ $employee->status === 'active' ? 'bg-green-100 text-success' : 'bg-gray-100 text-gray-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                             {{ $employee->status === 'active' ? 'bg-success' : 'bg-gray-400' }}"></span>
                                {{ $employee->status === 'active' ? 'Aktif' : ucfirst($employee->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Aktivitas Terbaru --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Aktivitas Terbaru</h2>

                    @if ($recentActivities->count() > 0)
                    <div class="space-y-3.5">
                        @foreach ($recentActivities as $act)
                        @php
                        $actConfig = match($act->status) {
                            'completed'   => ['bg' => 'bg-green-100',  'clr' => 'text-success',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'in_progress' => ['bg' => 'bg-blue-100',   'clr' => 'text-blue-600',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            default       => ['bg' => 'bg-gray-100',   'clr' => 'text-gray-500',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        };
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl {{ $actConfig['bg'] }}
                                        flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 {{ $actConfig['clr'] }}" fill="none" viewBox="0 0 24 24">
                                    <path d="{{ $actConfig['icon'] }}" stroke="currentColor" stroke-width="1.75"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $act->title }}</p>
                                @if ($act->report)
                                <a href="{{ route('employee.field-officer.assignments.show', $act->report->code) }}"
                                   class="text-xs text-primary-500 font-mono hover:underline">
                                    {{ $act->report->code }}
                                </a>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $act->created_at->translatedFormat('j M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href=""
                    class="mt-6 block text-center text-sm font-semibold text-primary-500
                            hover:text-primary-700 transition-colors">
                        Lihat Semua Aktivitas
                    </a>
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════ MOBILE BOTTOM NAV ════ --}}
<x-employee.bottom-nav active="dashboard" :badge="$sedangDikerjakan" />

@endsection