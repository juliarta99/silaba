@extends('layouts.app')
@section('title', 'Dashboard Kepala Dinas — SILABU')

@section('content')

@php
$priorityConfig = [
    'critical' => ['label' => 'Prioritas Darurat', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',            'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',            'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',            'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$statusConfig = [
    'pending'               => ['label' => 'Belum Ditugaskan', 'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
    'in_progress'           => ['label' => 'Diproses',         'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
    'waiting_for_materials' => ['label' => 'Menunggu Material','bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'under_review'          => ['label' => 'Ditinjau',         'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    'completed'             => ['label' => 'Selesai',          'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$medals = ['🥇','🥈','🥉'];
@endphp

{{-- ════ HERO ════ --}}
<div class="bg-primary-500 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold">Dashboard Kepala Dinas</h1>
                <p class="text-sm text-primary-200 mt-0.5">Pantau performa dan laporan dinas secara real-time</p>
            </div>
            <div class="shrink-0 text-right bg-white/15 border border-white/30 rounded-2xl px-4 py-3">
                <p class="text-xs text-primary-200">Selamat datang,</p>
                <p class="text-sm font-bold leading-snug">{{ $user->name }}</p>
                <p class="text-xs text-primary-200 mt-0.5">Kepala Dinas</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-50 min-h-[calc(100vh-68px)] pb-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- ════ KPI ROW 1 ════ --}}
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
                        <path d="M3 10l4-6 4 4 2-3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
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
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
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
                <div class="w-9 h-9 rounded-xl {{ $terlambat > 0 ? 'bg-red-50 border-red-100' : 'bg-gray-50 border-gray-100' }}
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

        {{-- ════ KPI ROW 2 (warna) ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            <div class="bg-primary-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-primary-200 mb-1">Rata-rata Waktu Selesai</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $avgDays }} <span class="text-base font-semibold">hari</span></p>
            </div>
            <div class="bg-green-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-green-100 mb-1">Tingkat Kepuasan</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $kepuasan }}<span class="text-base font-semibold">%</span></p>
            </div>
            <div class="bg-blue-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-blue-100 mb-1">Tingkat Kepatuhan</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $kepatuhan }}<span class="text-base font-semibold">%</span></p>
            </div>
            <div class="bg-yellow-500 rounded-2xl p-4 sm:p-5 text-white">
                <p class="text-xs text-yellow-100 mb-1">SLA Compliance</p>
                <p class="text-2xl sm:text-3xl font-bold tabular-nums">{{ $slaCompliance }}<span class="text-base font-semibold">%</span></p>
            </div>
        </div>

        {{-- ════ AKSI CEPAT ════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ([
                    ['route' => 'employee.head.reports.index',    'label' => 'Semua Laporan',  'sub' => 'Lihat detail',          'bg' => 'bg-red-50',    'clr' => 'text-primary-500', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['route' => 'employee.head.assignments.index','label' => 'Penugasan',      'sub' => 'Kelola petugas',        'bg' => 'bg-blue-50',   'clr' => 'text-blue-500',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'employee.head.reports.map',        'label' => 'Peta Sebaran',   'sub' => 'Visualisasi',           'bg' => 'bg-purple-50', 'clr' => 'text-purple-500', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'employee.head.reviews.index','label' => 'Performa',       'sub' => 'Analitik detail',       'bg' => 'bg-green-50',  'clr' => 'text-success',    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ] as $a)
                <a href="{{ route($a['route']) }}"
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-gray-100
                          {{ $a['bg'] }} hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 {{ $a['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $a['icon'] }}" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $a['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $a['sub'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- ════ MAIN GRID ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── KOLOM KIRI (2/3) ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Grafik Laporan Performa Dinas --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-base font-bold text-gray-900">Laporan Performa Dinas</h2>
                        <a href="{{ route('employee.head.reviews.index') }}"
                           class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                                <path d="M3 10l3-7M8 3v7M12 7l-3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Lihat Detail
                        </a>
                    </div>

                    {{-- Bar chart SVG --}}
                    @php
                    $maxVal = max($chartData->max('total'), 1);
                    $barH   = 180; // px tinggi area bar
                    $count  = $chartData->count();
                    @endphp
                    <div class="relative" style="height:{{ $barH + 40 }}px;">
                        <svg class="w-full" height="{{ $barH + 40 }}" viewBox="0 0 {{ $count * 80 }} {{ $barH + 40 }}"
                             preserveAspectRatio="none">
                            @foreach ($chartData as $i => $d)
                            @php
                            $barTotalH = round(($d['total']  / $maxVal) * $barH);
                            $barDoneH  = round(($d['selesai'] / $maxVal) * $barH);
                            $x         = $i * 80 + 10;
                            $w         = 56;
                            @endphp
                            {{-- Total bar (gray bg) --}}
                            <rect x="{{ $x }}" y="{{ $barH - $barTotalH }}"
                                  width="{{ $w }}" height="{{ $barTotalH }}"
                                  rx="4" fill="#F3F4F6"/>
                            {{-- Selesai bar (merah) --}}
                            <rect x="{{ $x }}" y="{{ $barH - $barDoneH }}"
                                  width="{{ $w }}" height="{{ $barDoneH }}"
                                  rx="4" fill="#C01818"/>
                            {{-- Angka total di atas bar --}}
                            <text x="{{ $x + $w/2 }}" y="{{ $barH - $barTotalH - 5 }}"
                                  text-anchor="middle" font-size="11" fill="#6B7280" font-weight="600">
                                {{ $d['total'] }}
                            </text>
                            {{-- Label bulan --}}
                            <text x="{{ $x + $w/2 }}" y="{{ $barH + 18 }}"
                                  text-anchor="middle" font-size="11" fill="#9CA3AF">
                                {{ $d['label'] }}
                            </text>
                            @endforeach
                        </svg>
                        {{-- Legend --}}
                        <div class="absolute bottom-0 left-0 flex items-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-2 rounded bg-gray-200"></div>Total Laporan
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-2 rounded bg-primary-500"></div>Selesai
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik per Kategori --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Statistik per Kategori</h2>
                    <div class="space-y-4">
                        @foreach ($kategoriStats as $kat)
                        @php
                        $trend = $kat->pct >= 90 ? ['icon' => '↗', 'clr' => 'text-success']
                               : ($kat->pct >= 70  ? ['icon' => '→', 'clr' => 'text-yellow-500']
                                                    : ['icon' => '↘', 'clr' => 'text-error']);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-900">{{ $kat->name }}</span>
                                    <span class="text-xs font-bold {{ $trend['clr'] }}">{{ $trend['icon'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs text-gray-400">{{ $kat->selesai }}/{{ $kat->total }}</span>
                                    <span class="text-sm font-bold text-primary-500 ml-2">{{ $kat->pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-primary-500 transition-all"
                                     style="width:{{ $kat->pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Sebaran per Kecamatan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Sebaran Masalah per Kecamatan</h2>
                    <div class="space-y-4">
                        @foreach ($districtStats as $dist)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Kecamatan {{ $dist->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $dist->done_reports }} selesai dari {{ $dist->total_reports }} laporan
                                    </p>
                                </div>
                                <span class="text-sm font-bold {{ $dist->pct >= 90 ? 'text-success' : ($dist->pct >= 70 ? 'text-yellow-600' : 'text-error') }} tabular-nums">
                                    {{ $dist->pct }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-primary-500 transition-all"
                                     style="width:{{ $dist->pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tren Bulanan (Line chart) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-5">Tren Bulanan</h2>
                    @php
                    $lineMax  = max($chartData->max('total'), 1);
                    $svgW     = 600;
                    $svgH     = 200;
                    $padL     = 40; $padR = 20; $padT = 20; $padB = 40;
                    $plotW    = $svgW - $padL - $padR;
                    $plotH    = $svgH - $padT - $padB;
                    $pts      = $chartData->values();
                    $n        = $pts->count();
                    $xStep    = $plotW / max(1, $n - 1);

                    $lineTotal = $pts->map(fn ($d, $i) => [
                        $padL + $i * $xStep,
                        $padT + $plotH - round(($d['total'] / $lineMax) * $plotH),
                    ]);
                    $lineSelesai = $pts->map(fn ($d, $i) => [
                        $padL + $i * $xStep,
                        $padT + $plotH - round(($d['selesai'] / $lineMax) * $plotH),
                    ]);
                    $lineKepuasan = $pts->map(fn ($d, $i) => [
                        $padL + $i * $xStep,
                        $padT + $plotH - round(($d['kepuasan'] / 100) * $plotH),
                    ]);

                    $toPath = fn ($pts) => 'M ' . $pts->map(fn ($p) => $p[0] . ' ' . $p[1])->implode(' L ');
                    @endphp

                    <div class="overflow-x-auto">
                        <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-full" style="min-width:320px">
                            {{-- Y grid lines --}}
                            @foreach ([0, 25, 50, 75, 100] as $pct)
                            @php $y = $padT + $plotH - round($pct / 100 * $plotH); @endphp
                            <line x1="{{ $padL }}" y1="{{ $y }}" x2="{{ $svgW - $padR }}" y2="{{ $y }}"
                                  stroke="#F3F4F6" stroke-width="1"/>
                            <text x="{{ $padL - 6 }}" y="{{ $y + 4 }}" text-anchor="end"
                                  font-size="9" fill="#9CA3AF">{{ round($lineMax * $pct / 100) }}</text>
                            @endforeach

                            {{-- Lines --}}
                            <path d="{{ $toPath($lineTotal) }}" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="4 2"/>
                            <path d="{{ $toPath($lineSelesai) }}" fill="none" stroke="#22C55E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="{{ $toPath($lineKepuasan) }}" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

                            {{-- Dots selesai --}}
                            @foreach ($lineSelesai as $p)
                            <circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="4" fill="#22C55E" stroke="white" stroke-width="2"/>
                            @endforeach

                            {{-- X labels --}}
                            @foreach ($pts as $i => $d)
                            <text x="{{ $padL + $i * $xStep }}" y="{{ $svgH - 8 }}"
                                  text-anchor="middle" font-size="10" fill="#9CA3AF">{{ $d['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-0.5 bg-gray-300" style="border-top:2px dashed #9CA3AF"></div>
                            Total Laporan
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-0.5 bg-[#22C55E] rounded"></div>
                            Selesai
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-0.5 bg-[#3B82F6] rounded"></div>
                            Kepuasan (%)
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── KOLOM KANAN (1/3) ── --}}
            <div class="space-y-5">

                {{-- Profil --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-primary-100 text-primary-600 text-2xl font-bold
                                flex items-center justify-center mx-auto mb-3 overflow-hidden">
                        @if ($user->picture)
                        <img src="{{ Storage::url($user->picture) }}" class="w-full h-full object-cover" alt="">
                        @else
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">NIP: {{ $employee->nip }}</p>
                    <p class="text-xs font-medium text-gray-600 mt-0.5">Kepala Dinas</p>
                    <div class="border-t border-gray-50 mt-4 pt-4 text-left space-y-1.5">
                        <p class="text-xs text-gray-400">Dinas</p>
                        <p class="text-sm font-semibold text-gray-900 leading-snug">
                            {{ $employee->department?->name ?? '—' }}
                        </p>
                    </div>
                </div>

                {{-- Laporan Perlu Perhatian --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Laporan Perlu Perhatian</h2>
                        <a href="{{ route('employee.head.reports.index') }}"
                           class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                            Lihat Semua
                        </a>
                    </div>

                    @if ($urgentReports->count() > 0)
                    <div class="space-y-3">
                        @foreach ($urgentReports as $report)
                        @php
                        $p  = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
                        $st = $statusConfig[$report->status]     ?? $statusConfig['pending'];
                        $noAssign = $report->assignments->count() === 0;
                        @endphp
                        <a href="{{ route('employee.supervisor.reports.show', $report->code) }}"
                           class="block p-3.5 rounded-xl border border-gray-100 hover:border-gray-200
                                  hover:shadow-sm transition-all">
                            <p class="text-xs text-gray-400 font-mono mb-1">{{ $report->code }}</p>
                            <p class="text-sm font-bold text-gray-900 line-clamp-1 mb-1.5">{{ $report->title }}</p>
                            <p class="text-xs text-gray-400 mb-2">
                                Kec. {{ $report->district?->name }},
                                Kab. Badung, Bali
                            </p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $st['bg'] }} {{ $st['text'] }}">
                                    {{ $noAssign ? 'Belum Ditugaskan' : $st['label'] }}
                                </span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $p['bg'] }} {{ $p['text'] }}">
                                    {{ $p['label'] }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">Tidak ada laporan mendesak</p>
                    @endif
                </div>

                {{-- Top Petugas Bulan Ini --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24">
                            <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Top Petugas Bulan Ini
                    </h2>
                    @if ($topOfficers->count() > 0)
                    <div class="space-y-3.5">
                        @foreach ($topOfficers as $idx => $officer)
                        <div class="flex items-center gap-3">
                            <span class="text-base leading-none shrink-0">{{ $medals[$idx] ?? '#' . ($idx+1) }}</span>
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center
                                        shrink-0 text-xs font-bold text-gray-500 overflow-hidden">
                                @if ($officer->user?->picture)
                                <img src="{{ Storage::url($officer->user->picture) }}" class="w-full h-full object-cover" alt="">
                                @else
                                {{ strtoupper(substr($officer->user?->name ?? '?', 0, 1)) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $officer->user?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $officer->done_tasks }}/{{ $officer->total_tasks }} tugas selesai</p>
                                @if ($officer->satisfaction)
                                <p class="text-xs font-semibold text-yellow-500">⭐ {{ $officer->satisfaction }}% kepuasan</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada data bulan ini</p>
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
                    <p class="text-lg font-bold text-primary-500 text-center mb-1">
                        {{ now()->translatedFormat('F Y') }}
                    </p>
                    <p class="text-xs text-gray-400 text-center mb-4">Periode aktif</p>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">Bulan Ini</p>
                                <p class="text-lg font-bold text-gray-900 tabular-nums">{{ $periodeBulanIni }}</p>
                            </div>
                            <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24">
                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">Bulan Lalu</p>
                                <p class="text-lg font-bold text-gray-900 tabular-nums">{{ $periodeBulanLalu }}</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-4 py-3 rounded-xl bg-primary-50 border border-primary-100">
                            <p class="text-xs text-gray-400">Rata-rata per Bulan</p>
                            <p class="text-lg font-bold text-primary-600 tabular-nums">{{ $avgPerBulan }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection