<?php
namespace App\Http\Controllers\Citizen;
use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardClaim;
use Illuminate\Http\Request;

class RewardClaimController extends Controller
{
    public function index()
    {
        $rewards  = Reward::where('is_active', true)->where('stock', '>', 0)->get();
        $citizen  = auth()->user()->citizen;
        $points   = $citizen->points ?? 0;
        return view('citizen.rewards.index', compact('rewards','points'));
    }

    public function show(Reward $reward)
    {
        return view('citizen.rewards.show', compact('reward'));
    }

    public function store(Request $request, Reward $reward)
    {
        $citizen = auth()->user()->citizen;
        abort_if($citizen->points < $reward->points_required, 422, 'Poin tidak mencukupi.');
        abort_if($reward->stock <= 0, 422, 'Stok habis.');

        RewardClaim::create(['citizen_id' => $citizen->id, 'reward_id' => $reward->id, 'points_used' => $reward->points_required]);
        $citizen->decrement('points', $reward->points_required);
        $reward->decrement('stock');

        return redirect()->route('citizen.reward-claims.success');
    }

    public function history()
    {
        $claims = RewardClaim::where('citizen_id', auth()->user()->citizen->id)
            ->with('reward')->latest()->paginate(10);
        return view('citizen.rewards.history', compact('claims'));
    }

    public function success() { return view('citizen.rewards.success'); }
}
