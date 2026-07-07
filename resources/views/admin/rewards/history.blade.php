@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-2">
        <a href="{{ route('admin.rewards.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Klaim Reward</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar penukaran poin warga dengan voucher/hadiah.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse text-sm text-gray-600">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase">
                    <th class="p-4">Tanggal Klaim</th>
                    <th class="p-4">Nama Pengguna (Warga)</th>
                    <th class="p-4">Reward Ditebus</th>
                    <th class="p-4">Kode Voucher</th>
                    <th class="p-4 text-center">Poin Dipotong</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($claims as $claim)
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4">
                        <span class="font-bold text-gray-800">{{ $claim->created_at->format('d M Y') }}</span>
                        <span class="block text-xs text-gray-400 mt-0.5">{{ $claim->created_at->format('H:i') }} WITA</span>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex justify-center items-center font-bold text-xs">
                                {{ substr($claim->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $claim->user->name ?? 'Pengguna Dihapus' }}</p>
                                <p class="text-xs text-gray-500">{{ $claim->user->identifier ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 font-semibold text-gray-700">{{ $claim->reward->name ?? 'Reward Dihapus' }}</td>
                    <td class="p-4">
                        <span class="font-mono text-xs bg-gray-100 border border-gray-200 px-2 py-1 rounded text-gray-700 font-bold">
                            {{ $claim->voucher->code ?? 'KODE-HILANG' }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center gap-1 font-bold text-red-500">
                            -{{ number_format($claim->points_used) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="p-8 text-center text-gray-500">Belum ada riwayat klaim reward yang tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($claims->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/30">{{ $claims->links() }}</div>
        @endif
    </div>

</div>
@endsection