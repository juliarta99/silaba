@extends('layouts.app')

@section('title', 'Laporan Saya — SILABU')

@section('content')

{{-- ── Header ── --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-24 pb-8
                flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Laporan Saya</h1>
            <p class="text-sm text-gray-500 mt-1.5">Kelola dan lacak semua laporan Anda</p>
        </div>
        <a href="{{ route('reports.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                  text-white text-sm font-semibold transition-colors shadow-sm shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Buat Laporan Baru
        </a>
    </div>
</section>

{{-- ── Filter Bar ── --}}
<section class="bg-gray-50">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-6">
        <form method="GET" action="{{ route('citizen.reports.index') }}"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">

                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-2">Filter Status</label>
                    <select id="status" name="status"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm
                                   text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500
                                   focus:border-primary-500 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="pending"               {{ request('status') === 'pending' ? 'selected' : '' }}>Baru</option>
                        <option value="in_progress"           {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="under_review"          {{ request('status') === 'under_review' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="waiting_for_materials" {{ request('status') === 'waiting_for_materials' ? 'selected' : '' }}>Menunggu Material</option>
                        <option value="completed"             {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected"              {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label for="period" class="block text-xs font-semibold text-gray-700 mb-2">Periode</label>
                    <select id="period" name="period"
                            class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm
                                   text-gray-900 focus:bg-white focus:ring-2 focus:ring-primary-500
                                   focus:border-primary-500 outline-none transition-all">
                        <option value="">Semua Waktu</option>
                        <option value="7"  {{ request('period') === '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('period') === '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ request('period') === '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    </select>
                </div>

                <div>
                    <a href="{{ route('citizen.reports.index') }}"
                       class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg
                              border border-gray-200 text-gray-700 text-sm font-semibold
                              hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M13 8H3m0 0l4-4M3 8l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Reset Filter
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-100 mt-5 pt-4">
                <p class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-semibold text-gray-900">{{ $reports->count() }}</span>
                    dari
                    <span class="font-semibold text-gray-900">{{ $reports->total() }}</span>
                    laporan
                </p>
            </div>
        </form>
    </div>
</section>

{{-- ── List Laporan ── --}}
<section class="bg-gray-50 pb-16">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-6">

        @if ($reports->count() > 0)
        <div class="flex flex-col gap-5">
            @foreach ($reports as $report)
            @php
            $statusLabel = [
                'pending'                => 'Baru',
                'in_progress'            => 'Diproses',
                'under_review'           => 'Menunggu Verifikasi',
                'waiting_for_materials'  => 'Menunggu Material',
                'completed'              => 'Selesai',
                'rejected'               => 'Ditolak',
            ][$report->status] ?? 'Baru';

            $statusBadge = [
                'pending'                => 'bg-blue-500 text-white',
                'in_progress'            => 'bg-yellow-500 text-white',
                'under_review'           => 'bg-orange-500 text-white',
                'waiting_for_materials'  => 'bg-purple-500 text-white',
                'completed'              => 'bg-success text-white',
                'rejected'               => 'bg-error text-white',
            ][$report->status] ?? 'bg-gray-400 text-white';

            $needsVerification = $report->status === 'under_review';
            $needsRating = $report->status === 'completed' && !$report->review;

            $latestProgress = $report->progresses->first();
            $updateTerakhir = $latestProgress?->title ?? match($report->status) {
                'pending'    => 'Laporan diterima, menunggu penugasan petugas',
                'rejected'   => 'Laporan ditolak',
                'completed'  => 'Laporan telah diselesaikan',
                default      => 'Laporan sedang diproses',
            };

            $coverPhoto = $report->evidences->where('file_type', 'photo')->first();
            $needsAction = $needsVerification || $needsRating;
            $wrapperBorder = $needsAction ? 'border-2 border-orange-300' : 'border border-gray-100';
            @endphp

            <div class="bg-white rounded-2xl {{ $wrapperBorder }} overflow-hidden shadow-sm">

                {{-- Banner aksi --}}
                @if ($needsVerification)
                <div class="flex items-center gap-2.5 px-5 py-3 bg-orange-50 border-b border-orange-100">
                    <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center shrink-0 text-xs font-bold">!</span>
                    <p class="text-sm font-medium text-orange-700">Perlu Verifikasi: Konfirmasi apakah masalah sudah selesai</p>
                </div>
                @elseif ($needsRating)
                <div class="flex items-center gap-2.5 px-5 py-3 bg-orange-50 border-b border-orange-100">
                    <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center shrink-0 text-xs font-bold">!</span>
                    <p class="text-sm font-medium text-orange-700">Beri Rating: Bantu kami meningkatkan layanan dengan memberikan penilaian</p>
                </div>
                @endif

                {{-- Body --}}
                <div class="flex flex-col sm:flex-row gap-4 p-5">

                    {{-- Foto --}}
                    <a href="{{ route('citizen.reports.show', $report->code) }}"
                       class="block w-full sm:w-40 h-40 sm:h-auto shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        @if ($coverPhoto)
                            <img src="{{ Storage::url($coverPhoto->file_path) }}"
                                 alt="{{ $report->title }}" loading="lazy"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">

                        {{-- Tiket + Badge --}}
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="text-xs font-mono text-gray-400">{{ $report->code }}</span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusBadge }}">
                                {{ $statusLabel }}
                            </span>
                            @if ($report->category)
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-primary-50 text-primary-500">
                                {{ $report->category->name }}
                            </span>
                            @endif
                        </div>

                        {{-- Judul --}}
                        <h3 class="text-base font-bold text-gray-900 mb-2 leading-snug">
                            <a href="{{ route('citizen.reports.show', $report->code) }}"
                               class="hover:text-primary-500 transition-colors">{{ $report->title }}</a>
                        </h3>

                        {{-- Tags --}}
                        @if ($report->tags->count() > 0)
                        <div class="flex flex-wrap gap-1.5 mb-2.5">
                            @foreach ($report->tags->take(4) as $tag)
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full
                                         border border-blue-200 text-blue-600 bg-blue-50/50">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        {{-- Meta --}}
                        <div class="flex flex-col gap-1.5 text-sm text-gray-500">
                            <span class="flex items-start gap-2">
                                <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 1.5a5.5 5.5 0 00-5.5 5.5c0 3.513 4.424 7.976 5.14 8.67a.5.5 0 00.72 0C9.076 14.976 13.5 10.513 13.5 7A5.5 5.5 0 008 1.5zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" clip-rule="evenodd"/>
                                </svg>
                                {{ $report->location }}
                            </span>
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                    <rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M2 6.5h12M5 1.5v2M11 1.5v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                </svg>
                                Dilaporkan: {{ $report->created_at->translatedFormat('j M Y') }}
                            </span>
                            <span class="flex items-start gap-2">
                                <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                    <path d="M5 2h6a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M6 5.5h4M6 8h4M6 10.5h2.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                </svg>
                                <span>Update terakhir: {{ $updateTerakhir }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2.5 w-full sm:w-44 shrink-0">

                        <a href="{{ route('citizen.reports.show', $report->code) }}"
                           class="flex items-center justify-center px-4 py-2.5 rounded-lg bg-primary-500
                                  hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                            Lihat Detail
                        </a>

                        @if ($needsVerification)
                        <a href="{{ route('citizen.reports.confirm', $report->code) }}"
                           class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-success
                                  hover:opacity-90 text-white text-sm font-semibold transition-opacity">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Konfirmasi Selesai
                        </a>
                        <a href="{{ route('citizen.reports.reject-completion', $report->code) }}"
                           class="flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-200
                                  text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors">
                            Laporkan Belum Selesai
                        </a>
                        @endif

                        @if ($needsRating)
                        <a href="{{ route('citizen.reports.rate', $report->code) }}"
                           class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg
                                  bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-sm font-semibold
                                  transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M8 1l1.9 4.5L15 6l-3.6 3.2L12.4 14 8 11.5 3.6 14l1-4.8L1 6l5.1-.5L8 1z"/>
                            </svg>
                            Beri Rating
                        </a>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($reports->hasPages())
        <div class="mt-8">{{ $reports->appends(request()->query())->links() }}</div>
        @endif

        @else
        <div class="flex flex-col items-center justify-center py-20 text-center
                    bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Anda belum memiliki laporan</h3>
            <p class="text-sm text-gray-500 max-w-sm mb-5">
                Mulai laporkan permasalahan di sekitar Anda untuk membantu Kabupaten Badung menjadi lebih baik.
            </p>
            <a href="{{ route('reports.create') }}"
               class="px-5 py-3 rounded-xl bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                Buat Laporan Pertama
            </a>
        </div>
        @endif

    </div>
</section>

@endsection