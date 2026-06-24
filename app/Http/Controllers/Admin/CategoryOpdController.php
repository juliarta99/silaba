<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Category, Department};
use Illuminate\Http\Request;

class CategoryOpdController extends Controller
{
    public function index()
    {
        $categories  = Category::with('departments')->get();
        $departments = Department::all();
        return view('admin.category-opd.index', compact('categories','departments'));
    }
    public function store(Request $r)
    {
        $r->validate(['category_id'=>'required|exists:categories,id','department_id'=>'required|exists:departments,id']);
        // TODO: attach via pivot table category_department
        // Category::find($r->category_id)->departments()->syncWithoutDetaching([$r->department_id]);
        return back()->with('success','Pemetaan ditambahkan.');
    }
    public function update(Request $r, $mapping)
    {
        // TODO: update pivot
        return back()->with('success','Pemetaan diperbarui.');
    }
    public function destroy($mapping)
    {
        // TODO: detach pivot
        return back()->with('success','Pemetaan dihapus.');
    }
}
