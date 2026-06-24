<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }
    public function store(Request $r)
    {
        $r->validate(['title'=>'required','message'=>'required','target_role'=>'nullable']);
        Notification::create($r->only('title','message','target_role','target_user_id'));
        // TODO: broadcast via WA / push notification
        return back()->with('success','Notifikasi dikirim.');
    }
    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success','Notifikasi dihapus.');
    }
}
