@extends('layouts.app')
@section('title', 'Komparasi Instansi — Kecamatan ' . $districtName)

@section('content')

@php
$medals  = ['🥇','🥈','🥉'];
$presets = [
    ''           => 'Semua Periode',
    'today'      => 'Hari Ini',
    '7days'      => '7 Hari Terakhir',
    '30days'     => '30 Hari Terakhir',
    'this_month' => 'Bulan Ini',
    'last_month' => 'Bulan Lalu',
    'this_year'  => 'Tahun Ini',
    'custom'     => 'Kustom...',
];
$currentPreset = request('preset','');
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16"
     x-data="{ showCustomDate: {{ ($currentPreset === 'custom' || (request('date_from') && !request('preset'))) ? 'true' : 'false' }} }">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ── Header ── --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('district-chief.dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Komparasi Instansi</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Perbandingan performa seluruh OPD di
                    <span class="font-semibold text-gray-700">Kecamatan {{ $districtName }}</span>
                </p>
            </div>
            <a href="{{ route('district-chief.reports.compare.export') . '?' . http_build_query(request()->except('_token')) }}"
               class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500
                      hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M8 2v8m0 0l-3-3m3 3l3-3M3 13h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export Excel
            </a>
        </div>

        {{-- ── KPI Summary ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 mb-5">
            @foreach ([
                ['label' => 'Total OPD',          'val' => $totalOPD,                                                         'clr' => 'text-gray-900',  'ibg' => 'bg-gray-10',   'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['label' => 'Rata-rata Completion','val' => ($avgCompletion ? number_format($avgCompletion, 1) : '—') . '%',   'clr' => 'text-blue-600',  'ibg' => 'bg-blue-50',   'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['label' => 'Rata-rata Waktu',     'val' => ($avgWaktu ? number_format($avgWaktu, 1) : '—') . ' hari',        'clr' => 'text-yellow-600','ibg' => 'bg-yellow-50', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Rata-rata Kepuasan',  'val' => ($avgKepuasan ? number_format($avgKepuasan, 1) : '—') . '%',      'clr' => 'text-success',   'ibg' => 'bg-green-50',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Perlu Perhatian',     'val' => $perluPerhatian,
                 'clr' => $perluPerhatian > 0 ? 'text-error' : 'text-gray-300',
                 'ibg' => $perluPerhatian > 0 ? 'bg-red-50' : 'bg-gray-10',
                 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
            ] as $s)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 sm:p-4 last:col-span-2 sm:last:col-span-1">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] sm:text-xs text-gray-400 leading-tight">{{ $s['label'] }}</p>
                    <div class="w-7 h-7 rounded-lg {{ $s['ibg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 {{ $s['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $s['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-lg sm:text-xl font-bold {{ $s['clr'] }} tabular-nums leading-tight">{{ $s['val'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ── Filter & Pengurutan ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Filter & Pengurutan
            </h2>
            <form method="GET" action="{{ route('district-chief.reports.compare') }}">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">

                    {{-- Preset Periode --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Periode</label>
                        <select name="preset"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                x-on:change="
                                    showCustomDate = ($event.target.value === 'custom');
                                    if ($event.target.value !== 'custom') $el.form.submit();
                                ">
                            @foreach ($presets as $val => $lbl)
                            <option value="{{ $val }}" {{ $currentPreset === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort By --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Urutkan Berdasarkan</label>
                        <select name="sort_by"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                onchange="this.form.submit()">
                            @foreach ([
                                'completion' => 'Completion Rate',
                                'total'      => 'Total Laporan',
                                'selesai'    => 'Jumlah Selesai',
                                'waktu'      => 'Rata-rata Waktu',
                                'kepuasan'   => 'Kepuasan',
                                'petugas'    => 'Petugas Aktif',
                                'trend'      => 'Trend',
                            ] as $val => $lbl)
                            <option value="{{ $val }}" {{ $sortBy === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort Direction --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Urutan</label>
                        <select name="sort_dir"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all"
                                onchange="this.form.submit()">
                            <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Tertinggi Dulu</option>
                            <option value="asc"  {{ $sortDir === 'asc'  ? 'selected' : '' }}>Terendah Dulu</option>
                        </select>
                    </div>
                </div>

                {{-- Custom Date Range (collapsible) --}}
                <div x-show="showCustomDate" x-cloak>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Dari Tanggal</label>
                            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10
                                          text-sm text-gray-900 focus:bg-white focus:border-primary-500
                                          focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10
                                          text-sm text-gray-900 focus:bg-white focus:border-primary-500
                                          focus:ring-1 focus:ring-primary-500 outline-none transition-all">
                        </div>
                    </div>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-semibold transition-colors">
                        Terapkan Filter
                    </button>
                </div>

                {{-- Info periode aktif --}}
                @if ($dateFrom || $dateTo || $currentPreset)
                <div class="mt-3 flex items-center justify-between">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-primary-400" fill="none" viewBox="0 0 14 14">
                            <rect x="1" y="1.5" width="12" height="11" rx="1" stroke="currentColor" stroke-width="1.1"/>
                            <path d="M1 5h12M4.5 1v2M9.5 1v2" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                        </svg>
                        @if ($currentPreset && isset($presets[$currentPreset]))
                            Periode: <strong>{{ $presets[$currentPreset] }}</strong>
                        @endif
                        @if ($dateFrom && $dateTo)
                            <span>{{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('j M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('j M Y') }}</span>
                        @endif
                    </p>
                    <a href="{{ route('district-chief.reports.compare') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Reset Filter
                    </a>
                </div>
                @endif

            </form>
        </div>

        {{-- ── Tabel Komparasi ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-10">
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide w-10">Rank</th>
                            <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Instansi</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Laporan</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Selesai</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[120px]">Completion</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Rata-rata Waktu</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kepuasan</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Petugas</th>
                            <th class="text-center px-3 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($data as $idx => $d)
                        @php
                        $rank       = $idx + 1;
                        $isTop3     = $rank <= 3;
                        $rateColor  = $d->completionRate >= 90 ? 'text-success' : ($d->completionRate >= 85 ? 'text-yellow-600' : 'text-error');
                        $barColor   = $d->completionRate >= 90 ? 'bg-success'   : ($d->completionRate >= 85 ? 'bg-yellow-400'   : 'bg-error');
                        $trendStr   = $d->trend !== null
                            ? ($d->trend > 0 ? '+' . $d->trend : (string)$d->trend) . '%'
                            : '—';
                        $trendColor = $d->trend === null ? 'text-gray-400'
                            : ($d->trend > 0 ? 'text-success' : ($d->trend < 0 ? 'text-error' : 'text-gray-400'));
                        $trendIcon  = $d->trend === null ? '•'
                            : ($d->trend > 0 ? '↗' : ($d->trend < 0 ? '↘' : '→'));
                        @endphp
                        <tr class="hover:bg-gray-10 transition-colors {{ $d->completionRate < 85 ? 'bg-red-50/30' : '' }}">

                            {{-- Rank --}}
                            <td class="px-4 py-4 text-center">
                                @if ($isTop3)
                                <span class="text-base">{{ $medals[$idx] }}</span>
                                @else
                                <span class="text-sm font-semibold text-gray-400">#{{ $rank }}</span>
                                @endif
                            </td>

                            {{-- Instansi --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center text-[10px]
                                                font-bold text-white
                                                {{ $isTop3 ? 'bg-primary-500' : 'bg-gray-300' }}">
                                        {{ $d->abbr }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 leading-snug text-sm">{{ $d->dept->name }}</p>
                                        @if ($d->dept->categories->first())
                                        <span class="inline-block mt-0.5 text-[10px] font-medium px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">
                                            {{ $d->dept->categories->first()->name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="px-3 py-4 text-center font-semibold text-gray-700">{{ $d->total }}</td>

                            {{-- Selesai --}}
                            <td class="px-3 py-4 text-center">
                                <p class="font-bold text-success">{{ $d->selesai }}</p>
                                <p class="text-[10px] text-gray-400">
                                    {{ $d->proses }} proses,
                                    {{ $d->terlambat }} terlambat
                                </p>
                            </td>

                            {{-- Completion + Bar --}}
                            <td class="px-3 py-4">
                                <p class="text-center font-bold text-base {{ $rateColor }} mb-1">
                                    {{ $d->completionRate }}%
                                </p>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full {{ $barColor }} transition-all"
                                         style="width:{{ $d->completionRate }}%"></div>
                                </div>
                            </td>

                            {{-- Rata-rata Waktu --}}
                            <td class="px-3 py-4 text-center">
                                <span class="font-semibold text-gray-700">
                                    {{ $d->avgDays !== null ? $d->avgDays . ' hari' : '—' }}
                                </span>
                            </td>

                            {{-- Kepuasan --}}
                            <td class="px-3 py-4 text-center">
                                <span class="font-semibold text-gray-700">
                                    {{ $d->kepuasan !== null ? $d->kepuasan . '%' : '—' }}
                                </span>
                            </td>

                            {{-- Petugas Aktif/Total --}}
                            <td class="px-3 py-4 text-center text-xs text-gray-500">
                                <span class="font-semibold text-gray-700">{{ $d->petugasAktif }}</span>/{{ $d->petugasTotal }}
                                <p class="text-[10px] text-gray-400">Aktif/Total</p>
                            </td>

                            {{-- Trend --}}
                            <td class="px-3 py-4 text-center">
                                <span class="text-sm font-bold {{ $trendColor }}">
                                    {{ $trendIcon }} {{ $trendStr }}
                                </span>
                            </td>
                        </tr>
                        @endforeach

                        @if ($data->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center py-12 text-sm text-gray-400">
                                Tidak ada data OPD untuk kecamatan ini
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Bottom Section: Distribusi + Insight ── --}}
        @if ($data->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Distribusi Performa --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-bold text-gray-900 mb-4">Distribusi Performa</h2>
                @php
                $totalDept = $distribusi['excellent'] + $distribusi['good'] + $distribusi['poor'];
                @endphp
                <div class="space-y-4">
                    @foreach ([
                        ['label' => 'Excellent (≥90%)', 'count' => $distribusi['excellent'], 'bar' => 'bg-success', 'text' => 'text-success'],
                        ['label' => 'Good (85–89%)',    'count' => $distribusi['good'],      'bar' => 'bg-yellow-400', 'text' => 'text-yellow-600'],
                        ['label' => 'Need Improvement (<85%)', 'count' => $distribusi['poor'], 'bar' => 'bg-error', 'text' => 'text-error'],
                    ] as $dist)
                    @php $pct = $totalDept > 0 ? round(($dist['count'] / $totalDept) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-700">{{ $dist['label'] }}</span>
                            <span class="text-sm font-bold {{ $dist['text'] }}">{{ $dist['count'] }} OPD</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $dist['bar'] }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Insight --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-bold text-gray-900 mb-4">Insight Komparasi</h2>
                <div class="space-y-3">

                    {{-- Top Performer --}}
                    @if ($topPerformer)
                    <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-green-50 border border-green-100">
                        <svg class="w-4 h-4 text-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-success mb-0.5">Top Performer</p>
                            <p class="text-xs text-green-700 leading-relaxed">
                                <strong>{{ $topPerformer->dept->name }}</strong>
                                memimpin dengan completion rate
                                <strong>{{ $topPerformer->completionRate }}%</strong>
                                dan kepuasan <strong>{{ $topPerformer->kepuasan ?? '—' }}%</strong>
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Perlu Perhatian --}}
                    @if ($perluPerhatian > 0)
                    <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-red-50 border border-red-100">
                        <svg class="w-4 h-4 text-error shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-error mb-0.5">Perlu Perhatian</p>
                            <p class="text-xs text-red-700 leading-relaxed">
                                <strong>{{ $perluPerhatian }} OPD</strong> memiliki completion rate di bawah 85%.
                                Diperlukan evaluasi dan perbaikan sistem.
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- Gap Performa --}}
                    <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <p class="text-sm font-bold text-blue-700 mb-0.5">Gap Performa</p>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Selisih antara OPD terbaik dan terburuk adalah
                                <strong>{{ $gap }}%</strong>.
                                {{ $gap <= 10 ? 'Gap sudah baik, pertahankan konsistensi.' : 'Target: kurangi gap menjadi <10%.' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        @endif

    </div>
</div>
@endsection