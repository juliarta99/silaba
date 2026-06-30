@extends('layouts.app')

@section('title', 'Tentang SILABA')

@section('content')
<div class="font-plus-jakarta-sans">

    {{-- ═══════════════════════════════════
         HERO
    ════════════════════════════════════ --}}
    <section class="bg-primary-500 text-white pt-36 pb-16 px-6 md:px-12 lg:px-24">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Tentang SILABA</h1>
            <p class="text-base md:text-lg text-primary-100 max-w-3xl leading-relaxed">
                Sistem Lapor Badung — Platform Pengaduan Masyarakat yang Transparan dan Responsif
            </p>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         LATAR BELAKANG
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-white">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Latar Belakang</h2>
            <div class="text-gray-600 space-y-4 leading-relaxed text-sm md:text-base">
                <p>
                    Sebelum adanya SILABA, masyarakat Kabupaten Badung menggunakan berbagai kanal terpisah
                    untuk menyampaikan pengaduan, termasuk kontak Bupati Badung. Sistem yang terfragmentasi
                    ini menyebabkan beberapa kendala seperti duplikasi laporan, sulitnya pelacakan status,
                    dan kurangnya transparansi dalam penanganan.
                </p>
                <p>
                    SILABA hadir sebagai solusi terpadu untuk mengatasi permasalahan tersebut. Platform ini
                    mengintegrasikan seluruh proses pengaduan masyarakat dalam satu sistem yang terstruktur,
                    transparan, dan mudah diakses. Dengan SILABA, setiap laporan dapat dilacak secara
                    real-time, penanganan menjadi lebih terkoordinasi antar OPD, dan masyarakat mendapatkan
                    kepastian waktu penyelesaian.
                </p>
                <p>
                    Sistem ini dikembangkan dengan memperhatikan kebutuhan berbagai pemangku kepentingan:
                    masyarakat sebagai pelapor, petugas lapangan sebagai eksekutor, supervisor dan kepala
                    dinas sebagai pengawas, hingga Bupati dan Sekda sebagai pengambil kebijakan strategis.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         VISI & MISI
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-gray-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Visi --}}
            <div class="bg-white p-7 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-primary-50 text-primary-500 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Visi</h3>
                <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                    Mewujudkan Kabupaten Badung yang responsif dan transparan dalam pelayanan publik
                    melalui sistem pengaduan masyarakat yang terintegrasi dan berbasis teknologi digital.
                </p>
            </div>

            {{-- Misi --}}
            <div class="bg-white p-7 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-primary-50 text-primary-500 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Misi</h3>
                <ul class="space-y-3 text-gray-600 text-sm md:text-base">
                    @php
                    $misiList = [
                        'Menyediakan platform pengaduan yang mudah diakses oleh seluruh lapisan masyarakat',
                        'Meningkatkan transparansi dan akuntabilitas penanganan pengaduan masyarakat',
                        'Mempercepat respons dan penyelesaian permasalahan di wilayah Kabupaten Badung',
                        'Mendorong partisipasi aktif masyarakat dalam pembangunan daerah',
                    ];
                    @endphp
                    @foreach ($misiList as $misi)
                    <li class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-primary-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="leading-relaxed">{{ $misi }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         ALUR KERJA
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Alur Kerja SILABA</h2>
                <p class="text-sm text-gray-500">Proses penanganan laporan dari pengajuan hingga penyelesaian</p>
            </div>

            @php
            $alurs = [
                ['no' => '1', 'title' => 'Pengajuan Laporan',   'desc' => 'Warga secara langsung melaporkan masalah melalui aplikasi dengan melampirkan foto dan lokasi'],
                ['no' => '2', 'title' => 'Verifikasi Sistem',   'desc' => 'Sistem otomatis mengecek duplikasi laporan dan memetakannya ke OPD yang berwenang'],
                ['no' => '3', 'title' => 'Penugasan Petugas',   'desc' => 'Supervisor menugaskan petugas lapangan untuk menangani laporan'],
                ['no' => '4', 'title' => 'Penanganan Lapangan', 'desc' => 'Petugas menindaklanjuti laporan dan memberikan update progress secara berkala'],
                ['no' => '5', 'title' => 'Verifikasi Warga',    'desc' => 'Warga mengonfirmasi penyelesaian masalah dan memberikan rating kepuasan'],
            ];
            @endphp

            {{-- Desktop: horizontal timeline --}}
            <div class="hidden md:block relative">
                <div class="absolute top-6 left-[10%] right-[10%] h-0.5 bg-gray-100"></div>
                <div class="grid grid-cols-5 gap-4">
                    @foreach ($alurs as $alur)
                    <div class="flex flex-col items-center text-center relative">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center
                                    text-lg font-bold mb-4 shadow-md border-4 border-white shrink-0">
                            {{ $alur['no'] }}
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-2">{{ $alur['title'] }}</h4>
                        <p class="text-xs text-gray-500 leading-relaxed px-2">{{ $alur['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Mobile: vertical timeline --}}
            <div class="md:hidden flex flex-col gap-6 relative">
                <div class="absolute top-6 bottom-6 left-6 w-0.5 bg-gray-100"></div>
                @foreach ($alurs as $alur)
                <div class="flex gap-4 relative">
                    <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center
                                text-lg font-bold shadow-md border-4 border-white shrink-0 z-10">
                        {{ $alur['no'] }}
                    </div>
                    <div class="pt-1">
                        <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $alur['title'] }}</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $alur['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         OPD TERLIBAT
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-gray-10">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">OPD Terlibat</h2>
                <p class="text-sm text-gray-500">Organisasi Perangkat Daerah yang berperan dalam SILABA</p>
            </div>

            @php
            $opds = [
                'Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)',
                'Dinas Lingkungan Hidup dan Kebersihan (DLHK)',
                'Satuan Polisi Pamong Praja (Satpol PP)',
                'Dinas Perhubungan',
                'Dinas Pariwisata',
                'Dinas Kesehatan',
                'Dinas Pendidikan',
                'Badan Penanggulangan Bencana Daerah (BPBD)',
            ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($opds as $opd)
                <div class="bg-white py-4 px-5 rounded-xl shadow-sm border border-gray-100
                            flex items-center gap-3.5 hover:shadow-md hover:border-primary-100
                            transition-all duration-150">
                    <div class="w-10 h-10 rounded-full bg-primary-50 text-primary-500
                                flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 leading-snug">{{ $opd }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         STATISTIK
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Statistik SILABA</h2>
                <p class="text-sm text-gray-500">Data agregat kinerja sistem hingga saat ini</p>
            </div>

            @php
            $stats = [
                [
                    'value' => '1,234', 'label' => 'Total Laporan Terselesaikan',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'value' => '2 Jam', 'label' => 'Rata-rata Waktu Respons',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'value' => '98%', 'label' => 'Tingkat Kepuasan',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'value' => '8', 'label' => 'OPD Terlibat',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                ],
            ];
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($stats as $stat)
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary-50 text-primary-500
                                flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            {!! $stat['icon'] !!}
                        </svg>
                    </div>
                    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary-500 mb-1.5 tabular-nums">
                        {{ $stat['value'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium leading-snug">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         MANFAAT
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-primary-500 text-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold mb-2">Manfaat SILABA</h2>
                <p class="text-sm text-primary-100">Keuntungan menggunakan platform SILABA untuk masyarakat Badung</p>
            </div>

            @php
            $manfaatGroups = [
                [
                    'title' => 'Untuk Masyarakat',
                    'items' => [
                        'Pelaporan mudah dan cepat',
                        'Pelacakan status real-time',
                        'Notifikasi otomatis via WhatsApp',
                        'Sistem reward untuk laporan terverifikasi',
                    ],
                ],
                [
                    'title' => 'Untuk Pemerintah',
                    'items' => [
                        'Koordinasi antar OPD lebih baik',
                        'Data untuk pengambilan keputusan',
                        'Monitoring kinerja real-time',
                        'Transparansi dan akuntabilitas',
                    ],
                ],
                [
                    'title' => 'Untuk Daerah',
                    'items' => [
                        'Peningkatan kualitas layanan publik',
                        'Partisipasi masyarakat lebih tinggi',
                        'Penyelesaian masalah lebih cepat',
                        'Kepercayaan publik meningkat',
                    ],
                ],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($manfaatGroups as $group)
                <div class="bg-white/10 p-7 md:p-8 rounded-2xl border border-white/20">
                    <h3 class="font-bold text-lg mb-4">{{ $group['title'] }}</h3>
                    <ul class="space-y-3 text-sm text-primary-50">
                        @foreach ($group['items'] as $item)
                        <li class="flex gap-2.5 leading-relaxed">
                            <span class="shrink-0">&bull;</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
         CTA PENUTUP
    ════════════════════════════════════ --}}
    <section class="py-16 px-6 md:px-12 lg:px-24 bg-gray-10 text-center">
        <div class="max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Siap Melaporkan Permasalahan Anda?</h2>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                Bergabunglah dengan masyarakat Badung lainnya dalam membangun daerah yang lebih baik
                melalui partisipasi aktif pelaporan.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <x-button href="{{ route('reports.create') }}" variant="primary" size="md">
                    Laporkan Sekarang
                </x-button>
                <x-button href="{{ route('reports.index') }}" variant="outline" size="md">
                    Lihat Semua Laporan
                </x-button>
            </div>
        </div>
    </section>

</div>
@endsection