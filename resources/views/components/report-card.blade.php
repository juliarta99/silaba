{{--
    Card laporan — dipakai di halaman publik, citizen, admin, dsb.

    Props:
    @prop string      $id       Nomor tiket, e.g. "TKT-2024-001"
    @prop string      $judul    Judul / kategori laporan
    @prop string      $status   'Baru' | 'Diproses' | 'Selesai' | 'Ditolak'
    @prop array       $tags     Array string tag, e.g. ['Jalan Rusak', 'Lubang Aspal']
    @prop string      $lokasi   Nama kecamatan
    @prop string      $tanggal  Tanggal string, e.g. "2 Jun 2026"
    @prop string|null $foto     URL foto evidence (null → placeholder abu)
    @prop string      $href     URL detail laporan (default '#')

    Contoh:
    <x-report-card
        id="TKT-2024-001"
        judul="Infrastruktur"
        status="Diproses"
        :tags="['Jalan Rusak', 'Lubang Aspal']"
        lokasi="Kecamatan Kuta"
        tanggal="2 Jun 2026"
        foto="{{ $report->coverPhotoUrl }}"
        href="{{ route('reports.show', $report) }}"
    />

    Atau dari array dummy:
    @foreach ($reports as $r)
        <x-report-card v-bind="$r" :href="route('reports.show', $r['id'])" />
    @endforeach
--}}

@props([
    'id'      => '',
    'judul'   => '',
    'status'  => 'Baru',
    'tags'    => [],
    'lokasi'  => '',
    'tanggal' => '',
    'foto'    => null,
    'href'    => '#',
])

@php
$statusMap = [
    'Baru'     => 'bg-blue-100  text-blue-700',
    'Diproses' => 'bg-yellow-100 text-yellow-700',
    'Selesai'  => 'bg-green-100 text-green-700',
    'Ditolak'  => 'bg-red-100   text-red-700',
];
$badgeClass = $statusMap[$status] ?? 'bg-gray-10 text-gray-600';
@endphp

<a href="{{ $href }}"
   class="group flex flex-col bg-white rounded-2xl border border-gray-50 overflow-hidden
          shadow-sm hover:shadow-md hover:-translate-y-0.5
          transition-all duration-200">

    {{-- ── Foto ── --}}
    <div class="relative h-44 overflow-hidden shrink-0 bg-gray-100">
        @if ($foto)
            <img
                src="{{ $foto }}"
                alt="{{ $judul }}"
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            >
        @else
            {{-- Placeholder jika tidak ada foto --}}
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        @endif

        {{-- Badge status --}}
        <span class="absolute top-3 left-3 text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeClass }}">
            {{ $status }}
        </span>
    </div>

    {{-- ── Body ── --}}
    <div class="flex flex-col gap-2 p-4 flex-1">

        {{-- ID tiket --}}
        <p class="text-[11px] text-gray-400 font-mono tracking-wide">{{ $id }}</p>

        {{-- Judul --}}
        <h3 class="text-sm font-semibold text-gray-900 leading-snug
                   group-hover:text-primary-500 transition-colors">
            {{ $judul }}
        </h3>

        {{-- Tags --}}
        @if (count($tags))
        <div class="flex flex-wrap gap-1.5">
            @foreach ($tags as $tag)
            <span class="text-[11px] px-2 py-0.5 rounded-full bg-primary-100 text-primary-500">
                {{ $tag }}
            </span>
            @endforeach
        </div>
        @endif

        {{-- Meta: lokasi + tanggal --}}
        <div class="flex flex-col gap-1 mt-auto pt-2 text-xs text-gray-400">

            @if ($lokasi)
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M8 1.5a5.5 5.5 0 00-5.5 5.5c0 3.513 4.424 7.976 5.14 8.67a.5.5 0 00.72 0C9.076 14.976 13.5 10.513 13.5 7A5.5 5.5 0 008 1.5zm0 7a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"
                          clip-rule="evenodd"/>
                </svg>
                {{ $lokasi }}
            </span>
            @endif

            @if ($tanggal)
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M8 1.5a6.5 6.5 0 100 13 6.5 6.5 0 000-13zM0 8a8 8 0 1116 0A8 8 0 010 8zm8-3a.75.75 0 01.75.75v2.69l1.78 1.78a.75.75 0 01-1.06 1.06l-2-2A.75.75 0 017.25 10V5.75A.75.75 0 018 5z"
                          fill="currentColor"/>
                </svg>
                {{ $tanggal }}
            </span>
            @endif

        </div>
    </div>
</a>
