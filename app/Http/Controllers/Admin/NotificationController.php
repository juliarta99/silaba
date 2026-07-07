<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Statistik Notifikasi
        $stats = [
            'total'   => Notification::count(),
            'sent'    => Notification::where('is_sent', true)->count(),
            'pending' => Notification::where('is_sent', false)->count(),
            'today'   => Notification::whereDate('created_at', Carbon::today())->count(),
        ];

        // 2. Query Data dengan Pencarian
        // Asumsi: Model Notification memiliki relasi belongTo ke model Report
        $query = Notification::with('report')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
        }

        $notifications = $query->paginate(15);

        return view('admin.notifications.index', compact('stats', 'notifications'));
    }
}