<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo', 'latestReply']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->latest()->paginate(20);

        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'stats'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'assignedTo', 'replies.user']);
        $admins = User::where('role', 'admin')->get();
        
        return view('admin.tickets.show', compact('ticket', 'admins'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => true,
        ]);

        $ticket->update([
            'last_reply_at' => now(),
            'status' => 'in_progress',
        ]);

        \App\Services\NotificationService::ticketReplied($ticket, auth()->user());

        return redirect()->back()->with('success', 'Reply sent successfully');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,waiting_response,resolved,closed',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $data['resolved_at'] = now();
        }

        if ($request->status === 'closed') {
            $data['closed_at'] = now();
        }

        $ticket->update($data);

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully');
    }

    public function updatePriority(Request $request, Ticket $ticket)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update(['priority' => $request->priority]);

        return redirect()->back()->with('success', 'Priority updated successfully');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully');
    }
}
