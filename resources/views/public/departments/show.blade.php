@extends('layouts.app') {{-- Sesuaikan dengan layout aplikasi Anda --}}

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-24">
    
    {{-- Tombol Kembali --}}
    <a href="{{ auth()->check() ? route('citizen.dashboard') : route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke @auth Dashboard @else Beranda @endauth
    </a>

    {{-- HEADER OPD --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            {{-- Logo / Initial OPD --}}
            <div class="w-20 h-20 rounded-2xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 border border-primary-100">
                @if($department->logo)
                    <img src="{{ asset('storage/' . $department->logo) }}" alt="Logo {{ $department->name }}" class="w-full h-full object-cover rounded-2xl">
                @else
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $department->name }}</h1>
                    <span class="px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded-lg border border-gray-200">
                        {{ $department->code }}
                    </span>
                </div>
                
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 mt-3">
                    @if($department->email)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ $department->email }}
                    </div>
                    @endif
                    
                    @if($department->phone)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        {{ $department->phone }}
                    </div>
                    @endif
                </div>

                @if($department->address)
                <p class="text-sm text-gray-600 mt-2 flex items-start gap-1.5">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ $department->address }}
                </p>
                @endif
            </div>
            
            {{-- Global Rating --}}
            <div class="text-center bg-gray-51 px-6 py-4 rounded-xl border border-gray-100 min-w-30">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</p>
                <div class="flex items-center justify-center text-yellow-400 my-1">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-xs text-gray-500">Dari {{ $reviews->total() }} Ulasan</p>
            </div>
        </div>
    </div>

    {{-- STATISTIK KINERJA --}}
    <h2 class="text-lg font-bold text-gray-900 mb-4">Statistik Penanganan</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
        {{-- Total --}}
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Laporan Masuk</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
        </div>
        
        {{-- Selesai --}}
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-success flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Laporan Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['completed']) }}</p>
            </div>
        </div>

        {{-- Penyelesaian Tepat Waktu (Pengganti Pending) --}}
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Selesai Tepat Waktu</p>
                <div class="flex items-baseline gap-1">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['on_time_percentage'] }}</p>
                    <span class="text-lg font-semibold text-gray-500">%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ULASAN MASYARAKAT --}}
    <h2 class="text-lg font-bold text-gray-900 mb-4">Ulasan & Rating dari Pelapor</h2>
    
    @if($reviews->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($reviews as $review)
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonim' }}</p>
                            <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    
                    @if($review->comment)
                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-10 p-3 rounded-lg">
                            "{{ $review->comment }}"
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div>
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 border-dashed">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum Ada Ulasan</h3>
            <p class="mt-1 text-sm text-gray-500">OPD ini belum menerima ulasan dari pelapor.</p>
        </div>
    @endif

    {{-- DAFTAR LAPORAN TERKAIT --}}
    <div class="mt-12 mb-6 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-900">Daftar Laporan Terkait</h2>
    </div>

    @if($reports->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($reports as $report)
                <a href="{{ route('reports.show', $report->code) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                    
                    {{-- 1. Gambar Evidence (Bisa disesuaikan dengan relasi evidence di project Anda) --}}
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        {{-- Logika Gambar: Sesuaikan dengan cara Anda menyimpan foto. Ini contoh jika ada relasi/kolom foto --}}
                        @php
                            // Asumsi Anda memiliki relasi $report->evidences atau property foto
                            $imageUrl = isset($report->evidences) && $report->evidences->count() > 0 
                                ? asset('storage/' . $report->evidences->first()->file_path) 
                                : 'https://ui-avatars.com/api/?name=Report&background=f3f4f6&color=9ca3af&size=400';
                        @endphp
                        <img src="{{ $imageUrl }}" alt="Foto Laporan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        {{-- Badge Status Laporan --}}
                        <div class="absolute top-3 right-3">
                            @if($report->status === 'completed')
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full shadow-sm">Selesai</span>
                            @elseif($report->status === 'pending')
                                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full shadow-sm">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow-sm">Diproses</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        {{-- 2. Nomor Tiket & Kategori --}}
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-md">#{{ $report->code }}</span>
                            <span class="text-xs font-medium text-gray-500 truncate max-w-[120px]">{{ $report->category->name ?? 'Kategori Umum' }}</span>
                        </div>

                        {{-- 3. Judul --}}
                        <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-2" title="{{ $report->title }}">
                            {{ $report->title }}
                        </h3>

                        {{-- 4. Lokasi / Info Tambahan --}}
                        <p class="text-xs text-gray-500 mb-4 flex items-start gap-1 line-clamp-1">
                            <svg class="w-4 h-4 shrink-0 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $report->location }}
                        </p>

                        {{-- Spacer --}}
                        <div class="flex-1"></div>

                        {{-- 5. Informasi SLA (Persis seperti "gambar.png") --}}
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            @if($report->status === 'completed')
                                {{-- Jika Sudah Selesai --}}
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-gray-400">Terselesaikan Pada</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($report->updated_at)->translatedFormat('d M Y') }}</span>
                                </div>
                            @elseif($report->sla_deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($report->sla_deadline);
                                    $now = now();
                                    $isOverdue = $now->greaterThan($deadline);
                                    
                                    if ($isOverdue) {
                                        // Menghitung pecahan hari, lalu dibulatkan paksa ke bawah
                                        $daysOverdue = floor($deadline->floatDiffInDays($now));
                                        
                                        $diffText = 'Terlambat ' . $daysOverdue . ' hari';
                                    } else {
                                        // Menghitung sisa waktu tepat ke bawah
                                        $days = floor($now->floatDiffInDays($deadline));
                                        
                                        // Menghitung sisa jam (total jam di-modulo 24)
                                        $hours = floor($now->floatDiffInHours($deadline)) % 24;
                                        
                                        $diffText = $days > 0 ? $days . ' hari ' . $hours . ' jam' : $hours . ' jam';
                                    }
                                @endphp

                                <div class="flex flex-col gap-1.5">
                                    <div class="flex justify-between items-center">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] text-gray-400">Batas Waktu</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $deadline->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Progress / Overdue Bar --}}
                                    <div class="flex flex-col mt-1">
                                        <span class="text-[11px] text-gray-400">Sisa Waktu</span>
                                        <span class="text-sm font-bold {{ $isOverdue ? 'text-error' : 'text-warning' }}">
                                            {{ $diffText }}
                                        </span>
                                        {{-- Visual Bar Horizontal --}}
                                        <div class="w-full h-1.5 rounded-full mt-1.5 {{ $isOverdue ? 'bg-error' : 'bg-warning' }}"></div>
                                    </div>
                                </div>
                            @else
                                {{-- Jika tidak ada SLA --}}
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-gray-400">Batas Waktu</span>
                                    <span class="text-sm font-semibold text-gray-500">Tidak ada batas waktu</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination Laporan --}}
        <div>
            {{ $reports->appends(request()->except('reports_page'))->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 border-dashed mb-10">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum Ada Laporan</h3>
            <p class="mt-1 text-sm text-gray-500">OPD ini belum memiliki laporan masuk.</p>
        </div>
    @endif
</div>
@endsection