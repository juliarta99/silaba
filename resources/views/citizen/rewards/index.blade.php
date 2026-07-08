@extends('layouts.app')
@section('title', 'Klaim Reward — SILABA')

@section('content')

@php
$categoryIcon = [
    'Pulsa & Data'   => '<path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Voucher'        => '<path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'F&B'            => '<path d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Wisata'         => '<path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Merchandise'    => '<path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Penghargaan'    => '<path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Transportasi'   => '<path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Layanan Publik' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
    'Wellness'       => '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>',
];
$defaultIcon = '<path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>';
@endphp

{{-- ════ Modal Konfirmasi ════ --}}
<div x-data="{
        open: false,
        rewardName: '',
        rewardPts: 0,
        rewardType: '',
        rewardDesc: '',
        formAction: '',
        userPts: {{ $points }},
        openModal(name, pts, type, desc, action) {
            this.rewardName  = name;
            this.rewardPts   = pts;
            this.rewardType  = type;
            this.rewardDesc  = desc;
            this.formAction  = action;
            this.open = true;
        }
    }"
    @keydown.escape.window="open = false"
>

{{-- Overlay --}}
<div x-show="open"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="open = false"
     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50"
     style="display:none;">

    {{-- Modal --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

        {{-- Header --}}
        <div class="bg-primary-500 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24">
                        <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white">Konfirmasi Klaim Reward</p>
                    <p class="text-xs text-primary-100 mt-0.5">Pastikan pilihan Anda sudah benar</p>
                </div>
                <button type="button" @click="open = false"
                        class="ml-auto text-primary-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">

            {{-- Reward info --}}
            <div class="bg-gray-10 border border-gray-100 rounded-xl p-4 mb-4">
                <p class="text-xs text-gray-400 mb-0.5 font-medium">Reward yang akan diklaim</p>
                <p class="text-base font-bold text-gray-900" x-text="rewardName"></p>
                <p class="text-xs text-gray-500 mt-0.5" x-text="rewardType"></p>
                <p class="text-xs text-gray-500 mt-1.5 leading-relaxed" x-text="rewardDesc"></p>
            </div>

            {{-- Kalkulasi poin --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="text-center px-3 py-3 rounded-xl bg-gray-10 border border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Poin Saat Ini</p>
                    <p class="text-lg font-bold text-gray-900" x-text="userPts"></p>
                </div>
                <div class="text-center px-3 py-3 rounded-xl bg-red-50 border border-red-100">
                    <p class="text-xs text-gray-400 mb-1">Digunakan</p>
                    <p class="text-lg font-bold text-error" x-text="'-' + rewardPts"></p>
                </div>
                <div class="text-center px-3 py-3 rounded-xl bg-green-50 border border-green-100">
                    <p class="text-xs text-gray-400 mb-1">Sisa Poin</p>
                    <p class="text-lg font-bold text-success" x-text="userPts - rewardPts"></p>
                </div>
            </div>

            {{-- Info kode langsung --}}
            <div class="flex items-start gap-2.5 px-4 py-3 rounded-xl bg-blue-50 border border-blue-100 mb-4">
                <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-xs text-blue-700 leading-relaxed">
                    Setelah klaim berhasil, <span class="font-semibold">kode voucher unik</span> akan
                    langsung ditampilkan dan dapat digunakan sesuai ketentuan reward.
                </p>
            </div>

            {{-- Peringatan tidak bisa dibatalkan --}}
            <div class="flex items-start gap-2.5 px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-100 mb-5">
                <svg class="w-4 h-4 text-yellow-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-xs text-yellow-700 leading-relaxed">
                    Klaim reward <span class="font-semibold">tidak dapat dibatalkan</span> setelah dikonfirmasi.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="button" @click="open = false"
                        class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-700
                               text-sm font-semibold hover:bg-gray-10 transition-colors">
                    Batal
                </button>
                <form :action="formAction" method="POST" class="flex-1" x-ref="claimForm">
                    @csrf
                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-semibold transition-colors">
                        Ya, Klaim Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════ Hero Header ════ --}}
<div class="bg-primary-500 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-24 pb-10">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">Klaim Reward</h1>
                <p class="text-sm text-primary-100">Tukarkan poin reward Anda dengan berbagai hadiah menarik</p>
            </div>
            <div class="shrink-0 bg-white/15 border border-white/30 rounded-2xl px-5 py-4 text-center min-w-[120px]">
                <p class="text-xs text-primary-200 mb-1">Poin Anda</p>
                <p class="text-3xl font-bold tabular-nums">{{ $points }}</p>
                <p class="text-xs text-primary-200 mt-0.5">Poin</p>
            </div>
        </div>
    </div>
</div>

{{-- ════ Konten ════ --}}
<div class="bg-gray-10 min-h-screen pb-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-8">

        @if (session('error'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-sm text-error mb-6">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Info banner --}}
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100 mb-8">
            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-blue-700 leading-relaxed">
                <span class="font-semibold">Info:</span>
                Poin reward didapatkan setelah laporan Anda diselesaikan dan diverifikasi (+25 poin per laporan).
                Klaim langsung mendapat kode voucher unik yang dapat digunakan sesuai ketentuan masing-masing reward.
            </p>
        </div>

        {{-- Katalog --}}
        <h2 class="text-lg font-bold text-gray-900 mb-5">Katalog Reward</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
            @foreach ($rewards as $reward)
            @php
            $stock      = $reward->stock; // dari withCount
            $canClaim   = $points >= $reward->points_required && $stock > 0;
            $stockHabis = $stock <= 0;
            $kurang     = $reward->points_required - $points;
            $iconPath   = $categoryIcon[$reward->type] ?? $defaultIcon;
            $storeRoute = route('citizen.reward-claims.store', $reward);
            @endphp

            <div class="bg-white rounded-2xl border {{ $stockHabis ? 'border-gray-100 opacity-65' : 'border-gray-100' }}
                        shadow-sm overflow-hidden flex flex-col transition-all hover:shadow-md">

                {{-- Icon --}}
                <div class="flex flex-col items-center pt-7 pb-4 px-4">
                    <div class="w-14 h-14 rounded-full {{ $stockHabis ? 'bg-gray-100' : 'bg-red-50' }}
                                flex items-center justify-center mb-2.5">
                        <svg class="w-7 h-7 {{ $stockHabis ? 'text-gray-300' : 'text-primary-500' }}"
                             fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            {!! $iconPath !!}
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-400 px-2.5 py-0.5 rounded-full bg-gray-10 border border-gray-100">
                        {{ $reward->type }}
                    </span>
                </div>

                {{-- Info --}}
                <div class="px-5 pb-3 flex-1">
                    <h3 class="text-sm font-bold text-gray-900 mb-1.5 leading-snug">{{ $reward->name }}</h3>
                    <p class="text-xs text-gray-400 leading-relaxed mb-4 line-clamp-2">{{ $reward->description }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-bold text-gray-900">{{ number_format($reward->points_required) }}</span>
                            <span class="text-xs text-gray-400">Poin</span>
                        </div>
                        @if ($stockHabis)
                        <span class="text-xs font-semibold text-error">Habis</span>
                        @else
                        <span class="text-xs font-medium text-success">Stok: {{ $stock }}</span>
                        @endif
                    </div>
                </div>

                {{-- CTA --}}
                <div class="px-5 pb-5">
                    @if ($stockHabis)
                    <button disabled
                            class="w-full py-2.5 rounded-xl bg-gray-100 text-gray-400 text-sm font-semibold cursor-not-allowed">
                        Tidak Tersedia
                    </button>
                    @elseif ($canClaim)
                    <button type="button"
                            @click="openModal(
                                '{{ addslashes($reward->name) }}',
                                {{ $reward->points_required }},
                                '{{ addslashes($reward->type) }}',
                                '{{ addslashes(Str::limit($reward->description ?? '', 100)) }}',
                                '{{ $storeRoute }}'
                            )"
                            class="w-full py-2.5 rounded-xl bg-primary-500 hover:bg-primary-700
                                   text-white text-sm font-semibold transition-colors active:scale-[.98]">
                        Klaim Sekarang
                    </button>
                    @else
                    <button disabled
                            class="w-full py-2.5 rounded-xl bg-gray-100 text-gray-500 text-sm font-medium cursor-not-allowed">
                        Butuh {{ number_format($kurang) }} Poin Lagi
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Link riwayat --}}
        <div class="text-center">
            <a href="{{ route('citizen.reward-claims.history') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600
                      hover:text-primary-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.75"/>
                    <path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Lihat Riwayat Reward Saya
            </a>
        </div>

    </div>
</div>

</div>{{-- end x-data modal --}}
@endsection