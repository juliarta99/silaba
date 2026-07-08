<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\District;
use App\Models\Category;
use App\Models\Department;
use App\Models\Employee;
use App\Models\District_Chief; // Sesuaikan jika namanya berbeda (misal DistrictChief)

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Total Data Master
        $totalUsers = User::count();
        $totalDistricts = District::count();
        $totalCategories = Category::count();
        $totalDepartments = Department::count();

        // 2. Distribusi Pengguna Berdasarkan Role & Posisi (Sesuai Struktur Database)
        $countWarga      = User::where('role', 'citizen')->count();
        $countPetugas    = Employee::where('position', 'field_officer')->count();
        $countSupervisor = Employee::where('position', 'supervisor')->count();
        $countKadis      = Employee::where('position', 'head_of_department')->count();
        $countCamat      = District_Chief::count(); 
        $countBupati     = User::where('role', 'regent')->count();
        
        // Gabungkan ke dalam array untuk di-looping di Blade
        $roleDistribution = [
            [
                'label' => 'Warga',
                'count' => $countWarga,
                'percentage' => $totalUsers > 0 ? round(($countWarga / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-blue-500' // Menggunakan warna tailwind standard
            ],
            [
                'label' => 'Petugas',
                'count' => $countPetugas,
                'percentage' => $totalUsers > 0 ? round(($countPetugas / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-green-400'
            ],
            [
                'label' => 'Supervisor',
                'count' => $countSupervisor,
                'percentage' => $totalUsers > 0 ? round(($countSupervisor / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-purple-400'
            ],
            [
                'label' => 'Kepala Dinas',
                'count' => $countKadis,
                'percentage' => $totalUsers > 0 ? round(($countKadis / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-orange-400'
            ],
            [
                'label' => 'Camat',
                'count' => $countCamat,
                'percentage' => $totalUsers > 0 ? round(($countCamat / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-teal-400'
            ],
            [
                'label' => 'Bupati/Sekda',
                'count' => $countBupati,
                'percentage' => $totalUsers > 0 ? round(($countBupati / $totalUsers) * 100, 1) : 0,
                'color' => 'bg-error' // Menggunakan custom config theme (color-error)
            ],
        ];

        // 3. Log Aktivitas Terbaru (Simulasi dari Kategori & User terbaru)
        $activities = collect();

        // Ambil 3 user terbaru
        $latestUsers = User::latest()->take(3)->get();
        foreach ($latestUsers as $user) {
            $activities->push([
                'title' => 'Pengguna Baru',
                'description' => "Akun {$user->name} telah dibuat",
                'created_at' => $user->created_at,
                'user' => 'Sistem',
                'color' => 'bg-green-500'
            ]);
        }

        // Ambil 3 kategori terbaru
        $latestCategories = Category::latest()->take(3)->get();
        foreach ($latestCategories as $cat) {
            $activities->push([
                'title' => 'Kategori Baru',
                'description' => "Kategori '{$cat->name}' telah ditambahkan",
                'created_at' => $cat->created_at,
                'user' => 'Admin',
                'color' => 'bg-blue-500'
            ]);
        }

        // Urutkan berdasarkan waktu paling baru dan ambil 5 teratas
        $activities = $activities->sortByDesc('created_at')->values()->take(5);

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalDistricts', 
            'totalCategories', 
            'totalDepartments', 
            'roleDistribution', 
            'activities'
        ));
    }
}