@extends('layouts.app')
@section('title', 'Performa Instansi — SILABU')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ── Header ── --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('employee.supervisor.dashboard') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                        <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Performa Instansi</h1>
                <p class="text-sm text-gray-500 mt-0.5">Analitik dan laporan kinerja</p>
            </div>
            @if (Route::has('employee.supervisor.reviews.export'))
            <a href="{{ route('employee.supervisor.reviews.export', ['start_date' => $startDate, 'end_date' => $endDate, 'district_id' => $districtId]) }}"
               class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                      text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M8 2v8m0 0l-3-3m3 3l3-3M3 13h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export Laporan
            </a>
            @endif
        </div>

        {{-- ── Stat Row ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-5">
            @foreach ([
                ['label' => 'Total Laporan Periode Ini', 'val' => $totalThisMonth, 'suffix' => '',
                 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                 'clr' => 'text-primary-500', 'ibg' => 'bg-primary-50'],
                ['label' => 'Tingkat Penyelesaian', 'val' => $completionRate, 'suffix' => '%',
                 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                 'clr' => 'text-success', 'ibg' => 'bg-green-50'],
                ['label' => 'Rata-rata Waktu (hari)', 'val' => $avgCompletionDays, 'suffix' => '',
                 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                 'clr' => 'text-blue-500', 'ibg' => 'bg-blue-50'],
                ['label' => 'Tingkat Kepuasan', 'val' => $satisfactionRate, 'suffix' => '%',
                 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                 'clr' => 'text-secondary-700', 'ibg' => 'bg-secondary-50'],
            ] as $i => $stat)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 sm:p-4">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] sm:text-xs text-gray-400 leading-tight">{{ $stat['label'] }}</p>
                    <div class="w-7 h-7 rounded-lg {{ $stat['ibg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5 {{ $stat['clr'] }}" fill="none" viewBox="0 0 24 24">
                            <path d="{{ $stat['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 tabular-nums">{{ $stat['val'] }}{{ $stat['suffix'] }}</p>
                    @if ($i === 0 && !is_null($growthPercent))
                        <span class="text-[11px] font-semibold mb-0.5 flex items-center gap-0.5 {{ $growthPercent >= 0 ? 'text-success' : 'text-error' }}">
                            {{ $growthPercent >= 0 ? '↗' : '↘' }} {{ abs($growthPercent) }}%
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Filter ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
            <p class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
                    <path d="M2 4h12M4 8h8M6 12h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Filter Periode & Kecamatan
            </p>
            <form method="GET" action="{{ route('employee.supervisor.reviews.index') }}">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-3">
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" max="{{ $endDate }}"
                               onchange="this.form.submit()"
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" min="{{ $startDate }}"
                               onchange="this.form.submit()"
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                      text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                      focus:ring-primary-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Kecamatan</label>
                        <select name="district_id" onchange="this.form.submit()"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-sm
                                       text-gray-700 focus:bg-white focus:border-primary-500 focus:ring-1
                                       focus:ring-primary-500 outline-none transition-all">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($districtsForFilter as $d)
                                <option value="{{ $d->id }}" @selected($districtId == $d->id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if (request('district_id'))
                <div class="flex items-center justify-end">
                    <a href="{{ route('employee.supervisor.reviews.index') }}"
                       class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                        Reset Filter
                    </a>
                </div>
                @endif
            </form>
        </div>

        {{-- ── Tren Laporan ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 mb-5">
            <h2 class="text-sm font-bold text-gray-900 mb-6">Tren Laporan 6 Bulan Terakhir</h2>
            @php $maxVal = max(1, collect($trend)->max('value')); @endphp
            <div class="grid grid-cols-6 gap-3 sm:gap-4 items-end h-36">
                @foreach ($trend as $t)
                    <div class="flex flex-col items-center justify-end h-full">
                        <span class="text-xs sm:text-sm text-gray-600 mb-2">{{ $t['value'] }}</span>
                        <div class="w-full bg-primary-500 rounded-t-lg" style="height: {{ max(6, ($t['value'] / $maxVal) * 100) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-6 gap-3 sm:gap-4 mt-2">
                @foreach ($trend as $t)
                    <div class="text-center text-xs sm:text-sm text-gray-400">{{ $t['label'] }}</div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
            {{-- Performa per Kategori --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-6">Performa per Kategori</h2>
                <div class="space-y-5">
                    @foreach ($categoryPerf as $cat)
                        <div>
                            <div class="flex justify-between items-baseline mb-1.5">
                                <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                                <span class="text-xs text-gray-400">{{ $cat['completed'] }}/{{ $cat['total'] }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary-500 rounded-full" style="width: {{ $cat['rate'] }}%"></div>
                                </div>
                                <span class="text-sm font-semibold text-primary-500 w-10 text-right">{{ $cat['rate'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                    @if ($categoryPerf->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-6">Belum ada data untuk periode ini.</p>
                    @endif
                </div>
            </div>

            {{-- Performa per Kecamatan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6">
                <h2 class="text-sm font-bold text-gray-900 mb-6">Performa per Kecamatan</h2>
                <div class="space-y-3">
                    @foreach ($districtPerf as $d)
                        <div class="border border-gray-100 rounded-xl p-4 bg-gray-10/50">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-gray-800">{{ $d['name'] }}</span>
                                <span class="text-xs text-gray-400">{{ $d['completed'] }}/{{ $d['total'] }} laporan</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-sm">
                                <div>
                                    <div class="text-[11px] text-gray-400">Penyelesaian</div>
                                    <div class="font-semibold text-primary-500">{{ $d['rate'] }}%</div>
                                </div>
                                <div>
                                    <div class="text-[11px] text-gray-400">Rata-rata</div>
                                    <div class="font-semibold text-gray-800">{{ $d['avg_days'] }} hari</div>
                                </div>
                                <div>
                                    <div class="text-[11px] text-gray-400">Kepuasan</div>
                                    <div class="font-semibold text-secondary-700 flex items-center gap-1">
                                        <svg class="w-3 h-3 pb-[0.5px]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ $d['satisfaction'] }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if ($districtPerf->isEmpty())
                        <p class="text-sm text-gray-400 text-center py-6">Belum ada data untuk periode ini.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Leaderboard Petugas ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 mb-5">
            <h2 class="text-sm font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Leaderboard Petugas
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                            <th class="py-2 px-3 font-medium">Rank</th>
                            <th class="py-2 px-3 font-medium">Nama Petugas</th>
                            <th class="py-2 px-3 font-medium text-center">Total Tugas</th>
                            <th class="py-2 px-3 font-medium text-center">Selesai</th>
                            <th class="py-2 px-3 font-medium text-center">Tepat Waktu</th>
                            <th class="py-2 px-3 font-medium text-center">Rata-rata</th>
                            <th class="py-2 px-3 font-medium text-center">Kepuasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderboard as $i => $p)
                            <tr class="border-b border-gray-100 last:border-0">
                                <td class="py-3 px-3">
                                    <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-gray-100 text-gray-700 font-semibold text-xs">#{{ $i + 1 }}</span>
                                </td>
                                <td class="py-3 px-3 font-medium text-gray-800">{{ $p['name'] }}</td>
                                <td class="py-3 px-3 text-center text-gray-600">{{ $p['total_tugas'] }}</td>
                                <td class="py-3 px-3 text-center text-success font-semibold">{{ $p['selesai'] }}</td>
                                <td class="py-3 px-3 text-center text-blue-700 font-semibold">{{ $p['tepat_waktu'] }}</td>
                                <td class="py-3 px-3 text-center text-gray-600">{{ $p['rata_rata'] }} hari</td>
                                <td class="py-3 px-3 text-center text-secondary-700 flex items-center gap-1">
                                    <svg class="w-3 h-3 pb-[0.5px]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $p['kepuasan'] }}%
                                </td>
                            </tr>
                        @endforeach
                        @if ($leaderboard->isEmpty())
                            <tr><td colspan="7" class="py-8 text-center text-sm text-gray-400">Belum ada data petugas untuk periode ini.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Review & Ulasan Masyarakat ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    Review &amp; Ulasan Masyarakat
                </h2>
                <div class="flex items-center gap-3 bg-gray-10 rounded-xl px-4 py-2 border border-gray-100">
                    <span class="text-xl font-bold text-gray-900">{{ number_format($reviewSummary['average'], 1) }}</span>
                    <div class="text-secondary-500 text-base leading-none">
                        @for ($s = 1; $s <= 5; $s++)
                            {{ $s <= round($reviewSummary['average']) ? '★' : '☆' }}
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">({{ $reviewSummary['total'] }} ulasan)</span>
                </div>
            </div>

            {{-- Distribusi rating --}}
            <div class="space-y-1.5 mb-6">
                @for ($star = 5; $star >= 1; $star--)
                    @php
                        $count = $reviewSummary['distribution'][$star] ?? 0;
                        $percent = $reviewSummary['total'] > 0 ? round($count / $reviewSummary['total'] * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-3 text-xs">
                        <span class="w-10 text-gray-500">{{ $star }} ★</span>
                        <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-secondary-500 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="w-6 text-right text-gray-400">{{ $count }}</span>
                    </div>
                @endfor
            </div>

            {{-- Daftar review --}}
            <div class="divide-y divide-gray-100">
                @forelse ($reviews as $review)
                    <div class="py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">{{ $review->user->name ?? 'Warga' }}</div>
                                <div class="text-secondary-500 text-sm">
                                    @for ($s = 1; $s <= 5; $s++)
                                        {{ $s <= $review->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                        </div>

                        @if ($review->comment)
                            <p class="text-sm text-gray-600 mt-2">{{ $review->comment }}</p>
                        @endif

                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @if ($review->report?->category)
                                <span class="text-xs font-medium bg-primary-50 text-primary-600 px-2 py-0.5 rounded-full">{{ $review->report->category->name }}</span>
                            @endif
                            @if ($review->report?->district)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $review->report->district->name }}</span>
                            @endif
                            @if ($review->report?->code)
                                <span class="text-xs font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $review->report->code }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-10 text-center">Belum ada review dari masyarakat untuk instansi ini.</p>
                @endforelse
            </div>

            @if ($reviews->hasPages())
            <div class="mt-6">{{ $reviews->links() }}</div>
            @endif
        </div>

    </div>
</div>
@endsection