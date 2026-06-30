@extends('layouts.app')

@section('title', 'Laporan Saya — SILABU')

@section('content')

{{-- ═══════════════════════════════════
     HEADER
════════════════════════════════════ --}}
<section class="bg-white border-b border-gray-50">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pb-8 pt-24 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Laporan Saya</h1>
            <p class="text-sm text-gray-500 mt-1.5">Kelola dan lacak semua laporan Anda</p>
        </div>
        <x-button href="{{ route('reports.create') }}" variant="primary" size="md">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M5 2h6a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.3"/>
                <path d="M6 5.5h4M6 8h4M6 10.5h2.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            Buat Laporan Baru
        </x-button>
    </div>
</section>

{{-- ═══════════════════════════════════
     FILTER BAR
════════════════════════════════════ --}}
<section class="bg-gray-10">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-6">
        <form method="GET" action="{{ route('citizen.reports.index') }}"
              class="bg-white rounded-2xl border border-gray-50 shadow-sm p-5 sm:p-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">

                {{-- Filter Status --}}
                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-2">
                        Filter Status
                    </label>
                    <select
                        id="status" name="status"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                               text-sm text-gray-900
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status') === 'pending' ? 'selected' : '' }}>Baru</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="needs_verification" {{ request('status') === 'needs_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="closed"   {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                {{-- Periode --}}
                <div>
                    <label for="period" class="block text-xs font-semibold text-gray-700 mb-2">
                        Periode
                    </label>
                    <select
                        id="period" name="period"
                        class="w-full px-3 py-2.5 rounded-lg border border-gray-200 bg-gray-10
                               text-sm text-gray-900
                               focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all duration-150"
                    >
                        <option value="">Semua Waktu</option>
                        <option value="7"  {{ request('period') === '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30" {{ request('period') === '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="90" {{ request('period') === '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div>
                    <a href="{{ route('citizen.reports.index') }}"
                       class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg
                              border border-gray-200 text-gray-700 text-sm font-semibold
                              hover:bg-gray-10 transition-colors duration-150">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M2 4h12M5 4V2.5A.5.5 0 015.5 2h5a.5.5 0 01.5.5V4m2 0v9.5a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5V4h10z"
                                  stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Reset Filter
                    </a>
                </div>

            </div>

            <div class="border-t border-gray-50 mt-5 pt-4">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-900">{{ count($reports) }}</span>
                    dari <span class="font-semibold text-gray-900">{{ count($reports) }}</span> laporan
                </p>
            </div>
        </form>
    </div>
</section>

{{-- ═══════════════════════════════════
     LIST LAPORAN
════════════════════════════════════ --}}
<section class="bg-gray-10 pb-16">
    <div class="max-w-5xl mx-auto px-5 sm:px-6 pt-6">

        @php
        // ── DATA DUMMY — ganti dengan data real dari controller ──
        $reports = [
            [
                'id'             => 'TKT-2024-005',
                'judul'          => 'Jalan Berlubang di Jalan Raya Kuta',
                'status'         => 'Diproses',
                'kategori'       => 'Infrastruktur',
                'tags'           => ['Jalan Rusak', 'Lubang Aspal', 'Berbahaya'],
                'alamat'         => 'Jl. Raya Kuta, Kec. Kuta',
                'tanggalLapor'   => '2 Jun 2026',
                'updateTerakhir' => 'Petugas sedang menuju lokasi',
                'foto'           => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&q=70',
                'needsVerification' => false,
                'needsRating'       => false,
            ],
            [
                'id'             => 'TKT-2024-004',
                'judul'          => 'Tumpukan Sampah di Pantai Jimbaran',
                'status'         => 'Menunggu Verifikasi',
                'kategori'       => 'Kebersihan',
                'tags'           => ['Sampah', 'Pantai', 'Limbah Plastik'],
                'alamat'         => 'Pantai Jimbaran, Kec. Kuta Selatan',
                'tanggalLapor'   => '28 Mei 2026',
                'updateTerakhir' => 'Petugas menyatakan selesai, menunggu konfirmasi Anda',
                'foto'           => 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=400&q=70',
                'needsVerification' => true,
                'needsRating'       => false,
            ],
            [
                'id'             => 'TKT-2024-003',
                'judul'          => 'Lampu Jalan Mati di Sunset Road',
                'status'         => 'Ditutup',
                'kategori'       => 'Infrastruktur',
                'tags'           => ['Penerangan Jalan', 'Lampu Mati'],
                'alamat'         => 'Jl. Sunset Road, Kec. Kuta',
                'tanggalLapor'   => '25 Mei 2026',
                'updateTerakhir' => 'Laporan telah diselesaikan dan ditutup',
                'foto'           => 'https://images.unsplash.com/photo-1495805442109-bf1cf975750b?w=400&q=70',
                'needsVerification' => false,
                'needsRating'       => true,
            ],
            [
                'id'             => 'TKT-2024-001',
                'judul'          => 'PKL Parkir di Trotoar',
                'status'         => 'Baru',
                'kategori'       => 'Ketertiban',
                'tags'           => ['Drainase', 'Banjir', 'Saluran'],
                'alamat'         => 'Jl. Pantai Kuta, Kec. Kuta',
                'tanggalLapor'   => '15 Mei 2026',
                'updateTerakhir' => 'Laporan diterima, menunggu penugasan petugas',
                'foto'           => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=70',
                'needsVerification' => false,
                'needsRating'       => false,
            ],
            [
                'id'             => 'TKT-2024-001',
                'judul'          => 'Saluran Air Tersumbat',
                'status'         => 'Baru',
                'kategori'       => 'Infrastruktur',
                'tags'           => ['Drainase', 'Banjir', 'Saluran'],
                'alamat'         => 'Jl. Gatot Subroto, Kec. Mengwi',
                'tanggalLapor'   => '15 Mei 2026',
                'updateTerakhir' => 'Laporan diterima, menunggu penugasan petugas',
                'foto'           => 'https://images.unsplash.com/photo-1604187351574-c75ca79f5807?w=400&q=70',
                'needsVerification' => false,
                'needsRating'       => false,
            ],
        ];
        @endphp

        @if (count($reports) > 0)
        <div class="flex flex-col gap-5">
            @foreach ($reports as $r)
            <x-my-report-card
                id="{{ $r['id'] }}"
                judul="{{ $r['judul'] }}"
                status="{{ $r['status'] }}"
                kategori="{{ $r['kategori'] }}"
                :tags="$r['tags']"
                alamat="{{ $r['alamat'] }}"
                tanggalLapor="{{ $r['tanggalLapor'] }}"
                updateTerakhir="{{ $r['updateTerakhir'] }}"
                foto="{{ $r['foto'] }}"
                href="#"
                :needsVerification="$r['needsVerification']"
                :needsRating="$r['needsRating']"
                confirmHref="#"
                rejectHref="#"
                ratingHref="#"
            />
            @endforeach
        </div>
        @else
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-50">
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
            <x-button href="{{ route('reports.create') }}" variant="primary" size="md">
                Buat Laporan Pertama
            </x-button>
        </div>
        @endif

    </div>
</section>

@endsection