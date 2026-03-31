<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'unread']);
        $query = $request->user()->notifications()->latest();

        if ($request->filled('q')) {
            $query->where('data', 'like', '%'.$request->input('q').'%');
        }

        if ($request->boolean('unread')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(20)->withQueryString();

        return view('notifications.index', compact('notifications', 'filters'));
    }

    public function markAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')->with('status', 'Notifications marked as read.');
    }
}
