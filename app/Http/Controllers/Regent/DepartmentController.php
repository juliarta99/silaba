<?php
namespace App\Http\Controllers\Regent;
use App\Http\Controllers\Controller;
use App\Models\{Department, Review, Report};

class DepartmentController extends Controller
{
    public function compare()
    {
        $departments = Department::all()->map(function($dept) {
            $total     = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$dept->id))->count();
            $completed = Report::whereHas('assignment.employee', fn($q)=>$q->where('department_id',$dept->id))
                           ->where('status','completed')->count();
            $avgRating = Review::whereHas('report.assignment.employee', fn($q)=>$q->where('department_id',$dept->id))->avg('rating');
            $dept->total     = $total;
            $dept->completed = $completed;
            $dept->rate      = $total > 0 ? round($completed / $total * 100, 1) : 0;
            $dept->avgRating = round($avgRating ?? 0, 1);
            return $dept;
        })->sortByDesc('rate');
        return view('regent.departments.compare', compact('departments'));
    }
}
