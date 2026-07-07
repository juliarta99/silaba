@extends('layouts.admin')

@section('content')
<div x-data="{ 
        showModal: false, 
        detailPhone: '', 
        detailMessage: '', 
        detailDate: '',
        detailStatus: false
    }" 
    class="p-6 max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pengiriman pesan sistem (WhatsApp/SMS) ke pengguna.</p>
        </div>
        
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="relative w-full md:w-72">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor HP atau isi pesan..." 
                class="w-full text-sm pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition shadow-sm">
            <div class="absolute left-3 top-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Pesan</p>
            <h3 class="text-2xl font-black text-gray-800 mt-1">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Berhasil Terkirim</p>
            <h3 class="text-2xl font-black text-green-500 mt-1">{{ number_format($stats['sent']) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Pending / Gagal</p>
            <h3 class="text-2xl font-black text-orange-500 mt-1">{{ number_format($stats['pending']) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Antrean Hari Ini</p>
            <h3 class="text-2xl font-black text-blue-500 mt-1">{{ number_format($stats['today']) }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase">
                        <th class="p-4">Tanggal & Waktu</th>
                        <th class="p-4">Tujuan (No. HP)</th>
                        <th class="p-4">Terkait Laporan</th>
                        <th class="p-4">Cuplikan Pesan</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($notifications as $notif)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4">
                            <span class="font-bold text-gray-800">{{ $notif->created_at->format('d M Y') }}</span>
                            <span class="block text-xs text-gray-400 mt-0.5">{{ $notif->created_at->format('H:i') }} WITA</span>
                        </td>
                        
                        <td class="p-4 font-mono text-gray-700">{{ $notif->phone }}</td>
                        
                        <td class="p-4">
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                #{{ $notif->report_id }} </span>
                        </td>
                        
                        <td class="p-4">
                            <p class="text-xs text-gray-500 truncate max-w-[200px] md:max-w-xs" title="{{ $notif->message }}">
                                {{ $notif->message }}
                            </p>
                        </td>
                        
                        <td class="p-4 text-center">
                            @if($notif->is_sent)
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-600 border border-green-200 rounded-full text-[11px] font-bold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Terkirim
                                </div>
                                @if($notif->sent_at)
                                    <div class="text-[10px] text-gray-400 mt-1">{{ Carbon\Carbon::parse($notif->sent_at)->format('H:i') }}</div>
                                @endif
                            @else
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 text-orange-600 border border-orange-200 rounded-full text-[11px] font-bold">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pending
                                </div>
                            @endif
                        </td>
                        
                        <td class="p-4 text-center">
                            <button @click="
                                    showModal = true;
                                    detailPhone = '{{ $notif->phone }}';
                                    detailMessage = '{{ addslashes($notif->message) }}';
                                    detailDate = '{{ $notif->created_at->format('d M Y, H:i') }}';
                                    detailStatus = {{ $notif->is_sent ? 'true' : 'false' }};
                                " 
                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Lihat Detail Pesan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            Belum ada riwayat notifikasi tersimpan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($notifications->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden flex flex-col">
            
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="font-bold text-gray-900">Detail Pesan Notifikasi</h3>
                    <p class="text-xs text-gray-500" x-text="detailDate"></p>
                </div>
                <button @click="showModal = false" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <span class="text-gray-500 block text-xs">Penerima (Tujuan):</span>
                        <span class="font-mono font-bold text-gray-800" x-text="detailPhone"></span>
                    </div>
                    <div>
                        <template x-if="detailStatus">
                            <span class="px-2.5 py-1 bg-green-50 text-green-600 border border-green-200 rounded-full text-xs font-bold">Terkirim</span>
                        </template>
                        <template x-if="!detailStatus">
                            <span class="px-2.5 py-1 bg-orange-50 text-orange-600 border border-orange-200 rounded-full text-xs font-bold">Pending</span>
                        </template>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                    <span class="text-gray-400 block text-xs mb-2 uppercase font-bold tracking-wider">Isi Pesan:</span>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed" x-text="detailMessage"></p>
                </div>
            </div>
            
            <div class="p-4 border-t border-gray-100 bg-gray-50/30 flex justify-end">
                <button @click="showModal = false" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold py-2 px-5 rounded-lg text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection