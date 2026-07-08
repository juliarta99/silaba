<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;

class CategoryMappingController extends Controller
{
    public function index(Request $request)
    {
        // Semua kategori dengan relasi department
        $allCategories = Category::with('department')->orderBy('name')->get();

        // Filter search
        $search = $request->get('search', '');
        $filtered = $search
            ? $allCategories->filter(fn ($c) =>
                str_contains(strtolower($c->name), strtolower($search)) ||
                str_contains(strtolower($c->department?->name ?? ''), strtolower($search))
              )
            : $allCategories;

        // Grup per department
        $grouped = $filtered
            ->filter(fn ($c) => $c->department_id !== null)
            ->groupBy('department_id');

        // Kategori belum dipetakan
        $unmapped = $filtered->filter(fn ($c) => $c->department_id === null);

        // Semua departments untuk select
        $departments = Department::orderBy('name')->get();

        // Departments yang ada dalam grup (untuk urutan tampil)
        $deptIds = $grouped->keys();
        $deptsSorted = $departments->filter(fn ($d) => $deptIds->contains($d->id));

        $stats = [
            'total'    => $allCategories->count(),
            'mapped'   => $allCategories->filter(fn ($c) => $c->department_id !== null)->count(),
            'unmapped' => $allCategories->filter(fn ($c) => $c->department_id === null)->count(),
        ];

        return view('admin.mappings.index', compact(
            'grouped', 'unmapped', 'departments', 'deptsSorted',
            'allCategories', 'stats', 'search'
        ));
    }

    // Petakan kategori ke OPD (update department_id)
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
        ], [
            'category_id.required'   => 'Pilih kategori.',
            'department_id.required' => 'Pilih OPD tujuan.',
        ]);

        $category = Category::findOrFail($request->category_id);

        // Cek apakah sudah dipetakan ke dept yang sama
        if ($category->department_id == $request->department_id) {
            return back()->withErrors(['mapping' => "Kategori {$category->name} sudah dipetakan ke OPD ini."]);
        }

        $oldDept = $category->department?->name ?? 'belum dipetakan';
        $category->update(['department_id' => $request->department_id]);

        $newDept = Department::findOrFail($request->department_id)->name;
        return back()->with('success', "Kategori {$category->name} berhasil dipetakan ke {$newDept}.");
    }

    // Hapus pemetaan (set department_id = null)
    public function destroy(Category $category)
    {
        $activeReports = $category->reports()
            ->whereNotIn('status', ['completed', 'rejected'])
            ->count();

        if ($activeReports > 0) {
            return back()->withErrors([
                'mapping' => "Pemetaan {$category->name} tidak dapat dihapus karena masih ada {$activeReports} laporan aktif.",
            ]);
        }

        $name = $category->name;
        $category->update(['department_id' => null]);

        return back()->with('success', "Pemetaan kategori {$name} berhasil dihapus.");
    }
}