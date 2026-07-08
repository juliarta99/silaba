@extends('layouts.app')
@section('title', 'Tiket Reward — SILABA')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-lg mx-auto px-4 sm:px-6 pt-6">

        {{-- Back --}}
        <a href="{{ route('citizen.reward-claims.history') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Riwayat
        </a>

        @php
        $voucher = $rewardClaim->voucher;
        $isExpired = $voucher->valid_until && \Carbon\Carbon::parse($voucher->valid_until)->isPast();
        @endphp

        {{-- ════ TIKET ════ --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-5 border border-gray-100" id="ticket">

            {{-- Header tiket --}}
            <div class="bg-primary-500 px-6 py-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-primary-200 mb-0.5 font-medium tracking-wider uppercase">Nomor Tiket</p>
                        <p class="text-lg font-bold font-mono tracking-widest text-white">
                            RWD-{{ str_pad($rewardClaim->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-primary-200 mb-0.5">Diklaim</p>
                        <p class="text-xs font-medium text-white">
                            {{ $rewardClaim->created_at->translatedFormat('j M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Nama reward + type --}}
            <div class="px-6 py-4 flex items-center gap-3 border-b border-dashed border-gray-200">
                <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 8v13m0-13V6a4 4 0 00-4-4H5.45a1 1 0 00-.82 1.57L7 7h5zm0 0V5.5A2.5 2.5 0 0114.5 3H17a1 1 0 01.82 1.57L15 8h-3z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 8h14a1 1 0 011 1v3a8 8 0 01-16 0V9a1 1 0 011-1z" stroke="currentColor" stroke-width="1.75"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base font-bold text-gray-900 truncate">{{ $rewardClaim->reward->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $rewardClaim->reward->type }}</p>
                </div>
                @if ($isExpired)
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-error shrink-0">
                    Kadaluarsa
                </span>
                @else
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-success shrink-0">
                    Aktif
                </span>
                @endif
            </div>

            {{-- QR Code + Kode --}}
            <div class="px-6 py-7 text-center">

                {{-- QR --}}
                <div class="flex justify-center mb-4">
                    <div class="p-4 border-2 border-gray-100 rounded-2xl bg-white inline-block shadow-sm">
                        <div id="qrcode"></div>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-5">Scan QR code ini kepada petugas</p>

                {{-- Kode voucher --}}
                <div class="bg-red-50 border-2 border-dashed border-primary-300 rounded-2xl px-6 py-5 mb-4"
                     x-data="{ copied: false }">
                    <p class="text-xs font-medium text-gray-400 tracking-wider uppercase mb-2">Kode Voucher</p>
                    <p class="text-2xl sm:text-3xl font-bold font-mono tracking-widest text-primary-500 select-all break-all">
                        {{ $voucher->code }}
                    </p>
                    <button
                        type="button"
                        @click="
                            navigator.clipboard.writeText('{{ $voucher->code }}');
                            copied = true;
                            setTimeout(() => copied = false, 2500)
                        "
                        class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold transition-all"
                        :class="copied
                            ? 'bg-green-100 text-success'
                            : 'bg-white border border-gray-200 text-gray-600 hover:border-primary-300 hover:text-primary-500'"
                    >
                        <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <rect x="5.5" y="5.5" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M3.5 10.5H2a1 1 0 01-1-1V2a1 1 0 011-1h7.5a1 1 0 011 1v1.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <svg x-show="copied" class="w-3.5 h-3.5" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span x-text="copied ? 'Berhasil Disalin!' : 'Salin Kode'"></span>
                    </button>
                </div>

                {{-- Masa berlaku --}}
                <div class="grid grid-cols-2 gap-3 text-left">
                    @if ($voucher->valid_from)
                    <div class="px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-0.5">Berlaku Mulai</p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($voucher->valid_from)->translatedFormat('j M Y') }}
                        </p>
                    </div>
                    @endif
                    @if ($voucher->valid_until)
                    <div class="px-4 py-3 rounded-xl {{ $isExpired ? 'bg-red-50 border-red-100' : 'bg-gray-10 border-gray-100' }} border">
                        <p class="text-xs text-gray-400 mb-0.5">Berlaku Hingga</p>
                        <p class="text-sm font-semibold {{ $isExpired ? 'text-error' : 'text-gray-900' }}">
                            {{ \Carbon\Carbon::parse($voucher->valid_until)->translatedFormat('j M Y') }}
                            @if ($isExpired) <span class="text-xs">(Kadaluarsa)</span> @endif
                        </p>
                    </div>
                    @elseif ($voucher->valid_from)
                    <div class="px-4 py-3 rounded-xl bg-gray-10 border border-gray-100">
                        <p class="text-xs text-gray-400 mb-0.5">Berlaku Hingga</p>
                        <p class="text-sm font-semibold text-gray-900">Tidak Kadaluarsa</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Poin info --}}
            <div class="mx-6 mb-6 flex items-center justify-between px-4 py-3 rounded-xl bg-red-50 border border-red-100">
                <span class="text-sm text-gray-600">Poin yang digunakan</span>
                <span class="text-sm font-bold text-error">-{{ $rewardClaim->points_used }} Poin</span>
            </div>

        </div>

        {{-- Cara penggunaan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Cara Menggunakan</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ $rewardClaim->reward->description ?? 'Tunjukkan kode atau QR kepada petugas terkait.' }}
            </p>
            @if ($rewardClaim->notes)
            <div class="mt-3 px-3.5 py-3 rounded-xl bg-blue-50 border border-blue-100">
                <p class="text-xs font-semibold text-blue-700 mb-0.5">Catatan Tambahan</p>
                <p class="text-xs text-blue-600 leading-relaxed">{{ $rewardClaim->notes }}</p>
            </div>
            @endif
        </div>

        {{-- S&K --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Syarat & Ketentuan</h3>
            <ul class="space-y-2">
                @foreach ([
                    'Kode hanya dapat digunakan satu kali',
                    'Tidak dapat ditukarkan dengan uang tunai',
                    'Tidak dapat digabungkan dengan promo lain',
                    'Tunjukkan kode atau QR sebelum bertransaksi',
                    'SILABA tidak bertanggung jawab atas kode yang hilang atau disalahgunakan',
                ] as $item)
                <li class="flex items-start gap-2 text-xs text-gray-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-400 shrink-0 mt-1.5"></span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Bantuan --}}
        <div class="flex items-start gap-3 px-4 py-4 rounded-2xl bg-blue-50 border border-blue-100 mb-6">
            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-xs text-blue-700 leading-relaxed">
                <span class="font-semibold">Butuh bantuan?</span>
                Hubungi <a href="mailto:support@silaba.badung.go.id" class="underline font-medium">support@silaba.badung.go.id</a>
                atau WhatsApp 0811-3888-9999 dengan menyebutkan nomor tiket
                <span class="font-bold">RWD-{{ str_pad($rewardClaim->id, 4, '0', STR_PAD_LEFT) }}</span>
            </p>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('citizen.reward-claims.history') }}"
               class="flex items-center justify-center py-3 rounded-xl border border-gray-200
                      text-gray-700 text-sm font-semibold hover:bg-gray-10 transition-colors">
                Riwayat Reward
            </a>
            <a href="{{ route('citizen.reward-claims.index') }}"
               class="flex items-center justify-center py-3 rounded-xl bg-primary-500 hover:bg-primary-700
                      text-white text-sm font-semibold transition-colors">
                Klaim Lagi
            </a>
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var qrElement = document.getElementById('qrcode');
            if (qrElement && typeof QRCode !== 'undefined') {
                new QRCode(qrElement, {
                    text: '{{ $voucher->code }}',
                    width: 180,
                    height: 180,
                    colorDark: '#C01818',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H,
                });
            } else {
                console.error("QRCode library gagal dimuat atau elemen tidak ditemukan.");
            }
        });
    </script>
@endsection