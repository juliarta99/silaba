@extends('layouts.app')
@section('title', 'Dashboard Eksekutif — SILABA')

@section('content')

@php
$medals = ['🥇','🥈','🥉'];
$trendIcon = fn ($v) => $v === null ? '→' : ($v > 0 ? '↗' : ($v < 0 ? '↘' : '→'));
$trendClr  = fn ($v) => $v === null ? 'text-gray-400' : ($v > 0 ? 'text-success' : ($v < 0 ? 'text-error' : 'text-gray-400'));
$rateClr   = fn ($r) => $r >= 90 ? 'text-success' : ($r >= 85 ? 'text-yellow-600' : 'text-error');
$barClr    = fn ($r) => $r >= 90 ? 'bg-success' : ($r >= 85 ? 'bg-yellow-400' : 'bg-error');

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
$periodLabel = $dateFrom && $dateTo
    ? \Carbon\Carbon::parse($dateFrom)->translatedFormat('j M Y') . ' — ' . \Carbon\Carbon::parse($dateTo)->translatedFormat('j M Y')
    : ($activePreset && isset($presets[$activePreset]) ? $presets[$activePreset] : now()->translatedFormat('F Y'));
@endphp

{{-- ════ HERO ════ --}}
<div class="bg-primary-500 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-white shrink-0" fill="none" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard Eksekutif
                </h1>
                <p class="text-sm text-primary-200 mt-0.5">Kabupaten Badung — SILABA Analytics</p>
            </div>
            {{-- Profil Bupati --}}
            <div class="shrink-0 text-right bg-white/15 border border-white/25 rounded-2xl px-4 py-3">
                <p class="text-xs text-primary-200">Selamat datang,</p>
                <p class="text-sm font-bold leading-snug">{{ $user->name }}</p>
                <p class="text-xs text-primary-200 mt-0.5">Bupati Badung
                    @if ($regent->start_year) • Periode {{ $regent->start_year }}{{ $regent->end_year ? '–'.$regent->end_year : '' }} @endif
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-10"
     x-data="{ showCustomDate: {{ ($activePreset === 'custom' || ($dateFrom && !$activePreset)) ? 'true' : 'false' }} }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        {{-- ════ Filter Periode ════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 -mt-3 mb-8 relative z-10">
            <form method="GET" action="{{ route('regent.dashboard') }}" class="flex flex-wrap items-end gap-3">

                {{-- Preset --}}
                <div class="flex-1 min-w-[160px]">
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
                        <option value="{{ $val }}" {{ $activePreset === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Custom date (collapsible) --}}
                <div x-show="showCustomDate" x-cloak class="flex items-end gap-2 flex-wrap">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Dari</label>
                        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" max="{{ date('Y-m-d') }}"
                               class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Sampai</label>
                        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" max="{{ date('Y-m-d') }}"
                               class="px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-semibold transition-colors">
                        Terapkan
                    </button>
                </div>

                {{-- Info periode + reset --}}
                <div class="flex items-center gap-3 ml-auto">
                    @if ($dateFrom || $activePreset)
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400">Menampilkan data</p>
                        <p class="text-xs font-semibold text-gray-700">{{ $periodLabel }}</p>
                    </div>
                    <a href="{{ route('regent.dashboard') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors whitespace-nowrap">
                        Reset
                    </a>
                    @else
                    <p class="text-xs text-gray-400">{{ $periodLabel }}</p>
                    @endif
                </div>

            </form>
        </div>

        {{-- ════ KPI Cards ════ --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 -mt-5 mb-5">

            {{-- Total Laporan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-400">Total Laporan</p>
                    <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 tabular-nums">{{ number_format($totalLaporan) }}</p>
                @if ($growthTotal !== null)
                <p class="text-xs {{ $growthTotal >= 0 ? 'text-success' : 'text-error' }} mt-1 flex items-center gap-1">
                    {{ $growthTotal >= 0 ? '↗' : '↘' }} {{ abs($growthTotal) }}% vs bulan lalu
                </p>
                @endif
            </div>

            {{-- Selesai Bulan Ini --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-400">Selesai Bulan Ini</p>
                    <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-success tabular-nums">{{ number_format($selesaiBulanIni) }}</p>
                @if ($growthSelesai !== null)
                <p class="text-xs {{ $growthSelesai >= 0 ? 'text-success' : 'text-error' }} mt-1 flex items-center gap-1">
                    {{ $growthSelesai >= 0 ? '↗' : '↘' }} {{ abs($growthSelesai) }}% vs bulan lalu
                </p>
                @endif
            </div>

            {{-- Sedang Diproses --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-400">Sedang Diproses</p>
                    <div class="w-8 h-8 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-yellow-600 tabular-nums">{{ number_format($sedangDiproses) }}</p>
                <p class="text-xs text-gray-400 mt-1">Rata-rata: {{ $avgHariSedang }} hari</p>
            </div>

            {{-- Tingkat Kepuasan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs text-gray-400">Tingkat Kepuasan</p>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-blue-600 tabular-nums">{{ $kepuasan }}<span class="text-lg">%</span></p>
                @if ($growthKepuasan !== null)
                <p class="text-xs {{ $growthKepuasan >= 0 ? 'text-success' : 'text-error' }} mt-1 flex items-center gap-1">
                    {{ $growthKepuasan >= 0 ? '↗' : '↘' }} {{ abs($growthKepuasan) }}% vs bulan lalu
                </p>
                @endif
            </div>
        </div>

        {{-- ════ AKSI CEPAT ════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-3 gap-3">
                @foreach ([
                    ['route' => 'regent.reports.compare', 'label' => 'Komparasi Instansi', 'sub' => 'Bandingkan performa OPD', 'bg' => 'bg-red-50',   'clr' => 'text-primary-500', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['route' => 'regent.reports.priority',   'label' => 'Rekomendasi',        'sub' => 'Prioritas',             'bg' => 'bg-purple-50', 'clr' => 'text-purple-500', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['route' => 'regent.reports.map',        'label' => 'Peta Kabupaten',     'sub' => 'Sebaran laporan',       'bg' => 'bg-blue-50',  'clr' => 'text-blue-500',   'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as $a)
                <a href="{{ route($a['route']) }}"
                   class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-gray-100 {{ $a['bg'] }} hover:opacity-80 transition-opacity">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4 {{ $a['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $a['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            {{-- Top Performing OPD --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Top Performing OPD
                    </h2>
                    <a href="{{ route('regent.reports.compare') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach ($topOPD as $idx => $d)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-primary-500 text-white text-[10px] font-bold
                                            flex items-center justify-center shrink-0">
                                    {{ $d->abbr }}
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ $medals[$idx] }} #{{ $idx+1 }} {{ $d->abbr }}</p>
                                    <p class="text-sm font-bold text-gray-900 leading-snug">{{ $d->dept->name }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold {{ $rateClr($d->rate) }}">{{ $d->rate }}%</p>
                                <p class="text-[10px] text-gray-400">Completion</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-2 text-center text-xs mb-2.5">
                            <div><p class="text-gray-400">Total</p><p class="font-semibold text-gray-700">{{ $d->total }}</p></div>
                            <div><p class="text-gray-400">Selesai</p><p class="font-semibold text-success">{{ $d->selesai }}</p></div>
                            <div><p class="text-gray-400">Rata-rata</p><p class="font-semibold text-gray-700">{{ $d->avgHari }} hari</p></div>
                            <div><p class="text-gray-400">Kepuasan</p><p class="font-semibold text-gray-700">{{ $d->kepuasan }}%</p></div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $barClr($d->rate) }}" style="width:{{ $d->rate }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- OPD Perlu Perhatian --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        OPD Perlu Perhatian
                    </h2>
                    <a href="{{ route('regent.reports.priority') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Lihat Rekomendasi →
                    </a>
                </div>

                @if ($poorOPD->count() > 0)
                <div class="space-y-4">
                    @foreach ($poorOPD as $idx => $d)
                    <div class="border border-orange-100 bg-orange-50 rounded-xl p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-orange-200 text-orange-800 text-[10px] font-bold
                                            flex items-center justify-center shrink-0">
                                    {{ $d->abbr }}
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">#{{ $idx+1 }} {{ $d->abbr }}</p>
                                    <p class="text-sm font-bold text-gray-900 leading-snug">{{ $d->dept->name }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-error">{{ $d->rate }}%</p>
                                <p class="text-[10px] text-gray-400">Completion</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-2 text-center text-xs mb-2.5">
                            <div><p class="text-gray-400">Total</p><p class="font-semibold text-gray-700">{{ $d->total }}</p></div>
                            <div><p class="text-gray-400">Selesai</p><p class="font-semibold text-success">{{ $d->selesai }}</p></div>
                            <div><p class="text-gray-400">Rata-rata</p><p class="font-semibold {{ $d->avgHari > 5 ? 'text-error' : 'text-gray-700' }}">{{ $d->avgHari }} hari</p></div>
                            <div><p class="text-gray-400">Kepuasan</p><p class="font-semibold text-gray-700">{{ $d->kepuasan }}%</p></div>
                        </div>
                        <div class="w-full bg-orange-100 rounded-full h-1.5 mb-2.5">
                            <div class="h-1.5 rounded-full bg-error" style="width:{{ $d->rate }}%"></div>
                        </div>
                        <p class="text-xs text-orange-700 flex items-center gap-1">
                            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 12 12">
                                <path d="M6 1L1 10h10L6 1z M6 5v2.5M6 8.5v.5" stroke="currentColor" stroke-width="1" stroke-linecap="round"/>
                            </svg>
                            Masalah: {{ $d->masalah }}
                        </p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm text-gray-400">Semua OPD performa baik</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ════ ROW 2: Kecamatan + Kategori ════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

            {{-- Performa per Kecamatan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-bold text-gray-900 mb-4">Performa per Kecamatan</h2>
                <div class="space-y-3.5">
                    @foreach ($districtStats as $idx => $dist)
                    @php $bc = $barClr($dist->pct); $rc = $rateClr($dist->pct); @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900">{{ $dist->name }}</span>
                                @if ($dist->trend !== null)
                                <span class="text-xs font-semibold {{ $trendClr($dist->trend) }}">
                                    {{ $trendIcon($dist->trend) }}
                                </span>
                                @endif
                            </div>
                            <span class="text-sm font-bold {{ $rc }} tabular-nums">{{ $dist->pct }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $bc }} transition-all" style="width:{{ $dist->pct }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $dist->selesai }} dari {{ $dist->total }} laporan</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Kategori Laporan Terbanyak --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h2 class="text-base font-bold text-gray-900 mb-4">Kategori Laporan Terbanyak</h2>
                <div class="space-y-4">
                    @foreach ($kategoriStats as $kat)
                    @php $pct = $totalForPct > 0 ? round(($kat->total / $totalForPct) * 100, 1) : 0; @endphp
                    <div class="flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-900 truncate">{{ $kat->name }}</span>
                                <span class="text-sm font-bold text-gray-700 tabular-nums ml-2 shrink-0">
                                    {{ number_format($kat->total) }}
                                    <span class="text-xs font-normal text-gray-400">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full bg-primary-500 transition-all" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ════ Bottom Stats ════ --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-500 rounded-2xl p-6 text-white flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-100">Total OPD Terdaftar</p>
                    <p class="text-4xl font-bold tabular-nums mt-0.5">{{ $totalOPD }}</p>
                    <p class="text-xs text-blue-200 mt-1">Seluruh instansi terintegrasi dengan SILABA</p>
                </div>
            </div>
            <div class="bg-success rounded-2xl p-6 text-white flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-green-100">Total Petugas Lapangan</p>
                    <p class="text-4xl font-bold tabular-nums mt-0.5">{{ $totalPetugas }}</p>
                    <p class="text-xs text-green-200 mt-1">Siap melayani masyarakat Kabupaten Badung</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection