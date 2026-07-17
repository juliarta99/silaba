<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\RewardVoucher;
use App\Models\RewardClaim;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    // ── 1. Halaman Manajemen Reward ──
    public function index()
    {
        // Hitung statistik untuk Dashboard Reward
        $stats = [
            'total_rewards' => Reward::count(),
            'active_rewards' => Reward::where('is_active', true)->count(),
            'total_vouchers' => RewardVoucher::where('is_claimed', false)->count(),
            'total_claims' => RewardClaim::count(),
        ];

        // Ambil data reward beserta jumlah vouchernya
        $rewards = Reward::withCount([
            'vouchers as total_vouchers',
            'vouchers as available_vouchers' => function ($query) {
                $query->where('is_claimed', false);
            }
        ])->latest()->paginate(10);

        return view('admin.rewards.index', compact('stats', 'rewards'));
    }

    // ── 2. Halaman Riwayat Klaim Reward ──
    public function history()
    {
        $claims = RewardClaim::with(['user', 'reward', 'voucher'])
            ->latest()
            ->paginate(15);

        return view('admin.rewards.history', compact('claims'));
    }

    // ── 3. Proses Tambah Reward Baru ──
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'points_required' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
        }

        Reward::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'type' => $request->type,
            'points_required' => $request->points_required,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.rewards.index')->with('success', 'Reward baru berhasil ditambahkan.');
    }

    // ── 4. Proses Tambah Voucher ke Reward ──
    public function storeVoucher(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
            'code' => 'required|string|unique:reward_vouchers,code',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        RewardVoucher::create([
            'reward_id' => $request->reward_id,
            'code' => strtoupper($request->code),
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_claimed' => false,
        ]);

        return redirect()->route('admin.rewards.index')->with('success', 'Voucher ' . $request->code . ' berhasil ditambahkan.');
    }

    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'points_required' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'required|boolean', // Status aktif/inaktif
        ]);

        $imagePath = $reward->image; // Pertahankan gambar lama secara default
        
        // Jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('rewards', 'public');
        }

        // Lakukan update data
        $reward->update([
            'name' => $request->name,
            'type' => $request->type,
            'points_required' => $request->points_required,
            'description' => $request->description,
            'image' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.rewards.index')->with('success', 'Data Reward berhasil diperbarui.');
    }

    public function export()
    {
        $rewards = Reward::with([
            'vouchers' => fn ($q) => $q->with('claim.user')->orderBy('code'),
        ])
        ->withCount([
            'vouchers',
            'vouchers as vouchers_claimed_count' => fn ($q) => $q->where('is_claimed', true),
            'vouchers as vouchers_available_count' => fn ($q) => $q->where('is_claimed', false),
            'vouchers as vouchers_expired_count' => fn ($q) => $q->where('is_claimed', false)
                                                                ->whereNotNull('valid_until')
                                                                ->where('valid_until', '<', now()),
        ])
        ->orderBy('name')
        ->get();

        $filename = 'data-reward-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rewards) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ── SECTION 1: Ringkasan Reward ───────────────────────────────────
            fputcsv($h, ['══ RINGKASAN REWARD ══'], ';');
            fputcsv($h, [
                'No', 'Nama Reward', 'Tipe', 'Poin Diperlukan', 'Status',
                'Total Voucher', 'Tersedia', 'Diklaim', 'Kadaluarsa',
            ], ';');

            $no = 1;
            foreach ($rewards as $rw) {
                fputcsv($h, [
                    $no++,
                    $rw->name,
                    $rw->type,
                    $rw->points_required,
                    $rw->is_active ? 'Aktif' : 'Nonaktif',
                    $rw->vouchers_count,
                    $rw->vouchers_available_count,
                    $rw->vouchers_claimed_count,
                    $rw->vouchers_expired_count,
                ], ';');
            }

            // ── SECTION 2: Detail Voucher per Reward ─────────────────────────
            fputcsv($h, [], ';');
            fputcsv($h, ['══ DETAIL VOUCHER PER REWARD ══'], ';');

            foreach ($rewards as $rw) {
                fputcsv($h, [], ';');
                fputcsv($h, ["── {$rw->name} ({$rw->type}) | {$rw->points_required} Poin ──"], ';');
                fputcsv($h, [
                    'No', 'Kode Voucher',
                    'Status', 'Berlaku Mulai', 'Berlaku Hingga',
                    'Diklaim Oleh', 'Tanggal Klaim', 'Poin Digunakan',
                ], ';');

                if ($rw->vouchers->count() === 0) {
                    fputcsv($h, ['', '(Belum ada voucher)', '', '', '', '', '', ''], ';');
                    continue;
                }

                $no = 1;
                foreach ($rw->vouchers as $v) {
                    $isExpired = ! $v->is_claimed
                        && $v->valid_until
                        && \Carbon\Carbon::parse($v->valid_until)->isPast();

                    $status = match (true) {
                        (bool) $v->is_claimed => 'Diklaim',
                        $isExpired            => 'Kadaluarsa',
                        default               => 'Tersedia',
                    };

                    fputcsv($h, [
                        $no++,
                        $v->code,
                        $status,
                        $v->valid_from
                            ? \Carbon\Carbon::parse($v->valid_from)->format('d/m/Y')
                            : '—',
                        $v->valid_until
                            ? \Carbon\Carbon::parse($v->valid_until)->format('d/m/Y')
                            : 'Tidak Kadaluarsa',
                        $v->claim?->user?->name ?? '—',
                        $v->claim?->created_at?->format('d/m/Y H:i') ?? '—',
                        $v->claim?->points_used ?? '—',
                    ], ';');
                }
            }

            fclose($h);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}