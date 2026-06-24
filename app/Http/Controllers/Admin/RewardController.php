<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::withCount('claims')->latest()->paginate(15);
        return view('admin.rewards.index', compact('rewards'));
    }
    public function create() { return view('admin.rewards.create'); }
    public function store(Request $r)
    {
        $r->validate(['name'=>'required','points_required'=>'required|integer|min:1','stock'=>'required|integer|min:0']);
        $data = $r->only('name','points_required','stock','description','is_active');
        if ($r->hasFile('photo')) $data['photo'] = $r->file('photo')->store('rewards','public');
        Reward::create($data);
        return redirect()->route('admin.rewards.index')->with('success','Reward ditambahkan.');
    }
    public function edit(Reward $reward) { return view('admin.rewards.edit', compact('reward')); }
    public function update(Request $r, Reward $reward)
    {
        $data = $r->only('name','points_required','stock','description','is_active');
        if ($r->hasFile('photo')) $data['photo'] = $r->file('photo')->store('rewards','public');
        $reward->update($data);
        return redirect()->route('admin.rewards.index')->with('success','Reward diperbarui.');
    }
    public function destroy(Reward $reward)
    {
        $reward->delete();
        return redirect()->route('admin.rewards.index')->with('success','Reward dihapus.');
    }
}
