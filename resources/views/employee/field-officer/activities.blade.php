@extends('layouts.app')
@section('title', 'Semua Aktivitas — SILABU')

@section('content')

<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- ── Header & Back Button ── --}}
        <div class="mb-6">
            <a href="{{ route('employee.field-officer.dashboard') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-700 mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 14 14">
                    <path d="M9 3L4 7l5 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Aktivitas</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftar seluruh progress tugas yang Anda kerjakan</p>
        </div>

        {{-- ── Filter & Search ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5 mb-5">
            <form action="{{ route('employee.field-officer.activities') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                
                {{-- Search Input --}}
                <div class="flex-1 relative">
                    <svg class="w-4.5 h-4.5 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode tiket, judul progress..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                </div>

                {{-- Status Filter --}}
                <div class="sm:w-48">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-10 text-base sm:text-sm focus:bg-white focus:ring-1 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="waiting_for_materials" {{ request('status') === 'waiting_for_materials' ? 'selected' : '' }}>Menunggu Material</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- Submit Button (Hidden on change for select, used for search) --}}
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold transition-colors shrink-0">
                    Cari
                </button>
            </form>
        </div>

        {{-- ── List Aktivitas ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
            @if ($activities->count() > 0)
                <div class="space-y-5">
                    @foreach ($activities as $act)
                    @php
                        $actConfig = match($act->status) {
                            'completed'   => ['bg' => 'bg-green-100',  'clr' => 'text-success',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Selesai'],
                            'in_progress' => ['bg' => 'bg-blue-100',   'clr' => 'text-blue-600',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Diproses'],
                            'waiting_for_materials' => ['bg' => 'bg-orange-100', 'clr' => 'text-orange-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Menunggu Material'],
                            'under_review' => ['bg' => 'bg-purple-100', 'clr' => 'text-purple-600', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Verifikasi'],
                            default       => ['bg' => 'bg-gray-100',   'clr' => 'text-gray-500',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Tugas'],
                        };
                    @endphp
                    <div class="flex items-start gap-3.5 pb-5 border-b border-gray-50 last:border-0 last:pb-0">
                        <div class="w-10 h-10 rounded-xl {{ $actConfig['bg'] }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 {{ $actConfig['clr'] }}" fill="none" viewBox="0 0 24 24">
                                <path d="{{ $actConfig['icon'] }}" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $act->title }}</h3>
                                <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $actConfig['bg'] }} {{ $actConfig['clr'] }}">
                                    {{ $actConfig['label'] }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-2">{{ $act->description }}</p>
                            
                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $act->created_at->translatedFormat('j M Y, H:i') }}
                                </span>
                                
                                @if ($act->report)
                                <span class="text-gray-300">•</span>
                                <a href="{{ route('employee.field-officer.assignments.show', $act->report->code) }}" 
                                   class="font-mono text-primary-500 hover:underline">
                                    {{ $act->report->code }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-6 pt-4 border-t border-gray-50">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-14 h-14 rounded-full bg-gray-10 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">Aktivitas tidak ditemukan</p>
                    <p class="text-xs text-gray-500">Cobalah mengubah kata kunci pencarian atau filter status.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<x-employee.bottom-nav active="dashboard" :badge="0" />

@endsection