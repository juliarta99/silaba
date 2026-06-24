<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::withCount('reports')->get();
        return view('admin.districts.index', compact('districts'));
    }
    public function create()  { return view('admin.districts.create'); }
    public function store(Request $request)
    {
        $request->validate(['name'=>'required|unique:districts','code'=>'required|unique:districts']);
        District::create($request->only('name','code','regent_name','area_km2'));
        return redirect()->route('admin.districts.index')->with('success','Kecamatan ditambahkan.');
    }
    public function show(District $district)
    {
        $district->loadCount('reports');
        return view('admin.districts.show', compact('district'));
    }
    public function edit(District $district)  { return view('admin.districts.edit', compact('district')); }
    public function update(Request $r, District $district)
    {
        $district->update($r->only('name','code','regent_name','area_km2'));
        return redirect()->route('admin.districts.index')->with('success','Kecamatan diperbarui.');
    }
    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('admin.districts.index')->with('success','Kecamatan dihapus.');
    }
}
