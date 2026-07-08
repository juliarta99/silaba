@extends('layouts.app')
@section('title', 'Analisis & Rekomendasi — Kabupaten Badung')

@section('content')

@php
$insightTypeConfig = [
    'warning' => ['bg' => 'bg-red-50',   'border' => 'border-red-200',  'icon_bg' => 'bg-red-100',   'icon_clr' => 'text-error',    'badge' => 'bg-red-100 text-error',     'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    'info'    => ['bg' => 'bg-blue-50',  'border' => 'border-blue-200', 'icon_bg' => 'bg-blue-100',  'icon_clr' => 'text-blue-600', 'badge' => 'bg-blue-100 text-blue-700', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200','icon_bg' => 'bg-green-100', 'icon_clr' => 'text-success',  'badge' => 'bg-green-100 text-success', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
];
$barColor = fn ($pct) => $pct >= 90 ? 'bg-success' : ($pct >= 75 ? 'bg-yellow-400' : 'bg-error');
$rateClr  = fn ($pct) => $pct >= 90 ? 'text-success' : ($pct >= 75 ? 'text-yellow-600' : 'text-error');
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-10">

    {{-- ── Hero ── --}}
    <div class="bg-primary-500 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-20 pb-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('regent.dashboard') }}"
                       class="inline-flex items-center gap-1.5 text-xs text-primary-200 hover:text-white mb-2 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                            <path d="M9 3L4 7l5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Kembali ke Dashboard
                    </a>
                    <h1 class="text-xl sm:text-2xl font-bold flex items-center gap-2.5">
                        <svg class="w-6 h-6 text-white shrink-0" fill="none" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Analisis Data & Rekomendasi
                    </h1>
                    <p class="text-sm text-primary-200 mt-0.5">
                        Insight berbasis data untuk prioritas anggaran strategis
                        <span class="ml-1.5 text-xs bg-white/20 px-2 py-0.5 rounded-full">Kabupaten Badung</span>
                    </p>
                </div>
                
                {{-- ACTIONS: Filter Bulan & Export --}}
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('regent.reports.priority') }}" class="shrink-0" x-data>
                        <select name="month" @change="$el.closest('form').submit()"
                                class="bg-white/20 border border-white/30 text-white text-sm font-medium rounded-xl px-3 py-2.5 outline-none focus:ring-2 focus:ring-white/50 [&>option]:text-gray-900 cursor-pointer">
                            <option value="">Semua Waktu</option>
                            @foreach($availableMonths as $am)
                                <option value="{{ $am['value'] }}" {{ request('month') == $am['value'] ? 'selected' : '' }}>
                                    {{ $am['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <a href="{{ route('regent.reports.priority.export', ['month' => request('month')]) }}"
                       class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30
                              text-white text-sm font-semibold transition-colors border border-white/30">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M8 2v8m0 0l-3-3m3 3l3-3M3 13h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-5 space-y-5">

        {{-- ══════ KEY INSIGHTS (AI) ══════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-gray-50">
                <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-lg"></span> Key Insights
                    <span class="text-xs font-normal text-gray-400 ml-1">powered by Gemini AI</span>
                </h2>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    @if ($cacheInfo['cached'])
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        Cache aktif · Reset {{ $cacheInfo['next_reset'] }}
                    </span>
                    @else
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        Baru digenerate
                    </span>
                    @endif
                    <a href="{{ route('regent.reports.priority', ['refresh_ai' => 1, 'month' => request('month')]) }}"
                       class="flex items-center gap-1 text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                            <path d="M12.5 2.5A6 6 0 002 7M1.5 11.5A6 6 0 0012 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M1.5 8.5V11.5H4.5M9.5 2.5H12.5V5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Refresh AI
                    </a>
                </div>
            </div>

            <div class="p-5 space-y-4">
                @php $insightData = $insights['insights'] ?? []; @endphp

                @if (count($insightData) > 0)
                    @foreach ($insightData as $insight)
                    @php $cfg = $insightTypeConfig[$insight['type'] ?? 'info'] ?? $insightTypeConfig['info']; @endphp
                    <div class="flex items-start gap-4 p-4 rounded-xl {{ $cfg['bg'] }} border {{ $cfg['border'] }}">
                        <div class="w-9 h-9 rounded-xl {{ $cfg['icon_bg'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 {{ $cfg['icon_clr'] }}" fill="none" viewBox="0 0 24 24">
                                <path d="{{ $cfg['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <p class="text-sm font-bold text-gray-900">{{ $insight['title'] ?? '' }}</p>
                                @if (!empty($insight['data_point']))
                                <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-full {{ $cfg['badge'] }}">
                                    {{ $insight['data_point'] }}
                                </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 mb-1.5">{{ $insight['summary'] ?? '' }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $insight['detail'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach

                    @if (!empty($insights['rekomendasi_utama']))
                    <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-primary-50 border border-primary-100">
                        <svg class="w-4 h-4 text-primary-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div>
                            <p class="text-xs font-bold text-primary-700 mb-0.5">Rekomendasi Utama</p>
                            <p class="text-sm text-primary-800 leading-relaxed">{{ $insights['rekomendasi_utama'] }}</p>
                        </div>
                    </div>
                    @endif
                @else
                <div class="text-center py-8">
                    <p class="text-sm text-gray-400">Insight tidak tersedia. Coba refresh.</p>
                </div>
                @endif

                <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs text-gray-400">
                    <span>Dibuat: {{ $insights['generated_at'] ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- ══════ KATEGORI LAPORAN TERBANYAK ══════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kategori Laporan Terbanyak
            </h2>
            <div class="space-y-4">
                @foreach ($kategoriRaw as $idx => $kat)
                @php $barClr = $barColor($kat['completion']); @endphp
                <div class="border border-gray-100 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                        text-white shrink-0 {{ $idx < 3 ? 'bg-primary-500' : 'bg-gray-300' }}">
                                #{{ $idx + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $kat['nama'] }}</p>
                                <p class="text-xs text-gray-400">{{ $kat['pct_of_total'] }}% dari total keseluruhan</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold {{ $rateClr($kat['completion']) }}">
                            Completion {{ $kat['completion'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-3">
                        <div class="h-1.5 rounded-full {{ $barClr }}" style="width:{{ $kat['completion'] }}%"></div>
                    </div>
                    <div class="grid grid-cols-4 gap-3 text-center">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Selesai</p>
                            <p class="text-sm font-bold text-success">{{ number_format($kat['selesai']) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Terlambat</p>
                            <p class="text-sm font-bold {{ $kat['terlambat'] > 0 ? 'text-error' : 'text-gray-400' }}">{{ $kat['terlambat'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Rata-rata</p>
                            <p class="text-sm font-bold text-gray-700">{{ $kat['avg_hari'] }} hari</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Total</p>
                            <p class="text-sm font-bold text-gray-700">{{ number_format($kat['total']) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════ DINAS PALING BANYAK TERLAMBAT ══════ --}}
        @if ($dinasTerlambat->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Dinas dengan Keterlambatan Tinggi
            </h2>
            <div class="space-y-3">
                @foreach ($dinasTerlambat as $idx => $dinas)
                @php $rankColors = ['bg-red-500','bg-orange-400','bg-yellow-400']; @endphp
                <div class="border {{ $dinas['pct_terlambat'] >= 15 ? 'border-red-200 bg-red-50' : 'border-gray-100' }} rounded-xl p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full {{ $rankColors[$idx] ?? 'bg-gray-300' }} text-white text-xs font-bold
                                        flex items-center justify-center shrink-0">
                                #{{ $idx + 1 }}
                            </div>
                            <p class="text-sm font-bold text-gray-900">{{ $dinas['nama'] }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-error">{{ $dinas['pct_terlambat'] }}%</p>
                            <p class="text-xs text-gray-400">{{ $dinas['terlambat'] }} dari {{ $dinas['total'] }}</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-3">
                        <div class="h-1.5 rounded-full bg-error" style="width:{{ min(100, $dinas['pct_terlambat']) }}%"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div>
                            <p class="text-gray-400">Rata-rata Waktu</p>
                            <p class="font-bold text-gray-700">{{ $dinas['avg_hari'] }} hari</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Total Laporan</p>
                            <p class="font-bold text-gray-700">{{ number_format($dinas['total']) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Petugas Aktif/Total</p>
                            <p class="font-bold text-gray-700">{{ $dinas['petugas_aktif'] }}/{{ $dinas['petugas_total'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════ DISTRIBUSI PER KECAMATAN ══════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
                </svg>
                Distribusi per Kecamatan
            </h2>
            <div class="space-y-3.5">
                @foreach ($distrikStats as $idx => $dist)
                @php $bc = $barColor($dist['pct']); @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-gray-400 w-5">#{{ $idx + 1 }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $dist['nama'] }}</span>
                            <span class="text-xs text-gray-400">{{ number_format($dist['selesai']) }}/{{ number_format($dist['total']) }}</span>
                        </div>
                        <span class="text-sm font-bold {{ $rateClr($dist['pct']) }} tabular-nums">{{ $dist['pct'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $bc }} transition-all" style="width:{{ $dist['pct'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════ TREND 6 BULAN ══════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Trend 6 Bulan Terakhir
            </h2>
            <div class="space-y-2.5">
                @php $trendMax = max($trend->max('total'), 1); @endphp
                @foreach ($trend as $t)
                @php
                $tWidth = round(($t['total'] / $trendMax) * 100);
                $sWidth = $t['total'] > 0 ? round(($t['selesai'] / $t['total']) * 100) : 0;
                $isLast = $loop->last;
                @endphp
                <div class="flex items-center gap-4">
                    <span class="text-xs text-gray-500 w-24 shrink-0">
                        {{ $t['bulan_short'] }}
                        @if ($isLast && empty(request('month')))
                        <span class="ml-1 text-[10px] bg-blue-100 text-blue-600 px-1 py-0.5 rounded font-bold">Berjalan</span>
                        @endif
                    </span>
                    <div class="flex-1">
                        <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden">
                            <div class="h-5 rounded-full bg-gray-200" style="width:{{ $tWidth }}%">
                                <div class="h-5 rounded-full bg-success" style="width:{{ $sWidth }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="w-72 shrink-0 flex items-center gap-3 text-xs text-gray-500">
                        <span>Total <strong class="text-gray-700">{{ number_format($t['total']) }}</strong></span>
                        <span>Selesai <strong class="text-success">{{ number_format($t['selesai']) }}</strong></span>
                        <span>Terlambat
                            <strong class="{{ $t['terlambat'] > 0 ? 'text-error' : 'text-gray-400' }}">
                                {{ $t['terlambat'] }}
                            </strong>
                            <span class="text-gray-400">({{ $t['pct_terlambat'] }}%)</span>
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection