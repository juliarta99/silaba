@extends('layouts.app')

@section('title', 'Rating Berhasil Dikirim — SILABU')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6 pt-12">

        {{-- ── Icon + Heading ── --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-success/15 flex items-center
                        justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke="currentColor" stroke-width="2.25"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                Rating Berhasil Dikirim!
            </h1>
            <p class="text-sm text-gray-500">Terima kasih atas penilaian Anda</p>
        </div>

        {{-- ── Card: Ringkasan Rating ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">

            <p class="text-xs text-gray-400 text-center mb-1">Rating Anda untuk</p>
            <p class="text-lg font-bold text-primary-500 text-center mb-4">{{ $report->code }}</p>

            {{-- Bintang hasil --}}
            <div class="flex items-center justify-center gap-1.5 mb-2">
                @for ($i = 1; $i <= 5; $i++)
                <svg class="w-9 h-9 {{ $i <= $stars ? 'text-yellow-400' : 'text-gray-200' }}"
                     fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                @endfor
            </div>
            <p class="text-base font-bold text-gray-900 text-center mb-5">
                {{ $stars }} dari 5 Bintang
            </p>

            {{-- 3 highlight --}}
            <div class="space-y-3">

                {{-- Rating tersimpan --}}
                <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-green-50 border border-green-100">
                    <svg class="w-5 h-5 text-success shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-success">Rating Tersimpan</p>
                        <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                            Penilaian Anda akan membantu kami meningkatkan kualitas layanan SILABU
                        </p>
                    </div>
                </div>

                {{-- Poin ditambahkan --}}
                <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-yellow-50 border border-yellow-100">
                    <svg class="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                              stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-600">
                            Poin Reward Ditambahkan: +{{ $points }} Poin
                        </p>
                        <p class="text-xs text-yellow-600/80 mt-0.5 leading-relaxed">
                            Poin reward telah ditambahkan ke akun Anda dan dapat ditukar
                            dengan berbagai hadiah menarik
                        </p>
                    </div>
                </div>

                {{-- Laporan ditutup --}}
                <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-600">Laporan Ditutup</p>
                        <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                            Laporan ini telah ditutup dan statusnya akan diperbarui di dashboard Anda
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Banner kumpulkan lebih banyak poin ── --}}
        <div class="flex items-start gap-3.5 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 mb-6">
            <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center
                        justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                          stroke="currentColor" stroke-width="1.75"
                          stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                          stroke="currentColor" stroke-width="1.75"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">Kumpulkan Lebih Banyak Poin!</p>
                <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">
                    Buat laporan baru dan dapatkan lebih banyak poin reward setiap kali
                    laporan Anda terverifikasi selesai.
                </p>
            </div>
        </div>

        {{-- ── Action buttons ── --}}
        <div class="grid grid-cols-3 gap-3 mb-6">
            <a href="{{ route('citizen.dashboard') }}"
               class="flex items-center justify-center py-3 rounded-xl bg-primary-500
                      hover:bg-primary-700 text-white text-sm font-semibold transition-colors
                      text-center">
                Ke Dashboard
            </a>
            <a href="{{ route('citizen.reward-claims.index') }}"
               class="flex items-center justify-center gap-1.5 py-3 rounded-xl bg-yellow-400
                      hover:bg-yellow-500 text-gray-900 text-sm font-semibold transition-colors
                      text-center">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z"
                          stroke="currentColor" stroke-width="1.75"
                          stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z"
                          stroke="currentColor" stroke-width="1.75"/>
                </svg>
                Klaim Reward
            </a>
            <a href="{{ route('reports.create') }}"
               class="flex items-center justify-center py-3 rounded-xl border border-gray-200
                      text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors
                      text-center">
                Buat Laporan Baru
            </a>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-sm text-gray-400 leading-relaxed">
            Terima kasih telah menggunakan SILABU untuk melaporkan masalah di Kabupaten Badung.
            Partisipasi Anda sangat berarti bagi kami!
        </p>

    </div>
</div>
@endsection