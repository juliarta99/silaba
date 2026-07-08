<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $phone = Auth::user()->citizen?->phone;

        if (! $phone) {
            return view('citizen.notifications.index', [
                'notifications' => collect(),
                'totalCount'    => 0,
            ]);
        }

        $notifications = Notification::where('phone', $phone)
            ->with(['report' => fn ($q) => $q->select('id', 'code', 'title', 'status', 'priority')])
            ->latest('sent_at')
            ->paginate(20);

        $totalCount = Notification::where('phone', $phone)->count();

        return view('citizen.notifications.index', compact('notifications', 'totalCount'));
    }
}