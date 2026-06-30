@extends('layouts.app')

@section('title', 'Verifikasi Berhasil')

@section('content')
<div
    x-data="{
        countdown: 3,
        init() {
            const interval = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = '{{ route('login') }}';
                }
            }, 1000);
        }
    }"
    class="min-h-[calc(100vh-68px)] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50"
>
    <div class="text-center mb-8">
        <div class="w-20 h-20 rounded-full bg-success/15 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M8 12.5l2.5 2.5L16 9" stroke="currentColor" stroke-width="2.25"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Verifikasi Berhasil!</h2>
        <p class="text-sm text-gray-500 mb-1">Akun Anda telah berhasil diverifikasi</p>
        <p class="text-sm text-gray-400">Selamat datang di SILABU</p>
    </div>

    <div class="bg-white p-6 sm:p-7 rounded-2xl shadow-sm border border-gray-100 w-full max-w-md mb-5">
        <div class="space-y-3">
            @php
            $highlights = [
                ['title' => 'Akun Terverifikasi', 'desc' => 'NIK Anda telah diverifikasi dengan sistem Disdukcapil'],
                ['title' => 'WhatsApp Terhubung', 'desc' => 'Nomor WhatsApp Anda siap menerima notifikasi real-time'],
                ['title' => 'Akses Penuh Tersedia', 'desc' => 'Anda dapat membuat laporan, melacak status, dan klaim reward'],
            ];
            @endphp
            @foreach ($highlights as $h)
            <div class="flex items-start gap-3 p-4 rounded-xl bg-green-50">
                <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $h['title'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $h['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="w-full max-w-md flex flex-col gap-3 mb-5">
        <a href="{{ route('login') }}"
           class="w-full flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-700
                  text-white py-3 rounded-lg font-semibold text-sm transition-all duration-150 active:scale-[.98] shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Masuk ke Dashboard
        </a>
        <a href="{{ route('reports.create') }}"
           class="w-full flex items-center justify-center gap-2 bg-secondary-500 hover:bg-secondary-700
                  text-gray-900 py-3 rounded-lg font-semibold text-sm transition-all duration-150 active:scale-[.98] shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Buat Laporan Pertama
        </a>
    </div>

    <div class="w-full max-w-md px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100 mb-4">
        <p class="text-sm text-blue-700 text-center leading-relaxed">
            <span class="font-semibold">Langkah selanjutnya:</span>
            Login dengan NIK atau nomor HP dan password yang telah Anda buat untuk mulai menggunakan SILABU.
        </p>
    </div>

    <p class="text-sm text-gray-400">
        Otomatis menuju halaman login dalam <span x-text="countdown"></span> detik...
    </p>
</div>
@endsection