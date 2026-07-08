@extends('layouts.app')

@section('title', 'Beri Rating — SILABA')

@section('content')

<div
    x-data="{
        rating: 0,
        hovered: 0,
        labels: ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'],
        submit() {
            if (this.rating === 0) {
                alert('Pilih rating bintang terlebih dahulu.');
                return;
            }
            this.$refs.form.submit();
        }
    }"
    class="bg-gray-10 min-h-[calc(100vh-68px)] py-16"
>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 pt-6">

        {{-- ── Back + Heading ── --}}
        <div class="mb-7">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500
                      hover:text-gray-800 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Beri Rating & Ulasan</h1>
            <p class="text-sm text-gray-500 mt-1.5">Bantu kami meningkatkan kualitas layanan</p>
        </div>

        {{-- ── Info Laporan ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
            <div class="flex items-center gap-3.5 mb-5 pb-5 border-b border-gray-50">
                <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-base font-bold text-gray-900">Laporan Telah Diselesaikan</p>
                    <p class="text-sm text-gray-500">Terima kasih telah menggunakan layanan SILABA</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Nomor Tiket</p>
                    <p class="text-sm font-bold text-gray-900">{{ $report->code }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Kategori</p>
                    <p class="text-sm font-bold text-gray-900">{{ $report->category?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Ditangani Oleh</p>
                    <p class="text-sm font-bold text-gray-900">{{ $department }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-0.5">Petugas</p>
                    <p class="text-sm font-bold text-gray-900">{{ $petugas }}</p>
                </div>
            </div>
        </div>

        {{-- ── Form Rating ── --}}
        <form x-ref="form"
              action="{{ route('citizen.reports.rate.store', $report->code) }}"
              method="POST">
            @csrf

            {{-- Hidden input — diisi dari Alpine --}}
            <input type="hidden" name="rating" :value="rating">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">

                {{-- Bintang interaktif --}}
                <div class="text-center mb-6">
                    <p class="text-lg font-bold text-gray-900 mb-1">Bagaimana pengalaman Anda?</p>
                    <p class="text-sm text-gray-500 mb-5">Pilih bintang untuk memberikan penilaian</p>

                    <div class="flex items-center justify-center gap-2 mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                        <button
                            type="button"
                            @mouseenter="hovered = {{ $i }}"
                            @mouseleave="hovered = 0"
                            @click="rating = {{ $i }}"
                            aria-label="{{ $i }} bintang"
                            class="focus:outline-none transition-transform active:scale-90"
                        >
                            <svg
                                class="w-10 h-10 transition-colors duration-100"
                                :class="(hovered > 0 ? hovered >= {{ $i }} : rating >= {{ $i }})
                                    ? 'text-yellow-400'
                                    : 'text-gray-200'"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </button>
                        @endfor
                    </div>

                    {{-- Label dinamis --}}
                    <p class="text-sm font-semibold min-h-5"
                       :class="rating > 0 ? 'text-yellow-500' : 'text-gray-400'"
                       x-text="hovered > 0 ? labels[hovered] : (rating > 0 ? labels[rating] : 'Pilih Rating')">
                        Pilih Rating
                    </p>
                </div>

                {{-- Komentar --}}
                <div class="border-t border-gray-50 pt-5">
                    <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">
                        Komentar atau Saran
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea
                        id="comment" name="comment" rows="4"
                        placeholder="Ceritakan pengalaman Anda menggunakan layanan SILABA..."
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-10
                               text-gray-900 placeholder-gray-400 resize-none focus:bg-white
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500
                               outline-none transition-all text-sm"
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1.5">
                        Masukan Anda sangat berharga untuk meningkatkan kualitas layanan kami
                    </p>
                </div>
            </div>

            {{-- Banner poin reward --}}
            <div class="flex items-start gap-3.5 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 mb-6">
                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center
                            justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Dapatkan 25 Poin Reward!</p>
                    <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">
                        Setelah memberikan rating, Anda akan mendapatkan 25 poin reward yang
                        dapat ditukar dengan berbagai hadiah menarik.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <a href="{{ route('reports.show', $report->code) }}"
                   class="px-6 py-3 rounded-xl border border-gray-200 text-gray-700 text-sm
                          font-semibold hover:bg-gray-10 transition-colors">
                    Batal
                </a>
                <button
                    type="button"
                    @click="submit()"
                    class="flex-1 flex items-center justify-center gap-2 bg-primary-500
                           hover:bg-primary-700 text-white py-3 rounded-xl font-semibold
                           text-sm transition-all active:scale-[.98] shadow-sm"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                              stroke="currentColor" stroke-width="1.75"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kirim Rating
                </button>
            </div>

        </form>
    </div>
</div>

@endsection