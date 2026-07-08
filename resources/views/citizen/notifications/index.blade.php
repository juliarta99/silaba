@extends('layouts.app')
@section('title', 'Notifikasi — SILABA')

@section('content')
<div class="bg-gray-10 min-h-[calc(100vh-68px)] py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 pt-6">

        <a href="{{ route('citizen.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Dashboard
        </a>
        
        {{-- ── Header ── --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $totalCount }} notifikasi dari laporan Anda
                </p>
            </div>
            {{-- Badge total --}}
            @if ($totalCount > 0)
            <div class="w-10 h-10 rounded-full bg-primary-50 border border-primary-100
                        flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            @endif
        </div>

        @if ($notifications->count() > 0)

        {{-- ── List Notifikasi ── --}}
        <div class="flex flex-col gap-3 mb-6">
            @foreach ($notifications as $notif)
            @php
            $report  = $notif->report;
            $msg     = $notif->message;

            // Deteksi tipe notifikasi dari isi pesan
            $isSuccess  = str_contains($msg, '✅') || str_contains($msg, 'Diterima');
            $isUpdate   = str_contains($msg, '🔔') || str_contains($msg, 'Update') || str_contains($msg, 'Diperbarui');
            $isDone     = str_contains($msg, '🎉') || str_contains($msg, 'diselesaikan') || str_contains($msg, 'selesai');
            $isWarning  = str_contains($msg, '⚠️') || str_contains($msg, 'Menunggu');

            if ($isDone) {
                $iconBg   = 'bg-green-50';
                $iconClr  = 'text-success';
                $icon     = '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>';
                $dotClr   = 'bg-success';
            } elseif ($isSuccess) {
                $iconBg   = 'bg-blue-50';
                $iconClr  = 'text-blue-500';
                $icon     = '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>';
                $dotClr   = 'bg-blue-500';
            } elseif ($isUpdate) {
                $iconBg   = 'bg-yellow-50';
                $iconClr  = 'text-yellow-500';
                $icon     = '<path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>';
                $dotClr   = 'bg-yellow-400';
            } else {
                $iconBg   = 'bg-gray-10';
                $iconClr  = 'text-gray-400';
                $icon     = '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>';
                $dotClr   = 'bg-gray-300';
            }

            // Status badge laporan
            $statusMap = [
                'pending'      => ['label' => 'Menunggu',    'class' => 'bg-gray-100 text-gray-600'],
                'in_progress'  => ['label' => 'Diproses',    'class' => 'bg-blue-100 text-blue-700'],
                'under_review' => ['label' => 'Ditinjau',    'class' => 'bg-yellow-100 text-yellow-700'],
                'completed'    => ['label' => 'Selesai',     'class' => 'bg-green-100 text-success'],
                'rejected'     => ['label' => 'Ditolak',     'class' => 'bg-red-100 text-error'],
                'waiting_for_materials' => ['label' => 'Menunggu Material', 'class' => 'bg-orange-100 text-orange-700'],
            ];
            $statusInfo = $statusMap[$report?->status ?? ''] ?? ['label' => '—', 'class' => 'bg-gray-100 text-gray-500'];

            // Parse pesan — tampilkan bersih tanpa emoji duplikat
            $cleanMsg = trim(preg_replace('/^[^\w\s]+\s*\*[^*]+\*\s*—\s*SILABA\s*/u', '', $msg));
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                        hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4 p-5">

                    {{-- Icon --}}
                    <div class="w-11 h-11 rounded-full {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5 relative">
                        <svg class="w-5 h-5 {{ $iconClr }}" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            {!! $icon !!}
                        </svg>
                        {{-- Dot indikator --}}
                        <span class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full {{ $dotClr }} border-2 border-white"></span>
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 min-w-0">

                        {{-- Header: kode tiket + status + waktu --}}
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            @if ($report)
                            <a href="{{ route('reports.show', $report->code) }}"
                               class="text-xs font-bold font-mono text-primary-500 hover:underline">
                                {{ $report->code }}
                            </a>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusInfo['class'] }}">
                                {{ $statusInfo['label'] }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-400 ml-auto whitespace-nowrap">
                                {{ $notif->sent_at
                                    ? \Carbon\Carbon::parse($notif->sent_at)->diffForHumans()
                                    : $notif->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Judul laporan --}}
                        @if ($report?->title)
                        <p class="text-sm font-semibold text-gray-900 mb-1.5 line-clamp-1">
                            {{ $report->title }}
                        </p>
                        @endif

                        {{-- Pesan notifikasi (WhatsApp style) --}}
                        <div class="text-sm text-gray-600 leading-relaxed">
                            @php
                            // Tampilkan pesan dengan format WhatsApp sederhana
                            // Bold: *teks* → <strong>
                            $formatted = e($msg);
                            $formatted = preg_replace('/\*([^*]+)\*/', '<strong>$1</strong>', $formatted);
                            // Hapus emoji header
                            $lines = explode("\n", $formatted);
                            // Skip baris pertama jika hanya berisi emoji + judul
                            if (count($lines) > 1) {
                                array_shift($lines); // hapus baris "✅ *Laporan Diterima — SILABA*"
                                if (trim($lines[0] ?? '') === '') array_shift($lines); // hapus baris kosong
                            }
                            $displayed = implode("\n", array_slice($lines, 0, 6));
                            @endphp
                            <div class="whitespace-pre-line line-clamp-4">{!! $displayed !!}</div>
                        </div>

                        {{-- Footer: timestamp lengkap + link --}}
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <span class="text-xs text-gray-400">
                                <svg class="w-3 h-3 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 12 12" aria-hidden="true">
                                    <circle cx="6" cy="6" r="5" stroke="currentColor" stroke-width="1.1"/>
                                    <path d="M6 3.5V6l1.5 1.5" stroke="currentColor" stroke-width="1.1" stroke-linecap="round"/>
                                </svg>
                                {{ $notif->sent_at
                                    ? \Carbon\Carbon::parse($notif->sent_at)->translatedFormat('j M Y, H:i')
                                    : $notif->created_at->translatedFormat('j M Y, H:i') }}
                                via WhatsApp
                            </span>

                            @if ($report)
                            <a href="{{ route('reports.show', $report->code) }}"
                               class="text-xs font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                                Lihat Laporan →
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
        <div class="mb-6">
            {{ $notifications->links() }}
        </div>
        @endif

        @else
        {{-- ── Empty State ── --}}
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                          stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1.5">Belum ada notifikasi</h3>
            <p class="text-sm text-gray-400 mb-6 max-w-xs mx-auto leading-relaxed">
                Notifikasi WhatsApp akan muncul di sini setelah laporan Anda diproses oleh petugas.
            </p>
            <a href="{{ route('citizen.reports.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-500
                      hover:bg-primary-700 text-white text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 16 16" aria-hidden="true">
                    <path d="M5 2h6a1 1 0 011 1v11a1 1 0 01-1 1H5a1 1 0 01-1-1V3a1 1 0 011-1z" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M6 5.5h4M6 8h4M6 10.5h2.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Lihat Laporan Saya
            </a>
        </div>
        @endif

        {{-- Info panel --}}
        <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl bg-blue-50 border border-blue-100 mt-6">
            <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-blue-700 leading-relaxed">
                <span class="font-semibold">Info:</span>
                Notifikasi dikirim via WhatsApp ke nomor
                <span class="font-semibold">{{ Auth::user()->citizen?->phone ?? '—' }}</span>
                setiap kali status laporan Anda berubah.
            </p>
        </div>

    </div>
</div>
@endsection