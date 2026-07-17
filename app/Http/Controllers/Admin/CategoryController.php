<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('reports')
            ->with('department');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('name',       'like', "%{$s}%")
                  ->orWhere('description','like', "%{$s}%")
                  ->orWhereHas('department', fn ($r) => $r->where('name','like',"%{$s}%"))
            );
        }

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        $categories = $query->orderBy('name')->get();

        $stats = [
            'total'        => $categories->count(),
            'total_reports'=> $categories->sum('reports_count'),
            'avg_reports'  => $categories->count() > 0
                ? round($categories->avg('reports_count')) : 0,
        ];

        $departments = Department::orderBy('name')->get();

        return view('admin.categories.index', compact(
            'categories', 'stats', 'departments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:50|unique:categories,name',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:500',
            'icon'          => 'nullable|string|max:100',
        ], [
            'name.required'          => 'Nama kategori wajib diisi.',
            'name.unique'            => 'Nama kategori sudah terdaftar.',
            'department_id.required' => 'OPD/Dinas wajib dipilih.',
        ]);

        Category::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'department_id' => $request->department_id,
            'description'   => $request->description,
            'icon'          => $request->icon ?: null,
        ]);

        return back()->with('success', "Kategori {$request->name} berhasil ditambahkan.");
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'          => ['required','string','max:50',
                                Rule::unique('categories','name')->ignore($category->id)],
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:500',
            'icon'          => 'nullable|string|max:100',
        ]);

        $category->update([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'department_id' => $request->department_id,
            'description'   => $request->description,
            'icon'          => $request->icon ?: null,
        ]);

        return back()->with('success', "Kategori {$category->name} berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        $active = $category->reports()
            ->whereNotIn('status', ['completed', 'rejected'])
            ->count();

        if ($active > 0) {
            return back()->withErrors([
                'delete' => "Kategori {$category->name} tidak dapat dihapus karena masih memiliki {$active} laporan aktif.",
            ]);
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Kategori {$name} berhasil dihapus.");
    }

    public function export()
    {
        $categories = Category::with(['department'])
            ->withCount([
                'reports',
                'reports as reports_pending_count'   => fn ($q) => $q->where('status', 'pending'),
                'reports as reports_progress_count'  => fn ($q) => $q->whereIn('status', ['in_progress', 'under_review', 'waiting_for_materials']),
                'reports as reports_completed_count' => fn ($q) => $q->where('status', 'completed'),
                'reports as reports_rejected_count'  => fn ($q) => $q->where('status', 'rejected'),
            ])
            ->orderBy('name')
            ->get();

        $filename = 'data-kategori-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($categories) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($h, [
                'No',
                'Nama Kategori',
                'Slug',
                'OPD Penanggung Jawab',
                'Status Pemetaan',
                'Total Laporan',
                'Baru',
                'Diproses',
                'Selesai',
                'Ditolak',
            ], ';');

            $no = 1;
            foreach ($categories as $cat) {
                fputcsv($h, [
                    $no++,
                    $cat->name,
                    $cat->slug,
                    $cat->department?->name ?? '(Belum dipetakan)',
                    $cat->department_id ? 'Sudah Dipetakan' : 'Belum Dipetakan',
                    $cat->reports_count,
                    $cat->reports_pending_count,
                    $cat->reports_progress_count,
                    $cat->reports_completed_count,
                    $cat->reports_rejected_count,
                ], ';');
            }

            fclose($h);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}