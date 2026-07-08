@extends('layouts.app')
@section('title', 'Riwayat Reward — SILABA')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-6">

        {{-- Back + Heading --}}
        <div class="mb-7">
            <a href="{{ route('citizen.reward-claims.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16">
                    <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Riwayat Reward</h1>
            <p class="text-sm text-gray-500 mt-1">Semua reward yang pernah Anda klaim</p>
        </div>

        {{-- Saldo poin --}}
        <div class="bg-primary-500 rounded-2xl p-5 text-white mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-primary-200">Saldo Poin Saat Ini</p>
                    <p class="text-2xl font-bold tabular-nums">{{ $points }} Poin</p>
                </div>
                <a href="{{ route('citizen.reward-claims.index') }}"
                   class="ml-auto px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-semibold transition-colors">
                    Klaim Reward →
                </a>
            </div>
        </div>

        {{-- List klaim --}}
        @if ($claims->count() > 0)
        <div class="flex flex-col gap-4 mb-6">
            @foreach ($claims as $claim)
            @php
            $voucher   = $claim->voucher;
            $isExpired = $voucher?->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-start gap-4 p-5">

                    {{-- Icon --}}
                    <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24">
                            <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
                        </svg>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1.5">
                            <div>
                                <p class="text-xs font-mono text-gray-400 mb-0.5">
                                    RWD-{{ str_pad($claim->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                                <h3 class="text-sm font-bold text-gray-900">{{ $claim->reward->name }}</h3>
                            </div>
                            @if ($isExpired)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-500 shrink-0">
                                Kadaluarsa
                            </span>
                            @else
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-success shrink-0">
                                Aktif
                            </span>
                            @endif
                        </div>

                        {{-- Kode voucher --}}
                        @if ($voucher?->code)
                        <div class="flex items-center gap-2 mb-2" x-data="{ copied: false }">
                            <code class="text-sm font-bold font-mono text-primary-500 tracking-wider
                                         bg-red-50 px-2.5 py-1 rounded-lg">
                                {{ $voucher->code }}
                            </code>
                            <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $voucher->code }}'); copied=true; setTimeout(()=>copied=false,2000)"
                                    class="text-xs transition-colors"
                                    :class="copied ? 'text-success' : 'text-gray-400 hover:text-primary-500'">
                                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                                    <rect x="5.5" y="5.5" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                                    <path d="M3.5 10.5H2a1 1 0 01-1-1V2a1 1 0 011-1h7.5a1 1 0 011 1v1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                                </svg>
                                <svg x-show="copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        @endif

                        {{-- Meta --}}
                        <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                            <span>Diklaim: {{ $claim->created_at->translatedFormat('j M Y, H:i') }}</span>
                            @if ($voucher?->valid_until)
                            <span>Berlaku: {{ \Carbon\Carbon::parse($voucher->valid_until)->translatedFormat('j M Y') }}</span>
                            @endif
                            <span class="font-medium text-error">-{{ $claim->points_used }} poin</span>
                        </div>
                    </div>

                    {{-- Action --}}
                    <a href="{{ route('citizen.reward-claims.show', $claim) }}"
                       class="shrink-0 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold
                              text-gray-600 hover:border-primary-300 hover:text-primary-500 transition-colors whitespace-nowrap">
                        Lihat Tiket
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if ($claims->hasPages())
        <div class="mb-6">{{ $claims->links() }}</div>
        @endif

        @else
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm mb-6">
            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
            </svg>
            <p class="text-sm text-gray-400 mb-4">Anda belum pernah klaim reward.</p>
            <a href="{{ route('citizen.reward-claims.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-500
                      hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                Klaim Reward Sekarang
            </a>
        </div>
        @endif

        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100">
            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-blue-700 leading-relaxed">
                <span class="font-semibold">Info:</span>
                Simpan kode voucher Anda dengan baik. Setiap kode hanya dapat digunakan satu kali dan tidak dapat dikembalikan.
            </p>
        </div>

    </div>
</div>
@endsection