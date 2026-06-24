<?php
namespace App\Http\Controllers\Citizen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()  { return view('citizen.profile'); }
    public function edit()   { return view('citizen.profile_edit'); }

    public function update(Request $request)
    {
        $request->validate(['name' => 'required|max:100', 'phone' => 'nullable|max:20', 'picture' => 'nullable|image|max:2048']);
        $user = auth()->user();
        $data = $request->only('name');
        if ($request->hasFile('picture')) {
            // TODO: store and delete old picture
            $data['picture'] = $request->file('picture')->store('avatars', 'public');
        }
        $user->update($data);
        return redirect()->route('citizen.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
