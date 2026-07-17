<?php

use Illuminate\Support\Facades\Route;

// ── Controllers ───────────────────────────────────────────────────────────────
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Citizen\DashboardController         as CitizenDashboard;
use App\Http\Controllers\Citizen\ProfileController           as CitizenProfile;
use App\Http\Controllers\Citizen\ReportController            as CitizenReport;
use App\Http\Controllers\Citizen\RewardClaimController       as CitizenRewardClaim;

// Employee — Field Officer (field_officer)
use App\Http\Controllers\Employee\FieldOfficer\DashboardController    as FODashboard;
use App\Http\Controllers\Employee\FieldOfficer\AssignmentController   as FOAssignment;

// Employee — Supervisor (supervisor) + Head of Department (head_of_department)
// Supervisor & Kepala Dinas berbagi controller yang sama — dibedakan scope datanya
use App\Http\Controllers\Employee\Supervisor\DashboardController      as SupDashboard;
use App\Http\Controllers\Employee\Supervisor\ReportController         as SupReport;
use App\Http\Controllers\Employee\Supervisor\AssignmentController     as SupAssignment;
use App\Http\Controllers\Employee\Supervisor\DepartmentController     as SupDepartment;
use App\Http\Controllers\Employee\Supervisor\ReviewController         as SupReview;

// Employee — Head of Department (head_of_department) hanya untuk halaman eksklusif HoD
use App\Http\Controllers\Employee\HeadOfDepartment\DashboardController as HoDDashboard;

// Admin / Super Admin
use App\Http\Controllers\Admin\ProfileController       as AdminProfile;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryMappingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Citizen\NotificationController as CitizenNotificationController;
use App\Http\Controllers\Citizen\RewardClaimPdfController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DistrictChief\CompareController;
use App\Http\Controllers\DistrictChief\DashboardController as CamatDashboardController;
use App\Http\Controllers\DistrictChief\PriorityController;
use App\Http\Controllers\DistrictChief\ProfileController as DistrictChiefProfileController;
use App\Http\Controllers\DistrictChief\ReportController as DistrictChiefReportController;
use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Regent\CompareController as RegentCompareController;
// Regent ( Bupati / Sekda)
use App\Http\Controllers\Regent\DashboardController    as RegentDashboard;
use App\Http\Controllers\Regent\PriorityController as RegentPriorityController;
use App\Http\Controllers\Regent\ProfileController as RegentProfileController;
use App\Http\Controllers\Regent\ReportController as RegentReportController;
use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\Shared\MapController;

/*
|=============================================================================
| PUBLIC ROUTES — Tanpa login
|=============================================================================
*/
Route::get('/',                    [PublicController::class, 'index'])->name('home');
Route::get('/tentang',             [PublicController::class, 'about'])->name('about');
Route::get('/laporan',             [ReportController::class, 'index'])->name('reports.index');
Route::get('/laporan/buat',     fn() => view('public.reports.create'))->name('reports.create');
Route::get('/laporan/berhasil', [ReportController::class, 'success'])->name('reports.success');
Route::get('/laporan/{code}',   [ReportController::class, 'show'])->name('reports.show');
Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');

/*
|=============================================================================
| AUTH ROUTES
|=============================================================================
*/
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.post');

    Route::view('/daftar', 'auth.register')->name('register');
    Route::view('/verifikasi-berhasil', 'auth.verify-otp-success')->name('verify.otp.success');

});
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|=============================================================================
| CITIZEN — /warga
| user.role = citizen
|=============================================================================
*/
Route::middleware(['auth', 'role:citizen'])
    ->prefix('warga')
    ->name('citizen.')
    ->group(function () {

    Route::get('/dashboard',                    [CitizenDashboard::class,    'index'])->name('dashboard');
    Route::get('/profil',            [CitizenProfile::class, 'index'])->name('profile');
    Route::patch('/profil',          [CitizenProfile::class, 'update'])->name('profile.update');
    Route::patch('/profil/foto',     [CitizenProfile::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profil/password', [CitizenProfile::class, 'updatePassword'])->name('profile.password');

    Route::get('/laporan/export', [CitizenReport::class, 'export'])->name('reports.export');

    // Laporan Saya
    Route::get('/laporan',                           [CitizenReport::class, 'index'])->name('reports.index');
    Route::get('/laporan/{code}',                    [CitizenReport::class, 'show'])->name('reports.show');
    Route::post('/laporan/{code}/konfirmasi',        [CitizenReport::class, 'confirm'])->name('reports.confirm');
    Route::post('/laporan/{code}/belum-selesai',     [CitizenReport::class, 'rejectCompletion'])->name('reports.reject-completion');
    Route::post('/laporan/{code}/dispute',           [CitizenReport::class, 'disputeDuplicate'])->name('reports.dispute');
    Route::get('/laporan/{code}/rating',             [CitizenReport::class, 'rateForm'])->name('reports.rate');
    Route::post('/laporan/{code}/rating',            [CitizenReport::class, 'storeRating'])->name('reports.rate.store');
    Route::get('/laporan/{code}/rating/berhasil',    [CitizenReport::class, 'rateSuccess'])->name('reports.rate.success');
    Route::delete('/laporan/{code}', [CitizenReport::class, 'destroy'])->name('reports.destroy');

    Route::get('/reward/klaim/{rewardClaim}/pdf', [RewardClaimPdfController::class, 'download'])->name('reward.claim.pdf');

    // Reward & Klaim
    Route::get('/reward', [CitizenRewardClaim::class, 'index'])->name('reward-claims.index');
    Route::post('/reward/{reward}', [CitizenRewardClaim::class, 'store'])->name('reward-claims.store');
    Route::get('/reward/riwayat', [CitizenRewardClaim::class, 'history'])->name('reward-claims.history');
    Route::get('/reward/klaim/{rewardClaim}/berhasil', [CitizenRewardClaim::class, 'success'])->name('reward-claims.success');
    Route::get('/reward/klaim/{rewardClaim}', [CitizenRewardClaim::class, 'show'])->name('reward-claims.show');

    // Notification
    Route::get('/notifikasi', [CitizenNotificationController::class, 'index'])->name('notifications.index');
});

Route::middleware(['auth', 'role:employee'])
    ->prefix('petugas')
    ->name('employee.')
    ->group(function () {

    Route::get('/profil',            [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profil',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/foto',     [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
/*
|=============================================================================
| EMPLOYEE — FIELD OFFICER — /petugas
| user.role = employee  AND  employee.position = field_officer
|=============================================================================
*/
Route::middleware(['auth', 'role:employee', 'employee.position:field_officer'])
    ->prefix('petugas')
    ->name('employee.field-officer.')
    ->group(function () {

    Route::get('/dashboard',           [FODashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard/aktivitas', [FODashboard::class, 'activities'])->name('activities');
    Route::get('/tugas', [FOAssignment::class, 'index'])->name('assignments.index');
    Route::get('/tugas/{code}', [FOAssignment::class, 'show'])->name('assignments.show');
    Route::get('/tugas/{code}/progress/buat', [FOAssignment::class, 'createProgress'])->name('assignments.progress.create');
    Route::post('/tugas/{code}/progress', [FOAssignment::class, 'storeProgress'])->name('assignments.progress.store');

    Route::get('/peta', [MapController::class, 'index'])->name('reports.map');
});

/*
|=============================================================================
| EMPLOYEE — SUPERVISOR — /supervisor
| user.role = employee  AND  employee.position = supervisor
|
| Halaman yang diakses: Dashboard, Daftar Laporan, Penugasan Petugas,
|                       Kelola Instansi, Performa, Peta Sebaran
| (sama dengan head_of_department, lihat grup shared di bawah)
|=============================================================================
*/
Route::middleware(['auth', 'role:employee', 'employee.position:supervisor'])
    ->prefix('supervisor')
    ->name('employee.supervisor.')
    ->group(function () {

    Route::get('/dashboard',  [SupDashboard::class, 'index'])->name('dashboard');
    Route::get('/peta', [MapController::class, 'index'])->name('reports.map');

    // Daftar Laporan
    Route::get('/laporan',         [SupReport::class, 'index']) ->name('reports.index');
    Route::delete('/laporan/{code}', [SupReport::class, 'destroy'])->name('reports.destroy');
    Route::get('/laporan/export',  [SupReport::class, 'export'])->name('reports.export');
    Route::post('/laporan/{code}/tambah-petugas',  [SupReport::class, 'addOfficer'])   ->name('reports.add-officer');
    Route::post('/laporan/{code}/hapus-petugas',   [SupReport::class, 'removeOfficer'])->name('reports.remove-officer');

    // ── Penugasan ─────────────────────────────────────────────────────
    Route::get('/penugasan',           [SupAssignment::class, 'index'])  ->name('assignments.index');
    Route::post('/penugasan',          [SupAssignment::class, 'store'])  ->name('assignments.store');
    Route::delete('/penugasan/hapus',  [SupAssignment::class, 'destroy'])->name('assignments.destroy');

    Route::get('/performa',             [SupReview::class,      'index'])->name('reviews.index');
    Route::get('/performa/export',      [SupReview::class,     'export'])->name('reviews.export');
});

/*
|=============================================================================
| EMPLOYEE — HEAD OF DEPARTMENT — /kepala-dinas
| user.role = employee  AND  employee.position = head_of_department
|
| Dashboard khusus HoD (lebih banyak data eksekutif),
| halaman lainnya shared dengan supervisor di grup bersama di bawah
|=============================================================================
*/
Route::middleware(['auth', 'role:employee', 'employee.position:head_of_department'])
    ->prefix('kepala-dinas')
    ->name('employee.head.')
    ->group(function () {

    Route::get('/dashboard',  [HoDDashboard::class, 'index'])->name('dashboard');

    Route::get('/peta', [MapController::class, 'index'])->name('reports.map');

    // Daftar Laporan
    Route::get('/laporan',         [SupReport::class, 'index']) ->name('reports.index');
    Route::get('/laporan/export',  [SupReport::class, 'export'])->name('reports.export');
    Route::post('/laporan/{code}/tambah-petugas',  [SupReport::class, 'addOfficer'])   ->name('reports.add-officer');
    Route::post('/laporan/{code}/hapus-petugas',   [SupReport::class, 'removeOfficer'])->name('reports.remove-officer');

    // ── Penugasan ─────────────────────────────────────────────────────
    Route::get('/penugasan',           [SupAssignment::class, 'index'])  ->name('assignments.index');
    Route::post('/penugasan',          [SupAssignment::class, 'store'])  ->name('assignments.store');
    Route::delete('/penugasan/hapus',  [SupAssignment::class, 'destroy'])->name('assignments.destroy');

    Route::get('/performa',             [SupReview::class,      'index'])->name('reviews.index');
});

/*
|=============================================================================
| EMPLOYEE — SHARED (Supervisor + Head of Department)
| user.role = employee  AND  employee.position IN (supervisor, head_of_department)
|
| Route ini bisa diakses keduanya.
| Frame desain: "Supervisor-Kadis" = bisa diakses supervisor & head_of_department
| Prefix: /dinas  (netral, bukan milik salah satu)
|=============================================================================
*/
Route::middleware(['auth', 'role:employee', 'employee.position:supervisor,head_of_department'])
    ->prefix('dinas')
    ->name('employee.shared.')
    ->group(function () {

    // Kelola Instansi (Department)
    Route::get('/instansi',             [SupDepartment::class,  'index'])->name('departments.index');
    Route::get('/instansi/{department}',[SupDepartment::class,  'show'])->name('departments.show');
    Route::post('/instansi',            [SupDepartment::class,  'store'])->name('departments.store');
    Route::put('/instansi/{department}',[SupDepartment::class,  'update'])->name('departments.update');
    Route::delete('/instansi/{department}',[SupDepartment::class,'destroy'])->name('departments.destroy');
    Route::post('/pegawai', [SupDepartment::class, 'storeEmployee'])->name('employees.store');
    Route::put('/pegawai/{employee}', [SupDepartment::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/pegawai/{employee}', [SupDepartment::class, 'destroyEmployee'])->name('employees.destroy');
});

// CAMAT
Route::middleware(['auth', 'role:district_chief'])
    ->prefix('camat')
    ->name('district-chief.')
    ->group(function () {
 
    Route::get('/dashboard',        [CamatDashboardController::class, 'index'])->name('dashboard');
    Route::get('/peta',             [MapController::class,  'index'])->name('reports.map');

    Route::get('/laporan',         [DistrictChiefReportController::class, 'index']) ->name('reports.index');
    Route::get('/komparasi',        [CompareController::class, 'index'])->name('reports.compare');
    Route::get('/komparasi/export', [CompareController::class, 'export'])->name('reports.compare.export');

    // profile
    Route::get('/profil',           [DistrictChiefProfileController::class, 'index'])->name('profile');
    Route::patch('/profil/update',  [DistrictChiefProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/foto',    [DistrictChiefProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profil/password',[DistrictChiefProfileController::class, 'updatePassword'])->name('profile.password');

    // priority
    Route::get('/prioritas',        [PriorityController::class, 'index']) ->name('reports.priority');
    Route::get('/prioritas/export', [PriorityController::class, 'export'])->name('reports.priority.export');
});

/*
|=============================================================================
| ADMIN / SUPER ADMIN — /admin
| user.role = admin | super_admin
|
| Perbedaan admin vs super_admin:
|   admin      → CRUD laporan, penugasan, OPD, kategori, reward
|   super_admin → semua admin + tambah/hapus admin lain (UserController)
|=============================================================================
*/
Route::middleware(['auth', 'role:admin|super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil',     [AdminProfile::class,   'index'])->name('profile');

    // ── Manajemen Pengguna (semua user: warga, petugas, supervisor, dll) ──
    Route::get('/pengguna',          [UserController::class, 'index'])  ->name('users.index');
    Route::get('/pengguna/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/pengguna/tambah',   [UserController::class, 'create']) ->name('users.create');
    Route::post('/pengguna',         [UserController::class, 'store'])  ->name('users.store');
    Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/pengguna/{user}',   [UserController::class, 'update']) ->name('users.update');
    Route::delete('/pengguna/{user}',[UserController::class, 'destroy'])->name('users.destroy');

    // ── Manajemen Kecamatan ──
    Route::get('/kecamatan',           [DistrictController::class, 'index'])  ->name('districts.index');
    Route::post('/kecamatan',          [DistrictController::class, 'store'])  ->name('districts.store');
    Route::get('/kecamatan/export', [DistrictController::class, 'export'])->name('districts.export');
    Route::put('/kecamatan/{district}',[DistrictController::class, 'update']) ->name('districts.update');
    Route::delete('/kecamatan/{district}',[DistrictController::class,'destroy'])->name('districts.destroy');

    // ── Manajemen Kategori ──
    Route::get('/kategori',             [CategoryController::class, 'index'])  ->name('categories.index');
    Route::post('/kategori',            [CategoryController::class, 'store'])  ->name('categories.store');
    Route::get('/kategori/export', [CategoryController::class, 'export'])->name('categories.export');
    Route::put('/kategori/{category}',  [CategoryController::class, 'update']) ->name('categories.update');
    Route::delete('/kategori/{category}',[CategoryController::class,'destroy'])->name('categories.destroy');

    // ── Manajemen OPD (Department) ──
    Route::get('/opd',                    [AdminDepartmentController::class, 'index'])     ->name('departments.index');
    Route::post('/opd',                   [AdminDepartmentController::class, 'store'])     ->name('departments.store');
    Route::get('/opd/export', [AdminDepartmentController::class, 'export'])->name('departments.export');
    Route::put('/opd/{department}',       [AdminDepartmentController::class, 'update'])    ->name('departments.update');
    Route::delete('/opd/{department}',    [AdminDepartmentController::class, 'destroy'])   ->name('departments.destroy');
    Route::post('/opd/{department}/kadis',[AdminDepartmentController::class, 'assignHead'])->name('departments.assign-head');

    // ── Pemetaan Kategori OPD ──
    Route::get('/pemetaan',              [CategoryMappingController::class, 'index'])  ->name('mappings.index');
    Route::post('/pemetaan',             [CategoryMappingController::class, 'store'])  ->name('mappings.store');
    Route::get('/pemetaan/export', [CategoryMappingController::class, 'export'])->name('mappings.export');
    Route::delete('/pemetaan/{category}',[CategoryMappingController::class, 'destroy'])->name('mappings.destroy');

    // ── Manajemen Reward ──
    Route::get('/reward', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/reward', [RewardController::class, 'store'])->name('rewards.store');
    Route::get('/reward/export', [RewardController::class, 'export'])->name('rewards.export');
    Route::put('/reward/{reward}', [RewardController::class, 'update'])->name('rewards.update'); // Rute Edit Baru
    Route::post('/reward/voucher', [RewardController::class, 'storeVoucher'])->name('rewards.store_voucher');
    Route::get('/reward/history', [RewardController::class, 'history'])->name('rewards.history');

    // ── Notifikasi ──
    Route::get('/notifikasi',            [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi',           [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifikasi/export', [NotificationController::class, 'export'])->name('notifications.export');
    Route::delete('/notifikasi/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

/*
|=============================================================================
| REGENT (Bupati / Sekda) — /pejabat
| user.role = regent
|
| Halaman: Dashboard ESS, Komparasi Instansi, Rekomendasi Prioritas,
|          Daftar Laporan, Peta Sebaran
|=============================================================================
*/
Route::middleware(['auth', 'role:regent'])
    ->prefix('pejabat')
    ->name('regent.')
    ->group(function () {

    Route::get('/dashboard',        [RegentDashboard::class, 'index'])->name('dashboard');
    Route::get('/peta',             [MapController::class,  'index'])->name('reports.map');

    Route::get('/laporan',         [RegentReportController::class, 'index']) ->name('reports.index');
    Route::get('/komparasi',        [RegentCompareController::class, 'index'])->name('reports.compare');
    Route::get('/komparasi/export', [RegentCompareController::class, 'export'])->name('reports.compare.export');

    // profile
    Route::get('/profil',           [RegentProfileController::class, 'index'])->name('profile');
    Route::patch('/profil/update',  [RegentProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/foto',    [RegentProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::patch('/profil/password',[RegentProfileController::class, 'updatePassword'])->name('profile.password');

    // priority
    Route::get('/prioritas',        [RegentPriorityController::class, 'index']) ->name('reports.priority');
    Route::get('/prioritas/export', [RegentPriorityController::class, 'export'])->name('reports.priority.export');
});


Route::middleware('auth')
    ->get('/laporan/{code}/pdf', [ReportPdfController::class, 'download'])
    ->name('reports.pdf.download');