@extends('layouts.admin')

@section('content')
<div x-data="{ 
        showRewardModal: false, 
        showVoucherModal: false, 
        showEditModal: false, 
        
        selectedRewardId: null, 
        selectedRewardName: '',
        
        editActionUrl: '',
        editName: '',
        editType: '',
        editPoints: '',
        editDesc: '',
        editIsActive: 1
    }" 
    class="p-6 max-w-7xl mx-auto space-y-6">

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-100">
        <span class="font-bold">Sukses!</span> {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Reward</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola katalog hadiah dan ketersediaan voucher sistem.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.rewards.export') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200
                    bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 16 16">
                    <path d="M3 12.5h10M8 2v8m0 0-3-3m3 3 3-3"
                        stroke="currentColor" stroke-width="1.3"
                        stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.rewards.history') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-10 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Riwayat Klaim
            </a>
            <button @click="showRewardModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Reward
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Katalog Reward</p>
            <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $stats['total_rewards'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Reward Aktif</p>
            <h3 class="text-2xl font-black text-green-500 mt-1">{{ $stats['active_rewards'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Voucher Tersedia</p>
            <h3 class="text-2xl font-black text-blue-500 mt-1">{{ $stats['total_vouchers'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Diklaim</p>
            <h3 class="text-2xl font-black text-purple-500 mt-1">{{ $stats['total_claims'] }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm text-gray-600">
                <thead>
                    <tr class="bg-gray-10 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase">
                        <th class="p-4">Info Reward</th>
                        <th class="p-4">Tipe</th>
                        <th class="p-4 text-center">Poin Harga</th>
                        <th class="p-4 text-center">Stok Voucher</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rewards as $reward)
                    <tr class="hover:bg-gray-10/50">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                    @if($reward->image)
                                        <img src="{{ Storage::url($reward->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-gray-400 mx-auto mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $reward->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $reward->description ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-semibold uppercase">{{ $reward->type }}</span></td>
                        <td class="p-4 text-center font-extrabold text-orange-500">{{ number_format($reward->points_required) }}</td>
                        <td class="p-4 text-center">
                            <span class="font-bold {{ $reward->available_vouchers > 0 ? 'text-green-600' : 'text-red-500' }}">
                                {{ $reward->available_vouchers }}
                            </span> 
                            <span class="text-gray-400 text-xs">/ {{ $reward->total_vouchers }}</span>
                        </td>
                        <td class="p-4 text-center">
                            @if($reward->is_active)
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 border border-green-200 rounded-full text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 border border-red-200 rounded-full text-xs font-bold">Inaktif</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center items-center gap-2">
                                <button @click="showVoucherModal = true; selectedRewardId = '{{ $reward->id }}'; selectedRewardName = '{{ addslashes($reward->name) }}'" 
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Tambah Voucher">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                                
                                <button @click="
                                            showEditModal = true; 
                                            editActionUrl = '{{ route('admin.rewards.update', $reward->id) }}';
                                            editName = '{{ addslashes($reward->name) }}';
                                            editType = '{{ $reward->type }}';
                                            editPoints = '{{ $reward->points_required }}';
                                            editDesc = '{{ addslashes($reward->description) }}';
                                            editIsActive = {{ $reward->is_active ? 1 : 0 }};
                                        " 
                                        class="p-1.5 text-gray-400 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition" title="Edit Reward">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Katalog reward masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rewards->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-10/30">{{ $rewards->links() }}</div>
        @endif
    </div>

    <div x-show="showRewardModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showRewardModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-10/50">
                <h3 class="font-bold text-gray-900">Tambah Reward Baru</h3>
                <button @click="showRewardModal = false" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('admin.rewards.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Reward <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Tipe <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="digital">Voucher Digital</option>
                            <option value="fisik">Barang Fisik</option>
                            <option value="saldo">Saldo E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Poin Dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="number" name="points_required" required min="1" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Gambar Cover (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" rows="3" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
                <div class="flex justify-end pt-2 gap-3">
                    <button type="button" @click="showRewardModal = false" class="bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold py-2 px-5 rounded-lg text-sm transition">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-10/50">
                <h3 class="font-bold text-gray-900">Ubah Data Reward</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form :action="editActionUrl" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                @method('PUT') <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Reward <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editName" required class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Tipe <span class="text-red-500">*</span></label>
                        <select name="type" x-model="editType" required class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="digital">Voucher Digital</option>
                            <option value="fisik">Barang Fisik</option>
                            <option value="saldo">Saldo E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Poin Dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="number" name="points_required" x-model="editPoints" required min="1" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Reward <span class="text-red-500">*</span></label>
                    <select name="is_active" x-model="editIsActive" required class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Aktif (Ditampilkan)</option>
                        <option value="0">Inaktif (Disembunyikan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Ganti Gambar (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" x-model="editDesc" rows="3" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
                <div class="flex justify-end pt-2 gap-3">
                    <button type="button" @click="showEditModal = false" class="bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold py-2 px-5 rounded-lg text-sm transition">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showVoucherModal" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div @click.away="showVoucherModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-blue-50">
                <div>
                    <h3 class="font-bold text-blue-900">Inject Kode Voucher</h3>
                    <p class="text-xs text-blue-600 mt-0.5" x-text="selectedRewardName"></p>
                </div>
                <button @click="showVoucherModal = false" class="text-blue-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form action="{{ route('admin.rewards.store_voucher') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="reward_id" :value="selectedRewardId">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kode Voucher / Serial <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="Cth: VCH-XYZ-123" class="w-full text-sm px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none uppercase font-mono">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Masa Aktif Dari</label>
                        <input type="date" name="valid_from" class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Berlaku Sampai</label>
                        <input type="date" name="valid_until" class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg outline-none">
                    </div>
                </div>
                <div class="flex justify-end pt-2 gap-3">
                    <button type="button" @click="showVoucherModal = false" class="bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold py-2.5 px-5 rounded-lg text-sm transition">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm transition shadow-sm">Tambahkan Voucher</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection