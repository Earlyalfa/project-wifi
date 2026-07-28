<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id'       => $n->id,
                    'message'  => $n->message,
                    'icon'     => $n->icon ?? 'bell',
                    'color'    => $n->color ?? 'cyan',
                    'url'      => $n->url ?? null,
                    'is_read'  => $n->is_read,
                    'time_ago' => $n->created_at->diffForHumans(),
                ];
            });

        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}

