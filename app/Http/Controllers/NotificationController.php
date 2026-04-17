<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        return response()->json($notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', $request->user()->id)
            ->findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json(['message' => 'Marked as read']);
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['message' => 'All marked as read']);
    }

    public function destroy(Request $request, $id)
    {
        Notification::where('user_id', $request->user()->id)
            ->findOrFail($id)
            ->delete();
        return response()->json(['message' => 'Notification deleted']);
    }
}