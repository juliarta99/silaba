{{--
    Card laporan horizontal — khusus halaman "Laporan Saya" (citizen).
    Beda dari report-card biasa: layout horizontal, ada action buttons
    kontekstual sesuai status, dan banner alert untuk status tertentu.

    Props:
    @prop string      $id           Nomor tiket
    @prop string      $judul        Judul laporan
    @prop string      $status       'Baru' | 'Diproses' | 'Menunggu Verifikasi' | 'Ditutup'
    @prop string      $kategori     Kategori utama (badge merah)
    @prop array       $tags         Tag tambahan (badge biru outline)
    @prop string      $alamat       Alamat lengkap
    @prop string      $tanggalLapor Tanggal pelaporan
    @prop string      $updateTerakhir Catatan update terakhir
    @prop string|null $foto         URL foto (null → placeholder)
    @prop string      $href         URL detail laporan
    @prop bool        $needsVerification  Tampilkan banner + tombol konfirmasi selesai
    @prop bool        $needsRating        Tampilkan banner + tombol beri rating
    @prop string|null $ratingHref         URL halaman beri rating
    @prop string|null $confirmHref        URL aksi konfirmasi selesai
    @prop string|null $rejectHref         URL aksi laporkan belum selesai

    Contoh:
    <x-my-report-card
        id="TKT-2024-004"
        judul="Tumpukan Sampah di Pantai Jimbaran"
        status="Menunggu Verifikasi"
        kategori="Kebersihan"
        :tags="['Sampah', 'Pantai', 'Limbah Plastik']"
        alamat="Pantai Jimbaran, Kec. Kuta Selatan"
        tanggalLapor="28 Mei 2026"
        updateTerakhir="Petugas menyatakan selesai, menunggu konfirmasi Anda"
        foto="..."
        href="..."
        :needsVerification="true"
        confirmHref="..."
        rejectHref="..."
    />
--}}

@props([
    'id'              => '',
    'judul'           => '',
    'status'          => 'Baru',
    'kategori'        => '',
    'tags'            => [],
    'alamat'          => '',
    'tanggalLapor'    => '',
    'updateTerakhir'  => '',
    'foto'            => null,
    'href'            => '#',
    'needsVerification' => false,
    'needsRating'        => false,
    'ratingHref'         => null,
    'confirmHref'        => null,
    'rejectHref'         => null,
])

@php
$statusMap = [
    'Baru'                 => 'bg-blue-500 text-white',
    'Diproses'             => 'bg-yellow-500 text-white',
    'Menunggu Verifikasi'  => 'bg-orange-500 text-white',
    'Ditutup'              => 'bg-gray-100 text-white',
    'Selesai'              => 'bg-success text-white',
];
$statusBadgeClass = $statusMap[$status] ?? 'bg-gray-400 text-white';

// Wrapper card punya border oranye highlight jika butuh aksi user
$needsAction = $needsVerification || $needsRating;
$wrapperClass = $needsAction
    ? 'border-2 border-orange-300 shadow-sm'
    : 'border border-gray-100 shadow-sm';
@endphp

<div class="bg-white rounded-2xl {{ $wrapperClass }} overflow-hidden">

    {{-- ── Banner alert (jika perlu aksi) ── --}}
    @if ($needsVerification)
    <div class="flex items-center gap-2.5 px-5 py-3 bg-orange-50 border-b border-orange-100">
        <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center shrink-0 text-xs font-bold">
            !
        </span>
        <p class="text-sm font-medium text-orange-700">
            Perlu Verifikasi: Konfirmasi apakah masalah sudah selesai
        </p>
    </div>
    @elseif ($needsRating)
    <div class="flex items-center gap-2.5 px-5 py-3 bg-orange-50 border-b border-orange-100">
        <span class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center shrink-0 text-xs font-bold">
            !
        </span>
        <p class="text-sm font-medium text-orange-700">
            Beri Rating: Bantu kami meningkatkan layanan dengan memberikan penilaian
        </p>
    </div>
    @endif

    {{-- ── Body: foto | info | actions ── --}}
    <div class="flex flex-col sm:flex-row gap-5 p-5">

        {{-- Foto --}}
        <a href="{{ $href }}" class="block w-full sm:w-44 h-44 sm:h-auto shrink-0 rounded-xl overflow-hidden bg-gray-100">
            @if ($foto)
                <img src="{{ $foto }}" alt="{{ $judul }}" loading="lazy"
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

            {{-- Tiket + Status + Kategori --}}
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="text-xs font-mono text-gray-400">{{ $id }}</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusBadgeClass }}">
                    {{ $status }}
                </span>
                @if ($kategori)
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-primary-50 text-primary-500">
                    {{ $kategori }}
                </span>
                @endif
            </div>

            {{-- Judul --}}
            <h3 class="text-base font-bold text-gray-900 mb-2 leading-snug">
                <a href="{{ $href }}" class="hover:text-primary-500 transition-colors">{{ $judul }}</a>
            </h3>

            {{-- Tags --}}
            @if (count($tags))
            <div class="flex flex-wrap gap-1.5 mb-3">
                @foreach ($tags as $tag)
                <span class="text-xs font-medium px-2.5 py-1 rounded-full border border-blue-200 text-blue-600 bg-blue-50/50">
                    {{ $tag }}
                </span>
                @endforeach
            </div>
            @endif

            {{-- Meta --}}
            <div class="flex flex-col gap-1.5 text-sm text-gray-500">

                @if ($alamat)
                <span class="flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M8 1.5a5.5 5.5 0 00-5.5 5.5c0 3.513 4.424 7.976 5.14 8.67a.5.5 0 00.72 0C9.076 14.976 13.5 10.513 13.5 7A5.5 5.5 0 008 1.5zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"
                              clip-rule="evenodd"/>
                    </svg>
                    {{ $alamat }}
                </span>
                @endif

                @if ($tanggalLapor)
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                        <rect x="2" y="3" width="12" height="11" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M2 6.5h12M5 1.5v2M11 1.5v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    Dilaporkan: {{ $tanggalLapor }}
                </span>
                @endif

                @if ($updateTerakhir)
                <span class="flex items-start gap-2">
                    <svg class="w-4 h-4 shrink-0 mt-0.5 text-gray-400" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M5 2h6a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M6 5.5h4M6 8h4M6 10.5h2.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <span>Update terakhir: {{ $updateTerakhir }}</span>
                </span>
                @endif

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2.5 w-full sm:w-48 shrink-0">

            <a href="{{ $href }}"
               class="flex items-center justify-center px-4 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-700
                      text-white text-sm font-semibold transition-colors duration-150 text-center">
                Lihat Detail
            </a>

            @if ($needsVerification)
                <a href="{{ $confirmHref }}"
                   class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-success hover:opacity-90
                          text-white text-sm font-semibold transition-opacity duration-150">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Konfirmasi Selesai
                </a>
                <a href="{{ $rejectHref }}"
                   class="flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-200
                          text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors duration-150">
                    Laporkan Belum Selesai
                </a>
            @endif

            @if ($needsRating)
                <a href="{{ $ratingHref }}"
                   class="flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-secondary-500 hover:bg-secondary-700
                          text-gray-900 text-sm font-semibold transition-colors duration-150">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8 1l1.9 4.5L15 6l-3.6 3.2L12.4 14 8 11.5 3.6 14l1-4.8L1 6l5.1-.5L8 1z"/>
                    </svg>
                    Beri Rating
                </a>
            @endif

        </div>
    </div>
</div>