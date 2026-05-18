<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReminder;
use App\Models\User;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * List all sent reminders.
     */
    public function index(Request $request)
    {
        $query = AdminReminder::with('sender')
            ->withCount('recipients')
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $reminders = $query->paginate(20)->withQueryString();

        return view('admin.reminders.index', compact('reminders'));
    }

    /**
     * Show compose form.
     */
    public function create()
    {
        // Sellers for the specific-user dropdown
        $sellers = User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        return view('admin.reminders.create', compact('sellers'));
    }

    /**
     * Send the reminder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'message'        => 'required|string|max:5000',
            'type'           => 'required|in:info,warning,error,success',
            'recipient_type' => 'required|in:all,role,specific',
            'recipient_role' => 'required_if:recipient_type,role|nullable|in:retailer,wholesaler,exporter,importer',
            'recipient_ids'  => 'required_if:recipient_type,specific|nullable|array',
            'recipient_ids.*'=> 'exists:users,id',
        ]);

        // Resolve target user IDs
        $userIds = match ($validated['recipient_type']) {
            'all' => User::whereIn('role', ['retailer', 'wholesaler', 'exporter', 'importer'])->pluck('id')->toArray(),
            'role' => match ($validated['recipient_role']) {
                // Live importer dashboard users often have role "exporter"
                'importer', 'exporter' => User::whereIn('role', ['importer', 'exporter'])->pluck('id')->toArray(),
                default => User::where('role', $validated['recipient_role'])->pluck('id')->toArray(),
            },
            'specific' => $validated['recipient_ids'],
        };

        if (empty($userIds)) {
            return back()->with('error', 'No recipients found for the selected criteria.');
        }

        // Create reminder record
        $reminder = AdminReminder::create([
            'sender_id'      => auth()->id(),
            'title'          => $validated['title'],
            'message'        => $validated['message'],
            'type'           => $validated['type'],
            'recipient_type' => $validated['recipient_type'],
            'recipient_role' => $validated['recipient_role'] ?? null,
        ]);

        // Attach recipients (pivot — no timestamps on this table)
        $pivotRows = collect($userIds)->mapWithKeys(fn ($id) => [(int) $id => ['read_at' => null]])->all();
        $reminder->recipients()->attach($pivotRows);

        // Also push into the existing Notification system so the bell badge lights up
        foreach ($userIds as $uid) {
            try {
                \App\Models\Notification::create([
                    'user_id' => $uid,
                    'type'    => $validated['type'],
                    'title'   => '📌 ' . $validated['title'],
                    'message' => $validated['message'],
                    'data'    => ['url' => '/seller/reminders', 'reminder_id' => $reminder->id],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Reminder bell notification failed for user ' . $uid . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder sent to ' . count($userIds) . ' seller(s) successfully!');
    }

    /**
     * Show a single reminder with recipient read status.
     */
    public function show(AdminReminder $reminder)
    {
        $reminder->load(['sender', 'recipients']);
        return view('admin.reminders.show', compact('reminder'));
    }

    /**
     * Delete a reminder.
     */
    public function destroy(AdminReminder $reminder)
    {
        $reminder->recipients()->detach();
        $reminder->delete();

        return redirect()->route('admin.reminders.index')
            ->with('success', 'Reminder deleted.');
    }
}
