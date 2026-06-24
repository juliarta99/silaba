<?php
namespace App\Http\Controllers\Employee\HeadOfDepartment;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee->load('department');
        return view('employee.head-of-department.profile', compact('employee'));
    }
}
