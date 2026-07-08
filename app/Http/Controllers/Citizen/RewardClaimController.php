<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardClaim;
use App\Models\RewardVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardClaimController extends Controller
{
    // ── Katalog reward ────────────────────────────────────────────────────
    public function index()
    {
        $points = Auth::user()->citizen?->points ?? 0;

        // Ambil semua reward aktif beserta count voucher available
        $rewards = Reward::where('is_active', true)
            ->withCount(['vouchers as stock' => fn ($q) => $q->available()])
            ->orderBy('points_required')
            ->get();

        return view('citizen.rewards.index', compact('rewards', 'points'));
    }

    // ── Klaim reward → langsung dapat kode ───────────────────────────────
    public function store(Request $request, Reward $reward)
    {
        $citizen = Auth::user()->citizen;

        abort_if(! $reward->is_active, 404);

        if (($citizen?->points ?? 0) < $reward->points_required) {
            return back()->with('error', 'Poin Anda tidak cukup untuk klaim reward ini.');
        }

        $claim = null;

        DB::transaction(function () use ($reward, $citizen, &$claim) {

            // Ambil voucher available (lock for update → cegah race condition)
            $voucher = RewardVoucher::where('reward_id', $reward->id)
                ->available()
                ->lockForUpdate()
                ->first();

            if (! $voucher) {
                throw new \RuntimeException('Stok reward sudah habis.');
            }

            // Tandai voucher sebagai claimed
            $voucher->update(['is_claimed' => true]);

            // Kurangi poin citizen
            $citizen->decrement('points', $reward->points_required);

            // Buat klaim — langsung linked ke voucher
            $claim = RewardClaim::create([
                'user_id'           => Auth::user()->id,
                'reward_id'         => $reward->id,
                'reward_voucher_id' => $voucher->id,
                'points_used'       => $reward->points_required,
            ]);
        });

        if (! $claim) {
            return back()->with('error', 'Stok reward sudah habis. Silakan pilih reward lain.');
        }

        return redirect()->route('citizen.reward-claims.success', $claim->id);
    }

    public function success(RewardClaim $rewardClaim)
    {
        // Pastikan hanya pemilik klaim yang bisa melihat halaman ini
        abort_if($rewardClaim->user_id !== Auth::user()->id, 403);
        
        $rewardClaim->load(['reward', 'voucher']);
        
        return view('citizen.rewards.success', compact('rewardClaim'));
    }
    
    // ── Detail tiket klaim + QR ───────────────────────────────────────────
    public function show(RewardClaim $rewardClaim)
    {
        abort_if($rewardClaim->user_id !== Auth::user()->id, 403);
        $rewardClaim->load(['reward', 'voucher']);

        return view('citizen.rewards.show', compact('rewardClaim'));
    }

    // ── Riwayat klaim ─────────────────────────────────────────────────────
    public function history()
    {
        $points = Auth::user()->citizen?->points ?? 0;

        $claims = RewardClaim::where('user_id', Auth::user()->id)
            ->with(['reward', 'voucher'])
            ->latest()
            ->paginate(15);

        return view('citizen.rewards.history', compact('points', 'claims'));
    }
}