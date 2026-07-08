@extends('layouts.app')
@section('title', 'Detail Tugas — ' . $report->code)

@section('content')

@php
$statusConfig = [
    'pending'               => ['label' => 'Menunggu',           'bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
    'in_progress'           => ['label' => 'Diproses',           'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
    'waiting_for_materials' => ['label' => 'Menunggu Material',  'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'under_review'          => ['label' => 'Menunggu Verifikasi','bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    'completed'             => ['label' => 'Selesai',            'bg' => 'bg-green-100',  'text' => 'text-success'],
    'rejected'              => ['label' => 'Ditolak',            'bg' => 'bg-red-100',    'text' => 'text-error'],
];
$priorityConfig = [
    'critical' => ['label' => 'Mendesak', 'bg' => 'bg-red-100',    'text' => 'text-error'],
    'high'     => ['label' => 'Tinggi',   'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
    'medium'   => ['label' => 'Sedang',   'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
    'low'      => ['label' => 'Rendah',   'bg' => 'bg-green-100',  'text' => 'text-success'],
];
$status   = $statusConfig[$report->status]   ?? $statusConfig['pending'];
$priority = $priorityConfig[$report->priority] ?? $priorityConfig['medium'];
$citizen  = $report->user?->citizen;
$canUpdate = ! in_array($report->status, ['completed', 'rejected']);
@endphp

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">

    {{-- ── Sticky Header (mobile) ── --}}
    <div class="bg-white border-b border-gray-100 sticky top-17 z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex flex-col sm:flex-row items-start justify-between gap-3">
            <div>
                <a href="{{ route('employee.field-officer.assignments.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-700 mb-0.5 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 14 14">
                        <path d="M9 3L4 7l5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Daftar Tugas
                </a>
                <p class="text-xs text-gray-400 font-medium">Nomor Tiket</p>
                <h1 class="text-lg sm:text-xl font-bold text-gray-900 font-mono tracking-wide">{{ $report->code }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 line-clamp-1">{{ $report->title }}</p>
            </div>
            @if ($canUpdate)
            <a href="{{ route('employee.field-officer.assignments.progress.create', $report->code) }}"
               class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                      text-white text-xs sm:text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M11.5 2.5a2.121 2.121 0 013 3L5 15H1v-4L11.5 2.5z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>
                    Update Progress
                </span>
            </a>
            @endif
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-5">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── KOLOM KIRI (2/3) ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- SLA Tracker --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                                <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                            </svg>
                            <span class="text-sm font-bold text-gray-900">SLA Tracker</span>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                                     {{ $slaInfo['expired'] ? 'bg-red-100 text-error' : ($slaInfo['urgent'] ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-success') }}">
                            {{ $slaInfo['text'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                        <span>Progress Waktu</span>
                        <span class="font-semibold">{{ $slaInfo['percent'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-3">
                        <div class="h-2 rounded-full transition-all {{ $slaInfo['expired'] ? 'bg-error' : ($slaInfo['urgent'] ? 'bg-yellow-400' : 'bg-primary-500') }}"
                             style="width: {{ $slaInfo['percent'] }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Total SLA: <strong class="text-gray-800">{{ $slaInfo['total_hours'] }} jam</strong></span>
                        @if ($report->sla_deadline)
                        <span>Deadline: <strong class="{{ $slaInfo['expired'] ? 'text-error' : 'text-gray-800' }}">
                            {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y, H:i') }}
                        </strong></span>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi + Tags --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-3">Deskripsi Laporan</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $report->description }}</p>

                    @if ($report->tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($report->tags as $tag)
                        <span class="text-xs px-2.5 py-1 rounded-full bg-primary-50 text-primary-600 border border-primary-100">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Foto Bukti --}}
                @if ($report->evidences->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-3">
                        Foto Bukti ({{ $report->evidences->count() }})
                    </h2>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($report->evidences as $ev)
                        <a href="{{ Storage::url($ev->file_path) }}" target="_blank"
                           class="aspect-square rounded-xl overflow-hidden block bg-gray-100 hover:opacity-90 transition-opacity">
                            @if ($ev->file_type === 'video')
                            <video src="{{ Storage::url($ev->file_path) }}"
                                   class="w-full h-full object-cover" muted></video>
                            @else
                            <img src="{{ Storage::url($ev->file_path) }}"
                                 class="w-full h-full object-cover" alt="">
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Update Progress (riwayat) --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Update Progress</h2>
                        @if ($canUpdate)
                        <a href="{{ route('employee.field-officer.assignments.progress.create', $report->code) }}"
                           class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                            + Tambah Update
                        </a>
                        @endif
                    </div>

                    @if ($report->progresses->count() > 0)
                    <div class="space-y-4">
                        @foreach ($report->progresses as $prog)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-primary-500 shrink-0 mt-1"></div>
                                @if (! $loop->last)
                                <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm font-bold text-gray-900 mb-0.5">{{ $prog->title }}</p>
                                <p class="text-xs text-gray-600 leading-relaxed mb-2">{{ $prog->description }}</p>
                                @if ($prog->photo)
                                <a href="{{ Storage::url($prog->photo) }}" target="_blank"
                                   class="block w-20 h-16 rounded-lg overflow-hidden mb-2 hover:opacity-90 transition-opacity">
                                    <img src="{{ Storage::url($prog->photo) }}" class="w-full h-full object-cover" alt="">
                                </a>
                                @endif
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span>{{ $prog->created_at->translatedFormat('j M Y, H:i') }}</span>
                                    @if ($prog->employee?->user)
                                    <span>•</span>
                                    <span>oleh {{ $prog->employee->user->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 text-center py-6">Belum ada update progress</p>
                    @endif
                </div>

                {{-- Timeline Status --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4">Timeline Status</h2>
                    <div class="space-y-4">
                        @if ($report->assignments->first())
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-success shrink-0 mt-1"></div>
                                <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm font-bold text-gray-900">Ditugaskan ke Petugas</p>
                                <p class="text-xs text-gray-500">Tugas ditugaskan kepada {{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $report->assignments->first()->created_at->translatedFormat('j M Y, H:i') }}
                                    <span class="mx-1">•</span> Admin OPD
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-success shrink-0 mt-1"></div>
                                <div class="w-px flex-1 bg-gray-200 mt-1"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm font-bold text-gray-900">Laporan Diterima</p>
                                <p class="text-xs text-gray-500">Laporan telah diverifikasi dan diterima oleh sistem</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $report->created_at->addHour()->translatedFormat('j M Y, H:i') }}
                                    <span class="mx-1">•</span> Sistem
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-success shrink-0 mt-1"></div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">Laporan Dibuat</p>
                                <p class="text-xs text-gray-500">Laporan dibuat oleh warga</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $report->created_at->translatedFormat('j M Y, H:i') }}
                                    <span class="mx-1">•</span>
                                    {{ $report->user?->name ?? $report->guest_name ?? 'Tamu' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── KOLOM KANAN (1/3) ── --}}
            <div class="space-y-5">

                {{-- Informasi Status --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4">Informasi Status</h2>
                    <div class="space-y-3.5">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Status</p>
                            <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full
                                         {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Prioritas</p>
                            <span class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full
                                         {{ $priority['bg'] }} {{ $priority['text'] }}">
                                {{ $priority['label'] }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-1">Kategori</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $report->category?->name ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Data Pelapor --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Data Pelapor
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Nama</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->user?->name ?? $report->guest_name ?? 'Tamu' }}
                            </p>
                        </div>
                        @if ($report->user)
                        <div>
                            <p class="text-xs text-gray-400">NIK</p>
                            <p class="text-sm font-semibold text-gray-900 font-mono">
                                {{ $report->user->identifier }}
                            </p>
                        </div>
                        @endif
                        @if ($citizen?->phone ?? $report->guest_phone)
                        <div>
                            <p class="text-xs text-gray-400">Telepon</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $citizen?->phone ?? $report->guest_phone) }}"
                               target="_blank"
                               class="text-sm font-semibold text-primary-500 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                                    <path d="M2.667 2h2.666L6.667 5.333 5 6.667c.667 1.333 2 2.666 3.333 3.333l1.334-1.667L13 9.333V12c0 .4-1.333 2-5.333.667C3.333 11.333 1.333 8 2.667 2z"
                                          stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $citizen?->phone ?? $report->guest_phone }}
                            </a>
                        </div>
                        @endif
                        @if ($citizen?->email)
                        <div>
                            <p class="text-xs text-gray-400">Email</p>
                            <a href="mailto:{{ $citizen->email }}"
                               class="text-sm font-semibold text-primary-500 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                                    <path d="M2 4l6 5 6-5M2 4h12v8H2V4z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $citizen->email }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="currentColor" stroke-width="1.75"/>
                        </svg>
                        Lokasi
                    </h2>
                    <div class="space-y-2.5 mb-4">
                        <div>
                            <p class="text-xs text-gray-400">Alamat</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $report->location ?? '—' }}</p>
                        </div>
                        @if ($report->district)
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-xs text-gray-400">Kecamatan</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $report->district->name }}</p>
                            </div>
                        </div>
                        @endif
                        @if ($report->latitude && $report->longitude)
                        <div>
                            <p class="text-xs text-gray-400">Koordinat</p>
                            <p class="text-xs font-mono text-gray-700">
                                {{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}
                            </p>
                        </div>
                        @endif
                    </div>
                    @if ($report->latitude && $report->longitude)
                    <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}"
                       target="_blank"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl
                              bg-primary-500 hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                            <path d="M8 1.5C5.79 1.5 4 3.29 4 5.5c0 2.625 4 9 4 9s4-6.375 4-9c0-2.21-1.79-4-4-4zm0 5.75a1.75 1.75 0 110-3.5 1.75 1.75 0 010 3.5z"
                                  fill="currentColor"/>
                        </svg>
                        Buka di Google Maps
                    </a>
                    @endif
                </div>

                {{-- Informasi Waktu --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.75"/>
                            <path d="M3 9h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
                        </svg>
                        Informasi Waktu
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-400">Dilaporkan</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->created_at->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Diterima Sistem</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->created_at->addHour()->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        @if ($report->assignments->first())
                        <div>
                            <p class="text-xs text-gray-400">Ditugaskan</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $report->assignments->first()->created_at->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        @endif
                        @if ($report->sla_deadline)
                        <div>
                            <p class="text-xs text-gray-400">Deadline</p>
                            <p class="text-sm font-bold {{ $slaInfo['expired'] ? 'text-error' : 'text-gray-900' }}">
                                {{ \Carbon\Carbon::parse($report->sla_deadline)->translatedFormat('j M Y, H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<x-employee.bottom-nav active="assignments" :badge="0" />

@endsection