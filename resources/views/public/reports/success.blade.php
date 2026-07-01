@extends('layouts.app')

@section('title', 'Laporan Berhasil Dikirim — SILABU')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-68px)] py-12 px-4 sm:px-6">
    <div class="max-w-lg mx-auto">

        {{-- ── Icon + Heading ── --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-success/15 flex items-center justify-center mx-auto mb-5">
                <svg class="w-11 h-11 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" fill="currentColor" opacity="0.15"/>
                    <path d="M7.5 12.5l3 3 6-6" stroke="currentColor" stroke-width="2.25"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Laporan Berhasil Dikirim!</h1>
            <p class="text-sm text-gray-500">Terima kasih telah melaporkan masalah di Kabupaten Badung</p>
        </div>

        {{-- ── Card: Nomor Tiket ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">

            {{-- Nomor tiket --}}
            <div class="text-center mb-6">
                <p class="text-xs font-medium text-gray-400 mb-2.5 uppercase tracking-wider">
                    Nomor Tiket Laporan Anda
                </p>
                <div class="inline-block px-6 py-3 rounded-xl border-2 border-primary-500">
                    <span class="text-2xl font-bold font-mono tracking-widest text-primary-500">
                        {{ $report->code ?? 'TKT-' . now()->year . '-XXX' }}
                    </span>
                </div>
            </div>

            {{-- Laporan diterima --}}
            <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50 border border-green-100 mb-3">
                <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Laporan Diterima</p>
                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        Laporan Anda telah masuk ke sistem SILABU dan akan segera diproses
                        oleh petugas terkait.
                    </p>
                </div>
            </div>

            {{-- Detail dikirim ke WhatsApp --}}
            @if (isset($maskedPhone))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100">
                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Detail Dikirim ke WhatsApp</p>
                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                        Detail laporan dan nomor tiket telah dikirim ke WhatsApp
                        <span class="font-semibold text-primary-500">{{ $maskedPhone }}</span>
                    </p>
                </div>
            </div>
            @endif

        </div>

        {{-- ── Card: Apa yang terjadi selanjutnya ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
            <h2 class="text-base font-bold text-gray-900 mb-4">Apa yang terjadi selanjutnya?</h2>

            @php
            $steps = [
                'Sistem akan otomatis memeriksa duplikasi laporan dalam radius 50 meter',
                'Laporan akan diteruskan ke OPD yang berwenang menangani masalah Anda',
                'Petugas lapangan akan ditugaskan untuk menindaklanjuti laporan',
                'Anda akan menerima update progress via WhatsApp',
                'Masalah akan diselesaikan maksimal dalam 10 hari kerja sesuai SLA',
            ];
            @endphp

            <ol class="flex flex-col gap-3.5">
                @foreach ($steps as $i => $step)
                <li class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-full bg-primary-500 text-white text-xs font-bold
                                 flex items-center justify-center shrink-0 mt-0.5">
                        {{ $i + 1 }}
                    </span>
                    <span class="text-sm text-gray-600 leading-relaxed">{{ $step }}</span>
                </li>
                @endforeach
            </ol>
        </div>

        {{-- ── Actions ── --}}
        <div class="flex flex-col gap-3 mb-6">
            <a href="{{ route('home') }}"
               class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-700
                      text-white py-3.5 rounded-xl font-semibold text-sm transition-all active:scale-[.98]">
                Kembali ke Beranda →
            </a>
            <a href="{{ route('reports.create') }}"
               class="w-full flex items-center justify-center gap-2 border border-gray-200 bg-white
                      hover:bg-gray-50 text-gray-700 py-3.5 rounded-xl font-semibold text-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M5 2h6a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M6 5.5h4M8 5.5V9" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Buat Laporan Lain
            </a>
        </div>

        {{-- ── Footer note ── --}}
        @guest
        <div class="text-center mb-3">
            <p class="text-sm text-gray-400">Ingin akses penuh dengan fitur pelacakan real-time?</p>
            <a href="{{ route('register') }}"
               class="text-sm font-semibold text-primary-500 hover:text-primary-700 hover:underline transition-colors">
                Daftar Akun Sekarang →
            </a>
        </div>
        @endguest

        <p class="text-center text-sm text-gray-400">
            Butuh bantuan? Hubungi kami di
            <a href="https://wa.me/6236130" target="_blank"
               class="font-medium text-success hover:underline">WhatsApp</a>
            atau
            <a href="mailto:silabu@badungkab.go.id"
               class="font-medium text-primary-500 hover:underline">Email</a>
        </p>

    </div>
</div>
@endsection