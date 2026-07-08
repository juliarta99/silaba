@extends('layouts.app')
@section('title', 'Daftar Laporan — Kabupaten Badung')

@section('content')

@php
$statusConfig = [
    'pending'               => ['label' => 'Belum Ditugaskan', 'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
    'in_progress'           => ['label' => 'Diproses',         'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
    'waiting_for_materials' => ['label' => 'Menunggu Material','bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'under_review'          => ['label' => 'Ditinjau',         'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    'completed'             => ['label' => 'Selesai',          'bg' => 'bg-green-100',  'text' => 'text-success'],
    'rejected'              => ['label' => 'Ditolak',          'bg' => 'bg-red-100',    'text' => 'text-error'],
];
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-green-100',  'text' => 'text-success'],
];
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ── Header ── --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('regent.dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Laporan</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Monitoring seluruh laporan
                    <span class="font-semibold text-gray-700">Kabupaten Badung</span>
                </p>
            </div>
            {{-- Export --}}
            {{-- <a href="{{ route('regent.reports.export') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
               class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500
                      hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M8 2v8m0 0l-3-3m3 3l3-3M3 13h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export CSV
            </a> --}}
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mb-5">
            @foreach ([
                ['label' => 'Total Laporan',    'val' => $stats['total'],      'clr' => 'text-gray-500',  'ibg' => 'bg-gray-10',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Belum Ditugaskan', 'val' => $stats['unassigned'], 'clr' => 'text-gray-400',  'ibg' => 'bg-gray-10',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Diproses',         'val' => $stats['inProgress'], 'clr' => 'text-yellow-500','ibg' => 'bg-yellow-50', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Selesai',          'val' => $stats['completed'],  'clr' => 'text-success',   'ibg' => 'bg-green-50',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Terlambat',        'val' => $stats['overdue'],
                 'clr' => $stats['overdue'] > 0 ? 'text-error' : 'text-gray-300',
                 'ibg' => $stats['overdue'] > 0 ? 'bg-red-50'  : 'bg-gray-10',
                 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ] as $stat)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 sm:p-4 last:col-span-2 sm:last:col-span-1">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] sm:text-xs text-gray-400 leading-tight">{{ $stat['label'] }}</p>
                    <div class="w-7 h-7 rounded-lg {{ $stat['ibg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 {{ $stat['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $stat['icon'] }}" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 tabular-nums">{{ number_format($stat['val']) }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Filter ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
            <p class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Filter & Pencarian
            </p>
            <form method="GET" action="{{ route('regent.reports.index') }}">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 mb-3">

                    {{-- Search --}}
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari laporan..."
                           class="col-span-2 sm:col-span-1 px-3 py-2.5 rounded-xl border border-gray-200
                                  bg-gray-10 text-sm placeholder-gray-400 focus:bg-white
                                  focus:border-primary-500 focus:ring-1 focus:ring-primary-500
                                  outline-none transition-all">

                    {{-- Status --}}
                    <select name="status"
                            class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach ([
                            'pending' => 'Belum Ditugaskan', 'in_progress' => 'Diproses',
                            'waiting_for_materials' => 'Menunggu Material',
                            'under_review' => 'Ditinjau', 'completed' => 'Selesai', 'rejected' => 'Ditolak',
                        ] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>

                    {{-- Prioritas --}}
                    <select name="priority"
                            class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Prioritas</option>
                        @foreach (['critical' => 'Mendesak', 'high' => 'Tinggi', 'medium' => 'Sedang', 'low' => 'Rendah'] as $val => $lbl)
                        <option value="{{ $val }}" {{ request('priority') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>

                    {{-- Kecamatan — tambahan untuk Bupati --}}
                    <select name="district"
                            class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $d)
                        <option value="{{ $d->id }}" {{ request('district') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>

                    {{-- Kategori --}}
                    <select name="category"
                            class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                   text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                   focus:ring-primary-500 outline-none transition-all"
                            onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Hasil: <span class="font-semibold text-gray-900">{{ number_format($reports->total()) }}</span> laporan
                    </p>
                    @if (request()->hasAny(['search','status','priority','district','category']))
                    <a href="{{ route('regent.reports.index') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Reset Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── List Laporan ── --}}
        @if ($reports->count() > 0)
        <div class="flex flex-col gap-3 mb-5">
            @foreach ($reports as $report)
            @php
            $st           = $statusConfig[$report->status]     ?? $statusConfig['pending'];
            $pr           = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
            $ev           = $report->evidences->first();
            $childCount   = $report->childReports->count();
            $officerCount = $report->assignments->count();

            $slaBadgeText = null;
            $slaBadgeRed  = false;
            if ($report->sla_deadline && ! in_array($report->status, ['completed','rejected'])) {
                $remaining    = now()->diffInHours(\Carbon\Carbon::parse($report->sla_deadline), false);
                $pct          = $report->created_at
                    ? min(100, round($report->created_at->diffInHours(now()) /
                        max(1, $report->created_at->diffInHours(\Carbon\Carbon::parse($report->sla_deadline))) * 100))
                    : 0;
                $slaBadgeText = 'SLA ' . $pct . '%';
                $slaBadgeRed  = $remaining <= 0 || ($remaining <= 72 && $pct >= 80);
            } elseif ($report->status === 'completed') {
                $slaBadgeText = 'SLA 100%';
            }
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex">

                    {{-- Foto --}}
                    <div class="w-20 sm:w-24 shrink-0">
                        @if ($ev)
                        <img src="{{ Storage::url($ev->file_path) }}"
                             class="w-full h-full object-cover min-h-[110px]" alt="">
                        @else
                        <div class="w-full min-h-[110px] bg-gray-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24">
                                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        @endif
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 min-w-0 p-4">

                        <div class="mb-1.5">
                            <a href="{{ route('reports.show', $report->code) }}"
                               class="text-sm font-bold text-gray-900 hover:text-primary-600
                                      transition-colors leading-snug line-clamp-2">
                                {{ $report->title }}
                            </a>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $report->code }}</p>
                        </div>

                        <div class="flex flex-wrap gap-1.5 mb-2.5">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $st['bg'] }} {{ $st['text'] }}">
                                {{ $st['label'] }}
                            </span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $pr['bg'] }} {{ $pr['text'] }}">
                                {{ $pr['label'] }}
                            </span>
                            @if ($report->category)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                {{ $report->category->name }}
                            </span>
                            @endif
                            @if ($report->district)
                            <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">
                                {{ $report->district->name }}
                            </span>
                            @endif
                            @if ($slaBadgeText)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                         {{ $slaBadgeRed ? 'bg-red-100 text-error' : 'bg-gray-100 text-gray-600' }}">
                                {{ $slaBadgeText }}
                            </span>
                            @endif
                            @if ($childCount > 0)
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                📋 {{ $childCount }} Serupa
                            </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400 mb-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <circle cx="6" cy="4" r="2" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M2 11c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                {{ $report->user?->name ?? ($report->guest_name ?? 'Tamu') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <rect x="1" y="1.5" width="10" height="9" rx="1" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M1 4.5h10" stroke="currentColor" stroke-width="1.1"/>
                                </svg>
                                {{ $report->created_at->translatedFormat('j M Y') }}
                            </span>
                            @if ($report->sla_deadline)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M6 3.5V6l1.5 1.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                Deadline: {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y') }}
                            </span>
                            @endif
                        </div>

                        {{-- Petugas + link detail (read-only) --}}
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @if ($officerCount > 0)
                                    @foreach ($report->assignments->take(3) as $assign)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                                 bg-gray-10 border border-gray-200 text-gray-600">
                                        <svg class="w-2.5 h-2.5 text-gray-400" fill="none" viewBox="0 0 12 12">
                                            <circle cx="6" cy="4" r="2" stroke="currentColor" stroke-width="1.1"/>
                                            <path d="M2 11c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                        </svg>
                                        {{ $assign->employee?->user?->name ?? '—' }}
                                    </span>
                                    @endforeach
                                    @if ($officerCount > 3)
                                    <span class="text-xs text-gray-400">+{{ $officerCount - 3 }} lainnya</span>
                                    @endif
                                @else
                                <span class="text-xs text-gray-400 italic">Belum ada petugas</span>
                                @endif
                            </div>

                            <a href="{{ route('reports.show', $report->code) }}"
                               class="shrink-0 text-xs font-semibold text-primary-500 hover:text-primary-700
                                      transition-colors flex items-center gap-1">
                                Lihat Detail
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12">
                                    <path d="M3 6h6M6 3l3 3-3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if ($reports->hasPages())
        <div class="mb-6">{{ $reports->links() }}</div>
        @endif

        @else
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm font-semibold text-gray-700 mb-1">Tidak ada laporan ditemukan</p>
            <p class="text-xs text-gray-400">Coba ubah filter pencarian</p>
        </div>
        @endif

    </div>
</div>
@endsection