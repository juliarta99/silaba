<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = auth()->user()->employee->load('department');
        return view('employee.supervisor.profile', compact('employee'));
    }
}
