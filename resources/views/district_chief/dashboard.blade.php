@extends('layouts.app')
@section('title', 'Dashboard Camat — ' . $districtName)

@section('content')

@php
$priorityConfig = [
    'critical' => ['label' => 'Darurat',  'dot' => 'bg-red-500',    'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'dot' => 'bg-orange-400', 'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'dot' => 'bg-yellow-400', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'dot' => 'bg-green-400',  'bg' => 'bg-green-100',  'text' => 'text-success'],
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

{{-- ════ HERO HEADER ════ --}}
<div class="bg-primary-500 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold">Dashboard Camat</h1>
                <p class="text-sm text-primary-200 mt-0.5">
                    Pantau performa dan laporan kecamatan secara real-time
                </p>
            </div>
            <div class="shrink-0 text-right bg-white/15 border border-white/30 rounded-2xl px-4 py-3">
                <p class="text-xs text-primary-200">Selamat datang,</p>
                <p class="text-sm font-bold leading-snug">{{ $user->name }}</p>
                <p class="text-xs text-primary-200 mt-0.5">Camat</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-10 min-h-[calc(100vh-68px)] pb-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- ════ KPI ROW 1 ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-5 mb-4">

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
            <div class="grid grid-cols-3 gap-3">
                @foreach ([
                    ['route' => 'district-chief.reports.compare', 'label' => 'Komparasi Instansi', 'sub' => 'Bandingkan performa OPD',  'bg' => 'bg-red-50',    'clr' => 'text-primary-500', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    // ['route' => 'district-chief.reports.priority','label' => 'Rekomendasi',         'sub' => 'Prioritas',               'bg' => 'bg-purple-50', 'clr' => 'text-purple-500', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['route' => 'district-chief.reports.map',    'label' => 'Peta Kabupaten',       'sub' => 'Sebaran laporan',         'bg' => 'bg-blue-50',   'clr' => 'text-blue-500',   'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
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

                {{-- Grafik Bar 6 Bulan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-base font-bold text-gray-900">Laporan Performa Kecamatan</h2>
                        <a href="{{ route('district-chief.reports.index') }}"
                           class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                                <path d="M3 10l3-7M8 3v7M12 7l-3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Lihat Detail
                        </a>
                    </div>

                    @php
                    $maxVal = max($chartData->max('total'), 1);
                    $barH   = 180;
                    $count  = $chartData->count();
                    @endphp
                    <div class="relative" style="height:{{ $barH + 40 }}px;">
                        <svg class="w-full" height="{{ $barH + 40 }}" viewBox="0 0 {{ $count * 80 }} {{ $barH + 40 }}" preserveAspectRatio="none">
                            @foreach ($chartData as $i => $d)
                            @php
                            $barTotalH = round(($d['total']  / $maxVal) * $barH);
                            $barDoneH  = round(($d['selesai'] / $maxVal) * $barH);
                            $x         = $i * 80 + 10;
                            $w         = 56;
                            @endphp
                            <rect x="{{ $x }}" y="{{ $barH - $barTotalH }}" width="{{ $w }}" height="{{ $barTotalH }}" rx="4" fill="#F3F4F6"/>
                            <rect x="{{ $x }}" y="{{ $barH - $barDoneH }}"  width="{{ $w }}" height="{{ $barDoneH }}"  rx="4" fill="#22C55E"/>
                            <text x="{{ $x + $w/2 }}" y="{{ $barH - $barTotalH - 5 }}" text-anchor="middle" font-size="11" fill="#6B7280" font-weight="600">{{ $d['total'] }}</text>
                            <text x="{{ $x + $w/2 }}" y="{{ $barH + 18 }}" text-anchor="middle" font-size="11" fill="#9CA3AF">{{ $d['label'] }}</text>
                            @endforeach
                        </svg>
                        <div class="absolute bottom-0 left-0 flex items-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-2 rounded bg-gray-200"></div>Total Laporan
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-2 rounded bg-success"></div>Selesai
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kategori Masalah Terbanyak --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Kategori Masalah Terbanyak</h2>
                    <div class="space-y-3.5">
                        @php $totalAll = $kategoriStats->sum('total'); @endphp
                        @foreach ($kategoriStats as $kat)
                        @php $pct = $totalAll > 0 ? round(($kat->total / $totalAll) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $kat->name }}</span>
                                <div class="text-right">
                                    <span class="text-xs text-gray-400">{{ $kat->total }} laporan</span>
                                    <span class="text-sm font-bold text-primary-500 ml-2">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full bg-primary-500 transition-all" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tren Bulanan (Line Chart) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-base font-bold text-gray-900 mb-5">Tren Bulanan</h2>
                    @php
                    $lineMax = max($trendData->max('total'), 1);
                    $svgW    = 600; $svgH = 200;
                    $padL    = 40; $padR = 20; $padT = 20; $padB = 40;
                    $plotW   = $svgW - $padL - $padR;
                    $plotH   = $svgH - $padT - $padB;
                    $pts     = $trendData->values();
                    $n       = $pts->count();
                    $xStep   = $plotW / max(1, $n - 1);

                    $lineTotal   = $pts->map(fn ($d, $i) => [$padL + $i*$xStep, $padT + $plotH - round(($d['total']/$lineMax)*$plotH)]);
                    $lineSelesai = $pts->map(fn ($d, $i) => [$padL + $i*$xStep, $padT + $plotH - round(($d['selesai']/$lineMax)*$plotH)]);
                    $toPath = fn ($pts) => 'M ' . $pts->map(fn ($p) => round($p[0]) . ' ' . round($p[1]))->implode(' L ');
                    @endphp
                    <div class="overflow-x-auto">
                        <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-full" style="min-width:320px">
                            @foreach ([0,25,50,75,100] as $pct)
                            @php $y = $padT + $plotH - round($pct/100*$plotH); @endphp
                            <line x1="{{ $padL }}" y1="{{ $y }}" x2="{{ $svgW-$padR }}" y2="{{ $y }}" stroke="#F3F4F6" stroke-width="1"/>
                            <text x="{{ $padL-6 }}" y="{{ $y+4 }}" text-anchor="end" font-size="9" fill="#9CA3AF">{{ round($lineMax*$pct/100) }}</text>
                            @endforeach
                            <path d="{{ $toPath($lineTotal) }}"   fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="4 2"/>
                            <path d="{{ $toPath($lineSelesai) }}" fill="none" stroke="#22C55E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            @foreach ($lineSelesai as $p)
                            <circle cx="{{ round($p[0]) }}" cy="{{ round($p[1]) }}" r="4" fill="#22C55E" stroke="white" stroke-width="2"/>
                            @endforeach
                            @foreach ($pts as $i => $d)
                            <text x="{{ $padL + $i*$xStep }}" y="{{ $svgH - 8 }}" text-anchor="middle" font-size="10" fill="#9CA3AF">{{ $d['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                    <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-0" style="border-top:2px dashed #9CA3AF"></div>Total Laporan
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-0.5 bg-success rounded"></div>Selesai
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── KOLOM KANAN (1/3) ── --}}
            <div class="space-y-5">

                {{-- Profil Camat --}}
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
                    <p class="text-xs text-gray-400 mt-0.5">NIP: {{ $districtChief->nip }}</p>
                    <p class="text-xs font-medium text-gray-600 mt-0.5">Camat</p>

                    <div class="border-t border-gray-50 mt-4 pt-4 text-left space-y-2">
                        <div>
                            <p class="text-xs text-gray-400">Wilayah</p>
                            <p class="text-sm font-bold text-gray-900">Kecamatan {{ $districtName }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Menjabat sejak</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $districtChief->start_year }}</p>
                        </div>
                    </div>
                </div>

                {{-- Laporan Perlu Perhatian --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Laporan Perlu Perhatian</h2>
                        <a href="{{ route('district-chief.reports.index') }}"
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
                        <a href="{{ route('reports.show', $report->code) }}"
                           class="block p-3.5 rounded-xl border border-gray-100 hover:border-gray-200 transition-all">
                            <div class="flex items-start gap-2 mb-1">
                                <div class="w-2 h-2 rounded-full {{ $p['dot'] }} shrink-0 mt-1.5"></div>
                                <p class="text-xs text-gray-400 font-mono">{{ $report->code }}</p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 line-clamp-1 mb-0.5 ml-4">{{ $report->title }}</p>
                            <p class="text-xs text-gray-400 mb-2 ml-4">
                                Kel. {{ $districtName }},
                                Kab. Badung, Bali
                            </p>
                            <div class="flex flex-wrap gap-1.5 ml-4">
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
                    <div class="text-center py-8">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada laporan mendesak</p>
                    </div>
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
                    <div class="space-y-4">
                        @foreach ($topOfficers as $idx => $officer)
                        <div class="flex items-center gap-3">
                            <span class="text-base leading-none shrink-0">{{ $medals[$idx] ?? '#'.($idx+1) }}</span>
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center
                                        shrink-0 text-xs font-bold text-gray-500 overflow-hidden">
                                @if ($officer->picture)
                                <img src="{{ Storage::url($officer->picture) }}" class="w-full h-full object-cover" alt="">
                                @else
                                {{ strtoupper(substr($officer->name ?? '?', 0, 1)) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $officer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $officer->done_tasks }}/{{ $officer->total_tasks }} tugas selesai</p>
                                @if ($officer->satisfaction)
                                <p class="text-xs font-semibold text-yellow-500 flex items-center gap-1">
                                    ⭐ {{ $officer->satisfaction }}% kepuasan
                                </p>
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
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">Bulan Ini</p>
                                <p class="text-xl font-bold text-gray-900 tabular-nums">{{ $periodeBulanIni }}</p>
                            </div>
                            <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24">
                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400">Bulan Lalu</p>
                                <p class="text-xl font-bold text-gray-900 tabular-nums">{{ $periodeBulanLalu }}</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24">
                                <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="px-4 py-3 rounded-xl bg-primary-50 border border-primary-100">
                            <p class="text-xs text-gray-400">Rata-rata per Bulan</p>
                            <p class="text-xl font-bold text-primary-600 tabular-nums">{{ $avgPerBulan }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection