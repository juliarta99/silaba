<?php
namespace App\Http\Controllers\Employee\Supervisor;
use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $deptId = auth()->user()->employee->department_id;
        $reviews = Review::whereHas('report.assignment.employee', fn($q)=>$q->where('department_id',$deptId))
                    ->with(['report','citizen.user'])->latest()->paginate(15);
        $avgRating = $reviews->avg('rating');
        return view('employee.shared.reviews.index', compact('reviews','avgRating'));
    }

    public function show(Review $review)
    {
        $review->load(['report.category','citizen.user']);
        return view('employee.shared.reviews.show', compact('review'));
    }
}
