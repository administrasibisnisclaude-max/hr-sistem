<?php

namespace App\Http\Controllers;

use App\Models\HrNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = HrNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(HrNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->markAsRead();
        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllRead()
    {
        HrNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function destroy(HrNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
