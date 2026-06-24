<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->get();
        return view('admin.categories.index', compact('categories'));
    }
    public function create() { return view('admin.categories.create'); }
    public function store(Request $r)
    {
        $r->validate(['name'=>'required|unique:categories','description'=>'nullable']);
        Category::create($r->only('name','description','icon'));
        return redirect()->route('admin.categories.index')->with('success','Kategori ditambahkan.');
    }
    public function edit(Category $category) { return view('admin.categories.edit', compact('category')); }
    public function update(Request $r, Category $category)
    {
        $category->update($r->only('name','description','icon'));
        return redirect()->route('admin.categories.index')->with('success','Kategori diperbarui.');
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success','Kategori dihapus.');
    }
}
