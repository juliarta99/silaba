<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User, Department, Category};

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers      = User::count();
        $totalDistricts  = \App\Models\District::count();
        $totalCategories = Category::count();
        $totalDepts      = Department::count();
        $activeOPD       = Department::count(); // bisa filter aktif
        $recentUsers     = User::latest()->take(5)->get();
        return view('admin.dashboard', compact('totalUsers','totalDistricts','totalCategories','totalDepts','activeOPD','recentUsers'));
    }
}
