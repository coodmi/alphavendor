<?php

namespace App\Http\Controllers;

use App\Models\AdminReminder;
use Illuminate\Http\Request;

class SellerReminderController extends Controller
{
    /**
     * Seller's reminder inbox.
     */
    public function index()
    {
        $user = auth()->user();

        $reminders = AdminReminder::whereHas('recipients', fn($q) => $q->where('user_id', $user->id))
            ->with('sender')
            ->latest()
            ->paginate(20);

        // Attach read status for this user
        $reminders->getCollection()->transform(function ($reminder) use ($user) {
            $pivot = $reminder->recipients()
                ->wherePivot('user_id', $user->id)
                ->first()?->pivot;
            $reminder->is_read   = $pivot && $pivot->read_at !== null;
            $reminder->read_at   = $pivot?->read_at;
            return $reminder;
        });

        $unreadCount = AdminReminder::whereHas('recipients', fn($q) =>
            $q->where('user_id', $user->id)->whereNull('admin_reminder_recipients.read_at')
        )->count();

        return view('seller.reminders.index', compact('reminders', 'unreadCount'));
    }

    /**
     * Mark a single reminder as read.
     */
    public function markRead(AdminReminder $reminder)
    {
        $user = auth()->user();

        // Verify this user is a recipient
        $exists = $reminder->recipients()->wherePivot('user_id', $user->id)->exists();
        if (!$exists) abort(403);

        $reminder->recipients()->updateExistingPivot($user->id, ['read_at' => now()]);

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

        \DB::table('admin_reminder_recipients')
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
        $count = AdminReminder::whereHas('recipients', fn($q) =>
            $q->where('user_id', auth()->id())->whereNull('admin_reminder_recipients.read_at')
        )->count();

        return response()->json(['count' => $count]);
    }
}
