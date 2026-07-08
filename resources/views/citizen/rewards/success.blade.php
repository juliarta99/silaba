@extends('layouts.app')
@section('title', 'Reward Berhasil Diklaim — SILABA')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6 pt-12">

        {{-- Icon + Heading --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-success flex items-center justify-center mx-auto mb-5 shadow-lg shadow-success/30">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1.5">Reward Berhasil Diklaim!</h1>
            <p class="text-sm text-gray-500">Kode Anda sudah siap digunakan sekarang</p>
        </div>

        @php $voucher = $claim->voucher; @endphp

        {{-- Tiket --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">

            {{-- Header tiket --}}
            <div class="bg-primary-500 px-6 py-4 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-primary-200 font-medium tracking-wider uppercase">Nomor Tiket</p>
                        <p class="text-lg font-bold font-mono tracking-wider">
                            RWD-{{ str_pad($claim->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white/20">
                        {{ $claim->created_at->translatedFormat('j M Y') }}
                    </span>
                </div>
            </div>

            {{-- Reward name --}}
            <div class="px-6 py-4 border-b border-dashed border-gray-200">
                <p class="text-base font-bold text-gray-900">{{ $claim->reward->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $claim->reward->type }} • {{ $claim->points_used }} poin digunakan</p>
            </div>

            {{-- QR + Kode --}}
            <div class="px-6 py-6 text-center">
                <div class="flex justify-center mb-3">
                    <div class="p-3 border-2 border-gray-100 rounded-xl bg-white">
                        <div id="qrcode-success"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-5">Scan QR code atau gunakan kode di bawah</p>

                {{-- Kode --}}
                <div class="bg-red-50 border-2 border-dashed border-primary-300 rounded-xl px-6 py-4 mb-3"
                     x-data="{ copied: false }">
                    <p class="text-xs text-gray-400 tracking-wider uppercase mb-1.5">Kode Voucher</p>
                    <p class="text-2xl font-bold font-mono tracking-widest text-primary-500 select-all break-all">
                        {{ $voucher->code }}
                    </p>
                    <button type="button"
                            @click="navigator.clipboard.writeText('{{ $voucher->code }}'); copied=true; setTimeout(()=>copied=false,2500)"
                            class="mt-3 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold transition-all"
                            :class="copied ? 'bg-green-100 text-success' : 'bg-white border border-gray-200 text-gray-600 hover:text-primary-500'">
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                            <rect x="5.5" y="5.5" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M3.5 10.5H2a1 1 0 01-1-1V2a1 1 0 011-1h7.5a1 1 0 011 1v1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <svg x-show="copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16">
                            <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-text="copied ? 'Tersalin!' : 'Salin Kode'"></span>
                    </button>
                </div>

                {{-- Masa berlaku --}}
                @if ($voucher->valid_until)
                <div class="text-xs text-gray-400">
                    Berlaku hingga:
                    <span class="font-semibold text-gray-700">
                        {{ \Carbon\Carbon::parse($voucher->valid_until)->translatedFormat('j M Y') }}
                    </span>
                </div>
                @else
                <p class="text-xs text-gray-400">Tidak ada batas waktu</p>
                @endif
            </div>

            {{-- Sisa poin --}}
            <div class="mx-5 mb-5 flex items-center gap-3 px-4 py-3 rounded-xl bg-yellow-50 border border-yellow-100">
                <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="none" viewBox="0 0 24 24">
                    <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
                </svg>
                <p class="text-sm text-yellow-700">Sisa poin Anda: <span class="font-bold">{{ $sisaPoin }} poin</span></p>
            </div>
        </div>

        {{-- Cara penggunaan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="text-sm font-bold text-gray-900 mb-2.5">Cara Menggunakan</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $claim->reward->description }}</p>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-3 gap-3 mb-5">
            <a href="{{ route('citizen.reward-claims.index') }}"
               class="flex items-center justify-center py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                      text-white text-sm font-semibold transition-colors text-center">
                Klaim Lagi
            </a>
            <a href="{{ route('citizen.reward-claims.show', $claim) }}"
               class="flex items-center justify-center py-3 rounded-xl border border-gray-200
                      text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors text-center">
                Lihat Tiket
            </a>
            <a href="{{ route('citizen.dashboard') }}"
               class="flex items-center justify-center py-3 rounded-xl bg-gray-800 hover:bg-gray-900
                      text-white text-sm font-semibold transition-colors text-center">
                Dashboard
            </a>
        </div>

        <p class="text-center text-xs text-gray-400 leading-relaxed">
            Simpan kode Anda dengan baik. Kode hanya dapat digunakan satu kali.
        </p>

    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        new QRCode(document.getElementById('qrcode-success'), {
            text: '{{ $voucher->code }}',
            width: 160, height: 160,
            colorDark: '#C01818', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H,
        });
    });
    </script>
@endsection