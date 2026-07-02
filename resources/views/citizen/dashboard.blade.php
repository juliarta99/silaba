@extends('layouts.app')

@section('title', 'Dashboard Saya — SILABU')

@section('content')

@php
$name     = $user->name;
$initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $name), 0, 2))));
$email    = $citizen?->email ?? '—';
$picture  = $user->picture ? asset('storage/' . $user->picture) : null;

$statusLabel = [
    'pending'               => 'Baru',
    'in_progress'           => 'Diproses',
    'under_review'          => 'Menunggu Verifikasi',
    'waiting_for_materials' => 'Menunggu Material',
    'completed'             => 'Selesai',
    'rejected'              => 'Ditolak',
];

$statusBadge = [
    'pending'               => 'bg-blue-100 text-blue-700',
    'in_progress'           => 'bg-yellow-400 text-white',
    'under_review'          => 'bg-orange-400 text-white',
    'waiting_for_materials' => 'bg-purple-400 text-white',
    'completed'             => 'bg-success text-white',
    'rejected'              => 'bg-error text-white',
];

$pct         = $nextRewardThreshold > 0
    ? min(100, round($poinReward / $nextRewardThreshold * 100))
    : 100;
$sisaPoin    = max(0, $nextRewardThreshold - $poinReward);
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-8">

        {{-- ════ HEADING ════ --}}
        <div class="mb-7">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, {{ $name }}</p>
        </div>

        {{-- ════════════════════════════════════════
             ROW 1: Stat cards + Profile card
        ════════════════════════════════════════ --}}
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full h-full">
    
                {{-- Stat: Total Laporan --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 12h6m-6 4h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalLaporan }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Laporan</p>
                    </div>
                </div>
    
                {{-- Stat: Sedang Diproses --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                            <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $sedangDiproses }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Sedang Diproses</p>
                    </div>
                </div>
    
                {{-- Stat: Selesai --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                  stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $selesai }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Selesai</p>
                    </div>
                </div>
    
                {{-- Stat: Poin Reward --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                                  stroke="currentColor" stroke-width="1.75"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                                  stroke="currentColor" stroke-width="1.75"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $poinReward }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Poin Reward</p>
                    </div>
                </div>
            </div>
            {{-- Profile card (span 2 kolom di lg) --}}
            <div class="w-full md:w-max
                        bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                        flex flex-row lg:flex-col items-center lg:items-start gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    {{-- Avatar --}}
                    @if ($picture)
                    <img src="{{ $picture }}" alt="{{ $name }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-primary-100 shrink-0">
                    @else
                    <div class="w-12 h-12 rounded-full bg-primary-500 text-white font-bold text-sm
                                flex items-center justify-center shrink-0">
                        {{ $initials }}
                    </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate">{{ $name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $email }}</p>
                    </div>
                </div>
                <a href="{{ route('citizen.profile') }}"
                   class="shrink-0 lg:w-full px-4 py-2 rounded-xl border border-gray-200
                          text-gray-700 text-xs font-semibold hover:bg-gray-50 transition-colors
                          text-center whitespace-nowrap">
                    Edit Profile
                </a>
            </div>
        </div>


        {{-- ════════════════════════════════════════
             ROW 2: Aksi Cepat + Laporan Terbaru | Notifikasi + Poin
        ════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6">

            {{-- ════ LEFT ════ --}}
            <div class="flex flex-col gap-5">

                {{-- Aksi Cepat --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="grid grid-cols-3 gap-3">

                        <a href="{{ route('reports.create') }}"
                           class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100
                                  hover:border-primary-200 hover:bg-primary-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center
                                        group-hover:bg-primary-100 transition-colors">
                                <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                          stroke="currentColor" stroke-width="1.75"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-900">Buat Laporan</p>
                                <p class="text-xs text-gray-400 mt-0.5">Laporkan masalah baru</p>
                            </div>
                        </a>

                        <a href="{{ route('citizen.reports.index') }}"
                           class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100
                                  hover:border-primary-200 hover:bg-primary-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center
                                        group-hover:bg-yellow-100 transition-colors">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                                    <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-900">Laporan Saya</p>
                                <p class="text-xs text-gray-400 mt-0.5">Lihat semua laporan</p>
                            </div>
                        </a>

                        <a href="{{ route('citizen.reward-claims.index') }}"
                           class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-gray-100
                                  hover:border-primary-200 hover:bg-primary-50 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center
                                        group-hover:bg-green-100 transition-colors">
                                <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                                          stroke="currentColor" stroke-width="1.75"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                                          stroke="currentColor" stroke-width="1.75"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-900">Klaim Reward</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $poinReward }} poin tersedia</p>
                            </div>
                        </a>

                    </div>
                </div>

                {{-- Laporan Terbaru --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-base font-bold text-gray-900">Laporan Terbaru</h2>
                        <a href="{{ route('citizen.reports.index') }}"
                           class="text-sm font-semibold text-primary-500 hover:text-primary-700
                                  transition-colors flex items-center gap-1">
                            Lihat Semua
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                                <path d="M3.5 8h9M9 4.5L12.5 8 9 11.5" stroke="currentColor"
                                      stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>

                    @if ($laporanTerbaru->count() > 0)
                    <div class="flex flex-col divide-y divide-gray-50">
                        @foreach ($laporanTerbaru as $laporan)
                        @php
                        $sl    = $statusLabel[$laporan->status] ?? 'Baru';
                        $sb    = $statusBadge[$laporan->status] ?? 'bg-gray-100 text-gray-600';
                        $cover = $laporan->evidences->where('file_type', 'photo')->first();
                        $tags  = $laporan->tags->take(3);
                        $update = $laporan->latestProgress?->title ?? match($laporan->status) {
                            'pending'    => 'Laporan diterima, menunggu penugasan',
                            'completed'  => 'Laporan telah diselesaikan',
                            'rejected'   => 'Laporan ditolak',
                            default      => 'Sedang diproses',
                        };
                        @endphp
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex gap-3">
                                {{-- Foto --}}
                                <a href="{{ route('reports.show', $laporan->code) }}"
                                   class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 block">
                                    @if ($cover)
                                    <img src="{{ Storage::url($cover->file_path) }}"
                                         alt="{{ $laporan->title }}" loading="lazy"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-200">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                  stroke="currentColor" stroke-width="1.5"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    @endif
                                </a>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs font-mono text-gray-400">{{ $laporan->code }}</span>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $sb }}">
                                            {{ $sl }}
                                        </span>
                                        <span class="text-xs text-gray-400 ml-auto flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 12 12" aria-hidden="true">
                                                <rect x="1" y="1.5" width="10" height="9" rx="1"
                                                      stroke="currentColor" stroke-width="1.1"/>
                                                <path d="M1 4.5h10M4 1.5v1M8 1.5v1" stroke="currentColor"
                                                      stroke-width="1.1" stroke-linecap="round"/>
                                            </svg>
                                            {{ $laporan->created_at->translatedFormat('j M Y') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('reports.show', $laporan->code) }}"
                                       class="text-sm font-semibold text-gray-900 hover:text-primary-500
                                              transition-colors leading-snug block mb-1.5">
                                        {{ $laporan->title }}
                                    </a>
                                    {{-- Tags --}}
                                    @if ($tags->count() > 0)
                                    <div class="flex flex-wrap gap-1.5 mb-1.5">
                                        @foreach ($tags as $tag)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                                     border border-gray-200 text-gray-500 bg-gray-50">
                                            {{ $tag->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                    {{-- Update terakhir --}}
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 12 12" aria-hidden="true">
                                            <path d="M2 6l2.5 2.5L10 3.5" stroke="currentColor"
                                                  stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        {{ $update }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-10">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                      stroke="currentColor" stroke-width="1.5"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">Belum ada laporan</p>
                        <a href="{{ route('reports.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-500
                                  hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                            Buat Laporan Pertama
                        </a>
                    </div>
                    @endif
                </div>

            </div>{{-- end left --}}

            {{-- ════ RIGHT ════ --}}
            <div class="flex flex-col gap-5">

                {{-- Notifikasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    stroke="currentColor" stroke-width="1.75"
                                    stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Notifikasi
                        </h2>
                        @if ($notifications->count() > 0)
                        <span class="w-2 h-2 rounded-full bg-error"></span>
                        @endif
                    </div>
                
                    @if ($notifications->count() > 0)
                    <div class="flex flex-col gap-2">
                        @foreach ($notifications as $notif)
                        <div class="px-3.5 py-3 rounded-xl bg-blue-50 border border-blue-50">
                            {{-- Nomor tiket laporan --}}
                            @if ($notif->report)
                            <p class="text-xs font-semibold text-primary-500 mb-0.5">
                                {{ $notif->report->code }}
                            </p>
                            @endif
                            {{-- Isi pesan --}}
                            <p class="text-xs text-gray-700 leading-relaxed mb-1">
                                {{ Str::limit($notif->message, 80) }}
                            </p>
                            {{-- Waktu kirim --}}
                            <p class="text-xs text-gray-400">
                                {{ $notif->sent_at?->diffForHumans() ?? $notif->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                
                    <a href="{{ route('citizen.reports.index') }}"
                    class="mt-3 block text-center text-sm font-semibold text-primary-500
                            hover:text-primary-700 transition-colors">
                        Lihat Semua Notifikasi
                    </a>
                    @else
                    <div class="text-center py-6">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                stroke="currentColor" stroke-width="1.75"
                                stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-sm text-gray-400">Tidak ada notifikasi.</p>
                    </div>
                    @endif
                </div>


                {{-- Poin Reward --}}
                <div class="bg-primary-500 rounded-2xl p-5 text-white">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                                      stroke="currentColor" stroke-width="1.75"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                                      stroke="currentColor" stroke-width="1.75"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Poin Reward</p>
                            <p class="text-xs text-primary-100">Kumpulkan lebih banyak poin</p>
                        </div>
                    </div>

                    {{-- Poin + target --}}
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-4xl font-bold tabular-nums">{{ $poinReward }}</span>
                        <span class="text-sm text-primary-200">/ {{ $nextRewardThreshold }} poin</span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full bg-white/20 rounded-full h-2 mb-3">
                        <div class="bg-white h-2 rounded-full transition-all"
                             style="width: {{ $pct }}%"></div>
                    </div>

                    @if ($sisaPoin > 0)
                    <p class="text-xs text-primary-100 mb-4">
                        {{ $sisaPoin }} poin lagi untuk mendapatkan reward berikutnya!
                    </p>
                    @else
                    <p class="text-xs text-primary-100 mb-4">
                        Selamat! Anda bisa klaim reward sekarang.
                    </p>
                    @endif

                    <a href="{{ route('citizen.reward-claims.index') }}"
                       class="block w-full py-2.5 rounded-xl bg-white hover:bg-primary-50
                              text-primary-600 text-sm font-bold text-center transition-colors">
                        Klaim Reward
                    </a>
                </div>

            </div>{{-- end right --}}

        </div>
    </div>
</div>

@endsection