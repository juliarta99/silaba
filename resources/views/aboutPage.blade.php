@extends('layouts.app')

@section('content')
    <div class="font-plus-jakarta-sans">

        <section class="bg-primary-500 text-white py-16 px-6 md:px-12 lg:px-24">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">Tentang SILABA</h1>
                <p class="text-base md:text-lg text-primary-100 max-w-3xl">
                    Sistem Lapor Badung - Platform Pengaduan Masyarakat yang Transparan dan Responsif
                </p>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-white">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Latar Belakang</h2>
                <div class="text-gray-700 space-y-4 leading-relaxed text-justify">
                    <p>
                        Sebelum adanya SILABA, masyarakat Kabupaten Badung menggunakan berbagai kanal terpisah untuk menyampaikan pengaduan, termasuk kontak Bupati Badung. Sistem yang terfragmentasi ini menyebabkan beberapa kendala seperti duplikasi laporan, sulitnya pelacakan status, dan kurangnya transparansi dalam penanganan.
                    </p>
                    <p>
                        SILABA hadir sebagai solusi terpadu untuk mengatasi permasalahan tersebut. Platform ini mengintegrasikan seluruh proses pengaduan masyarakat dalam satu sistem yang terstruktur, transparan, dan mudah diakses. Dengan SILABA, setiap laporan dapat dilacak secara real-time, penanganan menjadi lebih terkoordinasi antar OPD, dan masyarakat mendapatkan kepastian waktu penyelesaian.
                    </p>
                    <p>
                        Sistem ini dikembangkan dengan memperhatikan kebutuhan berbagai pemangku kepentingan: masyarakat sebagai pelapor, petugas lapangan sebagai eksekutor, supervisor dan kepala dinas sebagai pengawas, hingga Bupati dan Sekda sebagai pengambil kebijakan strategis.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-gray-50">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-primary-100 text-primary-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Visi</h3>
                    <p class="text-gray-700 leading-relaxed">
                        Mewujudkan Kabupaten Badung yang responsif dan transparan dalam pelayanan publik melalui sistem pengaduan masyarakat yang terintegrasi dan berbasis teknologi digital.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-primary-100 text-primary-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Misi</h3>
                    <ul class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-primary-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Menyediakan platform pengaduan yang mudah diakses oleh seluruh lapisan masyarakat</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-primary-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Meningkatkan transparansi dan akuntabilitas penanganan pengaduan masyarakat</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-primary-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Mempercepat respons dan penyelesaian permasalahan di wilayah Kabupaten Badung</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-primary-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Mendorong partisipasi aktif masyarakat dalam pembangunan daerah</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-white text-center">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Alur Kerja SILABA</h2>
                <p class="text-gray-500 mb-16">Proses penanganan laporan dari pengajuan hingga penyelesaian</p>

                <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center w-full">
                    <div class="hidden md:block absolute top-6 left-10 right-10 h-[2px] bg-gray-100 -z-10"></div>

                    @php
                        $alurs = [
                            ['no' => '1', 'title' => 'Pengajuan Laporan', 'desc' => 'Warga secara langsung melaporkan masalah melalui aplikasi dengan melampirkan foto dan lokasi'],
                            ['no' => '2', 'title' => 'Verifikasi Sistem', 'desc' => 'Sistem otomatis mengecek duplikasi laporan dan memetakannya OPD yang berwenang'],
                            ['no' => '3', 'title' => 'Penugasan Petugas', 'desc' => 'Supervisor menugaskan petugas lapangan untuk menangani laporan'],
                            ['no' => '4', 'title' => 'Penanganan Lapangan', 'desc' => 'Petugas menindaklanjuti laporan dan memberikan update progress secara berkala'],
                            ['no' => '5', 'title' => 'Verifikasi Warga', 'desc' => 'Warga mengkonfirmasi penyelesaian masalah dan memberikan rating kepuasan'],
                        ];
                    @endphp

                    @foreach($alurs as $alur)
                    <div class="flex flex-col items-center flex-1 w-full z-10 mb-8 md:mb-0 relative bg-white md:bg-transparent">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center text-xl font-bold mb-4 shadow-md border-4 border-white">
                            {{ $alur['no'] }}
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm md:text-base mb-2">{{ $alur['title'] }}</h4>
                        <p class="text-xs text-gray-500 px-2 md:px-4">{{ $alur['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-gray-50 text-center">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">OPD Terlibat</h2>
                <p class="text-gray-500 mb-10">Organisasi Perangkat Daerah yang berperan dalam SILABA</p>

                @php
                    $opds = [
                        'Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)', 'Dinas Lingkungan Hidup dan Kebersihan (DLHK)',
                        'Satuan Polisi Pamong Praja (Satpol PP)', 'Dinas Perhubungan',
                        'Dinas Pariwisata', 'Dinas Kesehatan', 'Dinas Pendidikan', 'Badan Penanggulangan Bencana Daerah (BPBD)'
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($opds as $opd)
                    <div class="bg-white py-4 px-5 rounded-lg shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 text-left leading-snug">{{ $opd }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-white text-center">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Statistik SILABA</h2>
                <p class="text-gray-500 mb-12">Data agregat kinerja sistem hingga saat ini</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-bold text-primary-500 mb-2">1,234</h3>
                        <p class="text-sm text-gray-500 font-medium">Total Laporan Terselesaikan</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-bold text-primary-500 mb-2">2 Jam</h3>
                        <p class="text-sm text-gray-500 font-medium">Rata-rata Waktu Respons</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-bold text-primary-500 mb-2">98%</h3>
                        <p class="text-sm text-gray-500 font-medium">Tingkat Kepuasan</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-primary-50 text-primary-500 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-3xl md:text-4xl font-bold text-primary-500 mb-2">8</h3>
                        <p class="text-sm text-gray-500 font-medium">OPD Terlibat</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 px-6 md:px-12 lg:px-24 bg-primary-500 text-white text-center">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-2xl font-bold mb-2">Manfaat SILABA</h2>
                <p class="text-primary-100 mb-12">Keuntungan menggunakan platform SILABA untuk masyarakat Badung</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white/10 p-8 rounded-xl border border-primary-500">
                        <h3 class="font-bold text-lg mb-4">Untuk Masyarakat</h3>
                        <ul class="space-y-3 text-sm text-primary-50">
                            <li class="flex gap-2"><span>&bull;</span> Pelaporan mudah dan cepat</li>
                            <li class="flex gap-2"><span>&bull;</span> Pelacakan status real-time</li>
                            <li class="flex gap-2"><span>&bull;</span> Notifikasi otomatis via WhatsApp</li>
                            <li class="flex gap-2"><span>&bull;</span> Sistem reward untuk laporan terverifikasi</li>
                        </ul>
                    </div>
                    <div class="bg-white/10 p-8 rounded-xl border border-primary-500">
                        <h3 class="font-bold text-lg mb-4">Untuk Pemerintah</h3>
                        <ul class="space-y-3 text-sm text-primary-50">
                            <li class="flex gap-2"><span>&bull;</span> Koordinasi antar OPD lebih baik</li>
                            <li class="flex gap-2"><span>&bull;</span> Data untuk pengambilan keputusan</li>
                            <li class="flex gap-2"><span>&bull;</span> Monitoring kinerja real-time</li>
                            <li class="flex gap-2"><span>&bull;</span> Transparansi dan akuntabilitas</li>
                        </ul>
                    </div>
                    <div class="bg-white/10 p-8 rounded-xl border border-primary-500">
                        <h3 class="font-bold text-lg mb-4">Untuk Daerah</h3>
                        <ul class="space-y-3 text-sm text-primary-50">
                            <li class="flex gap-2"><span>&bull;</span> Peningkatan kualitas layanan publik</li>
                            <li class="flex gap-2"><span>&bull;</span> Partisipasi masyarakat lebih tinggi</li>
                            <li class="flex gap-2"><span>&bull;</span> Penyelesaian masalah lebih cepat</li>
                            <li class="flex gap-2"><span>&bull;</span> Kepercayaan publik meningkat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
