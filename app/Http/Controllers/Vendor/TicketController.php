<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with(['latestReply'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => Ticket::where('user_id', auth()->id())->count(),
            'open' => Ticket::where('user_id', auth()->id())->where('status', 'open')->count(),
            'in_progress' => Ticket::where('user_id', auth()->id())->where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('user_id', auth()->id())->where('status', 'resolved')->count(),
        ];

        return view('vendor.tickets.index', compact('tickets', 'stats'));
    }

    public function create()
    {
        return view('vendor.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,normal,high,urgent',
            'category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'subject'     => $request->subject,
            'description' => $request->description,
            'priority'    => $request->priority,
            'category_id' => $request->category_id ?: null,
            'status'      => 'open',
        ]);

        \App\Services\NotificationService::ticketCreated($ticket);

        return redirect()->route('vendor.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket)
    {
        // Ensure user can only view their own tickets
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $ticket->load(['replies.user', 'assignedTo']);

        return view('vendor.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        // Ensure user can only reply to their own tickets
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        \App\Models\TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'message'     => $request->message,
            'is_internal' => false,
        ]);

        $ticket->update([
            'last_activity_at' => now(),
            'status'           => 'in_progress',
        ]);

        \App\Services\NotificationService::ticketReplied($ticket, auth()->user());

        return redirect()->back()->with('success', 'Reply sent successfully');
    }

    public function close(Ticket $ticket)
    {
        // Ensure user can only close their own tickets
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ticket closed successfully');
    }
}
