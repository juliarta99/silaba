<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $query = District::withCount(['reports','districtChiefs as chief_count'])
            ->with(['activeChief.user']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('name',  'like', "%{$s}%")
                  ->orWhere('email','like', "%{$s}%")
                  ->orWhereHas('districtChiefs', fn ($r) =>
                      $r->whereHas('user', fn ($u) => $u->where('name','like',"%{$s}%"))
                  )
            );
        }

        $districts = $query->orderBy('name')->get();

        $stats = [
            'total'     => $districts->count(),
            'total_reports' => $districts->sum('reports_count'),
        ];

        return view('admin.districts.index', compact('districts','stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:50|unique:districts,name',
            'email' => 'required|email|max:255|unique:districts,email',
            'phone' => 'required|string|max:20',
        ], [
            'name.required'  => 'Nama kecamatan wajib diisi.',
            'name.unique'    => 'Nama kecamatan sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ]);

        District::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', "Kecamatan {$request->name} berhasil ditambahkan.");
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name'  => ['required','string','max:50', Rule::unique('districts','name')->ignore($district->id)],
            'email' => ['required','email','max:255',  Rule::unique('districts','email')->ignore($district->id)],
            'phone' => 'required|string|max:20',
        ]);

        $district->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', "Kecamatan {$district->name} berhasil diperbarui.");
    }

    public function destroy(District $district)
    {
        // Cek apakah ada laporan aktif
        $activeReports = $district->reports()
            ->whereNotIn('status',['completed','rejected'])->count();

        if ($activeReports > 0) {
            return back()->withErrors([
                'delete' => "Tidak dapat menghapus Kecamatan {$district->name} karena masih ada {$activeReports} laporan aktif.",
            ]);
        }

        $name = $district->name;
        $district->delete();

        return back()->with('success', "Kecamatan {$name} berhasil dihapus.");
    }
}