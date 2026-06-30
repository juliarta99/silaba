@extends('layouts.app')

@section('title', 'Semua Laporan — SILABU')

@section('content')

{{-- ═══════════════════════════════════
     HEADER
════════════════════════════════════ --}}
<section class="bg-white border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 pt-32 pb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Semua Laporan</h1>
        <p class="text-sm text-gray-500 mt-1.5">
            Transparansi penuh terhadap seluruh laporan masyarakat Kabupaten Badung
        </p>
    </div>
</section>

{{-- ═══════════════════════════════════
     FILTER BAR
════════════════════════════════════ --}}
<section class="bg-gray-10 border-b border-gray-50">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 py-6">
        <form method="GET" action="{{ route('reports.index') }}"
              class="bg-white rounded-2xl border border-gray-50 shadow-sm p-5 sm:p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Cari Laporan --}}
                <div class="lg:col-span-1">
                    <label for="search" class="block text-xs font-semibold text-gray-700 mb-2">
                        Cari Laporan
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                             fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M11 11l3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="text" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan judul atau nomor tiket..."
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                                   text-sm text-gray-900 placeholder-gray-400
                                   focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                                   outline-none transition-all duration-150"
                        >
                    </div>
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category" class="block text-xs font-semibold text-gray-700 mb-2">
                        Kategori
                    </label>
                    <select
                        id="category" name="category"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                               text-sm text-gray-900
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach ($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label for="district" class="block text-xs font-semibold text-gray-700 mb-2">
                        Kecamatan
                    </label>
                    <select
                        id="district" name="district"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                               text-sm text-gray-900
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts ?? [] as $district)
                        <option value="{{ $district->id }}" {{ request('district') == $district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <select
                        id="status" name="status"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                               text-sm text-gray-900
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending"     {{ request('status') === 'pending' ? 'selected' : '' }}>Baru</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed"   {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected"    {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

            </div>

            {{-- Auto-submit on change via Alpine (opsional) --}}
            <button type="submit" class="sr-only">Terapkan Filter</button>
        </form>

        {{-- Info hasil + Reset --}}
        <div class="flex items-center justify-between mt-5">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-semibold text-gray-900">{{ $reports->count() }}</span>
                dari <span class="font-semibold text-gray-900">{{ $reports->total() ?? $reports->count() }}</span> laporan
            </p>

            @if (request()->anyFilled(['search', 'category', 'district', 'status']))
            <a href="{{ route('reports.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M2 4h12M5 4V2.5A.5.5 0 015.5 2h5a.5.5 0 01.5.5V4m2 0v9.5a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5V4h10z"
                          stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Reset Filter
            </a>
            @endif
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════
     GRID LAPORAN
════════════════════════════════════ --}}
<section class="bg-gray-10 pb-16">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 pt-8">

        @if ($reports->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($reports as $report)
            <x-report-card
                id="{{ $report->ticket_number }}"
                judul="{{ $report->title }}"
                kategori="{{ $report->category->name ?? '' }}"
                status="{{ match($report->status) {
                    'pending'     => 'Baru',
                    'in_progress' => 'Diproses',
                    'completed'   => 'Selesai',
                    'rejected'    => 'Ditolak',
                    default       => 'Baru',
                } }}"
                :tags="$report->tags->pluck('name')->toArray() ?? []"
                lokasi="Kec. {{ $report->district->name ?? '' }}"
                tanggal="{{ $report->created_at->translatedFormat('j M Y') }}"
                pelapor="{{ $report->citizen->user->name ?? $report->guest_name ?? 'Tamu' }}"
                foto="{{ $report->evidences->first()->photo_url ?? null }}"
                href="{{ route('reports.show', $report) }}"
            />
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($reports->hasPages())
        <div class="mt-10">
            {{ $reports->links() }}
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Tidak ada laporan ditemukan</h3>
            <p class="text-sm text-gray-500 max-w-sm">
                Coba ubah filter pencarian Anda, atau
                <a href="{{ route('reports.index') }}" class="text-primary-500 font-medium hover:underline">reset filter</a>
                untuk melihat semua laporan.
            </p>
        </div>
        @endif

    </div>
</section>

@endsection