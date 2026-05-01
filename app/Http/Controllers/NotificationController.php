<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->appNotifications();

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['count' => 0]);
            }
            $count = Auth::user()->appNotifications()->unread()->count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            \Log::error('Notification count error: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }

    /**
     * Create a new notification.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type'    => 'required|in:info,success,warning,error',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'data'    => 'nullable|array',
        ]);

        $notification = Notification::create($validated);

        return response()->json(['success' => true, 'notification' => $notification]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->appNotifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->appNotifications()->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        $notification = Auth::user()->appNotifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete all read notifications.
     */
    public function deleteAllRead()
    {
        Auth::user()->appNotifications()->read()->delete();

        return response()->json(['success' => true]);
    }
}
