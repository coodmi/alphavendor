<?php

namespace App\Http\Controllers;

use App\Models\AdminReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerReminderController extends Controller
{
    /**
     * Seller's reminder inbox.
     */
    public function index()
    {
        $user = auth()->user();

        $reminders = AdminReminder::forUser($user->id)
            ->with('sender')
            ->latest()
            ->paginate(20);

        $readMap = DB::table('admin_reminder_recipients')
            ->where('user_id', $user->id)
            ->whereIn('reminder_id', $reminders->pluck('id'))
            ->pluck('read_at', 'reminder_id');

        $reminders->getCollection()->transform(function ($reminder) use ($readMap) {
            $readAt = $readMap[$reminder->id] ?? null;
            $reminder->is_read = $readAt !== null;
            $reminder->read_at = $readAt;

            return $reminder;
        });

        $unreadCount = AdminReminder::unreadForUser($user->id)->count();

        return view('seller.reminders.index', compact('reminders', 'unreadCount'));
    }

    /**
     * Mark a single reminder as read.
     */
    public function markRead(AdminReminder $reminder)
    {
        $user = auth()->user();

        $updated = DB::table('admin_reminder_recipients')
            ->where('reminder_id', $reminder->id)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($updated === 0 && ! $reminder->recipients()->where('users.id', $user->id)->exists()) {
            abort(403);
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * Mark all reminders as read for this seller.
     */
    public function markAllRead()
    {
        $user = auth()->user();

        DB::table('admin_reminder_recipients')
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'All reminders marked as read.');
    }

    /**
     * Unread reminder count (JSON — for badge).
     */
    public function unreadCount()
    {
        $count = AdminReminder::unreadForUser(auth()->id())->count();

        return response()->json(['count' => $count]);
    }
}
