@extends('layouts.app')
@section('title', 'Daftar Tugas — SILABA')

@section('content')

@php
$statusConfig = [
    'pending'               => ['label' => 'Menunggu',          'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
    'in_progress'           => ['label' => 'Diproses',          'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
    'waiting_for_materials' => ['label' => 'Menunggu Material', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'under_review'          => ['label' => 'Menunggu Verifikasi','bg' => 'bg-purple-100','text' => 'text-purple-700'],
    'completed'             => ['label' => 'Selesai',           'bg' => 'bg-green-100',  'text' => 'text-success'],
    'rejected'              => ['label' => 'Ditolak',           'bg' => 'bg-red-100',    'text' => 'text-error'],
];
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-green-100',  'text' => 'text-success'],
];
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-6">

        {{-- Back + Heading --}}
        <div class="mb-6">
            <a href="{{ route('employee.field-officer.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Tugas Saya</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola semua tugas yang ditugaskan kepada Anda</p>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
            <form method="GET" action="{{ route('employee.field-officer.assignments.index') }}">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                    {{-- Search --}}
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari tiket, judul, atau lokasi..."
                           class="col-span-1 sm:col-span-1 px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-10
                                  text-sm text-gray-900 placeholder-gray-400 focus:bg-white
                                  focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none transition-all">

                    {{-- Filter Status --}}
                    <select name="status"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending"               {{ request('status') === 'pending'               ? 'selected' : '' }}>Menunggu</option>
                        <option value="in_progress"           {{ request('status') === 'in_progress'           ? 'selected' : '' }}>Diproses</option>
                        <option value="waiting_for_materials" {{ request('status') === 'waiting_for_materials' ? 'selected' : '' }}>Menunggu Material</option>
                        <option value="under_review"          {{ request('status') === 'under_review'          ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="completed"             {{ request('status') === 'completed'             ? 'selected' : '' }}>Selesai</option>
                    </select>

                    {{-- Filter Prioritas --}}
                    <select name="priority"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Prioritas</option>
                        <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Mendesak</option>
                        <option value="high"     {{ request('priority') === 'high'     ? 'selected' : '' }}>Tinggi</option>
                        <option value="medium"   {{ request('priority') === 'medium'   ? 'selected' : '' }}>Sedang</option>
                        <option value="low"      {{ request('priority') === 'low'      ? 'selected' : '' }}>Rendah</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Menampilkan <span class="font-semibold text-gray-800">{{ $totalCount }}</span> tugas
                    </p>
                    @if (request()->hasAny(['search', 'status', 'priority']))
                    <a href="{{ route('employee.field-officer.assignments.index') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Reset Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Flash success --}}
        @if (session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-sm text-success mb-5">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Daftar Tugas --}}
        @if ($assignments->count() > 0)
        <div class="flex flex-col gap-4">
            @foreach ($assignments as $assignment)
            @php
            $report   = $assignment->report;
            $status   = $statusConfig[$report->status]   ?? $statusConfig['pending'];
            $priority = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
            $evidence = $report->evidences->first();
            $lastProgress = $report->progresses->first();
            $reporter = $report->user?->name ?? $report->guest_name ?? 'Tamu';

            // SLA — format manusia
            $slaText   = null;
            $slaUrgent = false;
            $slaDone   = in_array($report->status, ['completed', 'rejected']);

            if ($slaDone) {
                $slaText = 'Selesai';
            } elseif ($report->sla_deadline) {
                $deadline  = \Carbon\Carbon::parse($report->sla_deadline);
                $remaining = now()->diffInHours($deadline, false); // negatif jika terlambat

                if ($remaining <= 0) {
                    // Terlambat — format human-readable
                    $overHours = abs((int) $remaining);
                    if ($overHours < 24) {
                        $slaText = $overHours . ' jam terlambat';
                    } elseif ($overHours < 24 * 7) {
                        $d = intdiv($overHours, 24);
                        $h = $overHours % 24;
                        $slaText = $h > 0 ? "{$d} hari {$h} jam terlambat" : "{$d} hari terlambat";
                    } elseif ($overHours < 24 * 30) {
                        $w  = intdiv($overHours, 24 * 7);
                        $sd = intdiv($overHours % (24 * 7), 24);
                        $slaText = $sd > 0 ? "{$w} minggu {$sd} hari terlambat" : "{$w} minggu terlambat";
                    } else {
                        $m  = intdiv($overHours, 24 * 30);
                        $sd = intdiv($overHours % (24 * 30), 24);
                        $slaText = $sd > 0 ? "{$m} bulan {$sd} hari terlambat" : "{$m} bulan terlambat";
                    }
                    $slaUrgent = true;
                } elseif ($remaining < 1) {
                    $slaText   = '< 1 jam tersisa';
                    $slaUrgent = true;
                } elseif ($remaining < 24) {
                    $slaText   = (int)$remaining . ' jam tersisa';
                    $slaUrgent = true;
                } elseif ($remaining < 48) {
                    $slaText   = '1 hari tersisa';
                    $slaUrgent = true;
                } else {
                    $days    = (int) ceil($remaining / 24);
                    $slaText = "{$days} hari tersisa";
                    $slaUrgent = $days <= 2;
                }
            }
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-md transition-shadow">
                <div class="flex gap-0">
                    {{-- Foto --}}
                    <div class="relative w-24 sm:w-32 shrink-0">
                        @if ($evidence)
                        <img src="{{ Storage::url($evidence->file_path) }}"
                             class="w-full h-full object-cover" alt="">
                        @else
                        <div class="w-full h-full min-h-[100px] bg-gray-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        @endif
                        {{-- Status badge di foto --}}
                        <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-0.5 rounded-full
                                     {{ $status['bg'] }} {{ $status['text'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 min-w-0 p-4">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400 font-mono mb-0.5">{{ $report->code }}</p>
                                <h3 class="text-sm font-bold text-gray-900 line-clamp-2 leading-snug">
                                    {{ $report->title }}
                                </h3>
                            </div>
                            {{-- SLA badge --}}
                            @if ($slaText)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap shrink-0
                                         {{ $slaDone ? 'bg-green-100 text-success' : ($slaUrgent ? 'bg-red-100 text-error' : 'bg-gray-100 text-gray-600') }}">
                                {{ $slaText }}
                            </span>
                            @endif
                        </div>

                        {{-- Tags: Kategori + Prioritas --}}
                        <div class="flex flex-wrap gap-1.5 mb-2.5">
                            @if ($report->category)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ $report->category->name }}
                            </span>
                            @endif
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                         {{ $priority['bg'] }} {{ $priority['text'] }}">
                                {{ $priority['label'] }}
                            </span>
                        </div>

                        {{-- Meta info --}}
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 mb-2.5">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <circle cx="6" cy="4" r="2" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M2 11c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                Pelapor: {{ $reporter }}
                            </span>
                            @if ($report->location)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <path d="M6 1C4.07 1 2.5 2.57 2.5 4.5c0 2.625 3.5 6.5 3.5 6.5s3.5-3.875 3.5-6.5C9.5 2.57 7.93 1 6 1zm0 4.75a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z"
                                          stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ Str::limit($report->location, 35) }}
                            </span>
                            @endif
                            @if ($report->sla_deadline)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <rect x="1" y="1.5" width="10" height="9" rx="1" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M1 4.5h10M4 1.5v1M8 1.5v1" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                Deadline: {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y') }}
                            </span>
                            @endif
                        </div>

                        {{-- Update terakhir + tombol --}}
                        <div class="flex items-center justify-between gap-2">
                            @if ($lastProgress)
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M6 3.5V6l1.5 1.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                Update terakhir: {{ $lastProgress->created_at->translatedFormat('j M Y, H:i') }}
                            </p>
                            @else
                            <span></span>
                            @endif

                            <a href="{{ route('employee.field-officer.assignments.show', $report->code) }}"
                               class="shrink-0 px-4 py-2 rounded-xl bg-primary-500 hover:bg-primary-700
                                      text-white text-xs font-semibold transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($assignments->hasPages())
        <div class="mt-5">
            {{ $assignments->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm font-semibold text-gray-700 mb-1">Tidak ada tugas ditemukan</p>
            <p class="text-xs text-gray-400">Coba ubah filter pencarian</p>
        </div>
        @endif

    </div>
</div>

<x-employee.bottom-nav active="assignments" :badge="$statusCounts['active']" />

@endsection