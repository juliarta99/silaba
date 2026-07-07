@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ═══════════════════════════════════
     HERO
════════════════════════════════════ --}}
<section class="relative min-h-125 lg:min-h-145 flex items-center">

    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1400&q=80')">
    </div>
    <div class="absolute inset-0"
         style="background:linear-gradient(160deg,rgba(77,10,10,.82) 0%,rgba(154,19,19,.80) 40%,rgba(192,24,24,.88) 100%)">
    </div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-5 sm:px-6 py-14">
        <div class="max-w-2xl">
            <h1 class="text-3xl sm:text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-none mb-3">
                SILABA
            </h1>
            <p class="text-base sm:text-lg md:text-xl font-semibold text-white/90 leading-snug mb-3">
                Sistem Lapor Badung - Layanan Pengaduan Masyarakat Kabupaten Badung
            </p>
            <p class="text-sm sm:text-base text-white/70 mb-8 leading-relaxed max-w-lg">
                Platform transparan untuk melaporkan permasalahan infrastruktur, kebersihan, dan ketertiban umum di wilayah Kabupaten Badung.
            </p>
            <div class="flex flex-wrap gap-3">
                <x-button href="{{ route('reports.create') }}" variant="primary" size="md">
                    Laporkan Sekarang
                </x-button>
                <x-button href="{{ route('reports.index') }}" variant="secondary" size="md">
                    Lacak Laporan
                </x-button>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════
     STATS BAR
════════════════════════════════════ --}}
<section class="bg-white border-b border-gray-10">
    <div class="max-w-7xl mx-auto px-5 sm:px-6 py-8 sm:py-10">
        <div class="grid grid-cols-3 divide-x divide-gray-50">
            <x-stat-card nilai="{{ $stats['completed'] }}"  label="Laporan Terselesaikan" />
            <x-stat-card nilai="{{ $stats['satisfaction'] }}"    label="Tingkat Kepuasan"      />
            <x-stat-card nilai="{{ $stats['avg_days'] }}" label="Rata-rata Penyelesaian"/>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════
     LAPORAN TERBARU
════════════════════════════════════ --}}
<section class="bg-gray-10 py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-5 sm:px-6">

        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Laporan Terbaru</h2>
            <p class="text-sm text-gray-500 mt-2">Transparansi penuh terhadap laporan masyarakat</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($latestReports as $r)
                @php
                    // Ambil foto pertama (jika ada), jika tidak gunakan fallback gambar default
                    $fotoPath = $r->evidences->firstWhere('file_type', 'photo')?->file_path;
                    $fotoUrl  = $fotoPath ? Storage::url($fotoPath) : 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80';
                    
                    // Ambil maksimal 2 nama tag untuk ditampilkan
                    $tagsArray = $r->tags->pluck('name')->take(2)->toArray();
                @endphp
                <x-report-card
                    :id="$r->code"
                    :judul="$r->category->name ?? 'Umum'"
                    :status="$r->status_label"
                    :tags="$tagsArray"
                    :lokasi="($r->district ? 'Kecamatan ' . $r->district->name : 'Kabupaten Badung')"
                    :tanggal="$r->created_at->translatedFormat('j M Y')"
                    :foto="$fotoUrl"
                    :href="route('reports.show', $r->code)"
                />
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10">
                    <p class="text-gray-500 text-sm">Belum ada laporan terbaru saat ini.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-10">
            <x-button href="{{ route('reports.index') }}" variant="outline" size="md">
                Lihat Semua Laporan
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M3.5 8h9M9 4.5L12.5 8 9 11.5"
                              stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot>
            </x-button>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════
     TENTANG SILABA
════════════════════════════════════ --}}
<section class="bg-white py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-5 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Tentang SILABA</h2>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-4">
                    SILABA (Sistem Lapor Badung) adalah platform digital yang dikembangkan oleh
                    Pemerintah Badung untuk memudahkan masyarakat dalam melaporkan permasalahan
                    di wilayah mereka.
                </p>
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
                    Sistem ini dirancang untuk meningkatkan transparansi, akuntabilitas, dan
                    kecepatan respons pemerintah daerah terhadap keluhan masyarakat.
                </p>
                <x-button href="{{ route('about') }}" variant="primary" size="md">
                    Pelajari Lebih Lanjut
                </x-button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-feature-card judul="Mudah Melaporkan" desc="Proses pelaporan yang sederhana dan cepat.">
                    <x-slot name="ikon">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M9 12h6M9 16h6M7 4H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2h-2M9 4a2 2 0 012-2h2a2 2 0 012 2v0a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card judul="Berbasis Lokasi" desc="Laporan dipetakan secara real-time.">
                    <x-slot name="ikon">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card judul="Tracking Real-time" desc="Pantau status laporan kapan saja.">
                    <x-slot name="ikon">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card judul="Notifikasi WhatsApp" desc="Update otomatis via WhatsApp.">
                    <x-slot name="ikon">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                  stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </x-slot>
                </x-feature-card>

            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════
     FAQ
════════════════════════════════════ --}}
<section class="bg-gray-10 py-14 sm:py-16">
    <div class="max-w-2xl mx-auto px-5 sm:px-6">

        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Pertanyaan Umum</h2>
            <p class="text-sm text-gray-500 mt-2">Jawaban untuk pertanyaan yang sering diajukan</p>
        </div>

        @php
        $faqs = [
            ['q' => 'Bagaimana cara membuat laporan?',
             'a' => 'Klik tombol "Laporkan Sekarang", isi form dengan detail kejadian, lokasi, dan foto bukti. Laporan akan langsung masuk ke sistem dan diteruskan ke instansi terkait.'],
            ['q' => 'Bagaimana cara melacak laporan saya?',
             'a' => 'Setelah membuat laporan, Anda mendapat nomor tiket. Gunakan nomor tiket di halaman "Lacak Laporan" untuk melihat status terkini.'],
            ['q' => 'Apa perbedaan jalur tamu dan warga terverifikasi?',
             'a' => 'Jalur tamu bisa langsung melapor tanpa daftar. Warga terverifikasi mendapat fitur lengkap: tracking real-time, notifikasi WhatsApp, reward poin, dan riwayat laporan.'],
            ['q' => 'Berapa lama waktu penyelesaian laporan?',
             'a' => 'Rata-rata penyelesaian adalah 7 hari kerja, tergantung kompleksitas masalah dan instansi yang menangani.'],
        ];
        @endphp

        <div class="flex flex-col gap-3">
            @foreach ($faqs as $faq)
            <div class="bg-white border border-gray-10 rounded-xl shadow-sm overflow-hidden group"
                 x-data="{ open: false }">
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left
                           group-hover:bg-gray-10 transition-colors duration-100"
                    :aria-expanded="open"
                >
                    <span class="text-sm font-medium text-gray-900">{{ $faq['q'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                         :class="{ 'rotate-180': open }"
                         fill="none" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-end="opacity-0"
                    class="px-5 pb-4 group-hover:bg-gray-10"
                >
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════
     HUBUNGI KAMI
════════════════════════════════════ --}}
<section class="bg-white py-14 sm:py-16">
    <div class="max-w-7xl mx-auto px-5 sm:px-6">

        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Hubungi Kami</h2>
            <p class="text-sm text-gray-500 mt-2">Butuh bantuan? Hubungi kami melalui saluran berikut</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">

            <x-contact-card label="Telepon" nilai="(0361) 123-4567" href="tel:+623611234567">
                <x-slot name="ikon">
                    <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-colors duration-150"
                         fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 5.5C3 14.06 9.94 21 18.5 21c.386 0 .77-.014 1.148-.042.435-.032.778-.387.778-.824V16.82a.75.75 0 00-.56-.727l-4.124-1.03a.75.75 0 00-.806.336l-.74 1.21a.75.75 0 01-.872.286 14.39 14.39 0 01-6.19-6.19.75.75 0 01.287-.873l1.21-.74a.75.75 0 00.337-.806L7.94 4.732a.75.75 0 00-.727-.56h-3.34c-.437 0-.792.343-.824.778A11.93 11.93 0 003 5.5z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot>
            </x-contact-card>

            <x-contact-card label="Email" nilai="silaba@badungkab.go.id" href="mailto:silaba@badungkab.go.id">
                <x-slot name="ikon">
                    <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-colors duration-150"
                         fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot>
            </x-contact-card>

            <x-contact-card label="WhatsApp" nilai="+62 812-3456-7890"
                            href="https://wa.me/6281234567890" :external="true">
                <x-slot name="ikon">
                    <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-colors duration-150"
                         fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"
                              stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 2a10 10 0 00-8.603 15.03L2 22l5.083-1.372A10 10 0 1012 2z"
                              stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-slot>
            </x-contact-card>

        </div>
    </div>
</section>

@endsection