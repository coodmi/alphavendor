<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->with(['category', 'latestReply'])
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
        $categories = TicketCategory::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('vendor.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,normal,high,urgent',
            'category'    => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        $categoryId = $request->category_id
            ?: TicketCategory::resolveIdFromSlug($request->category);

        try {
            $ticket = DB::transaction(function () use ($request, $categoryId) {
                $ticket = Ticket::create([
                    'user_id'     => auth()->id(),
                    'subject'     => $request->subject,
                    'description' => $request->description,
                    'priority'    => $request->priority,
                    'category_id' => $categoryId,
                    'status'      => 'open',
                ]);

                TicketMessage::create([
                    'ticket_id'   => $ticket->id,
                    'user_id'     => auth()->id(),
                    'message'     => $request->description,
                    'is_internal' => false,
                    'is_staff'    => false,
                ]);

                return $ticket;
            });
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('vendor.tickets.create')
                ->withInput()
                ->with('error', 'Could not submit your ticket. Please try again or contact support.');
        }

        try {
            \App\Services\NotificationService::ticketCreated($ticket);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('vendor.tickets.show', $ticket)
            ->with('success', 'Ticket submitted successfully. Our support team will respond soon.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $ticket->load(['category', 'replies.user', 'assignedTo']);

        return view('vendor.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => auth()->id(),
            'message'     => $request->message,
            'is_internal' => false,
            'is_staff'    => false,
        ]);

        $ticket->update([
            'last_activity_at' => now(),
            'status'           => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
        ]);

        try {
            \App\Services\NotificationService::ticketReplied($ticket, auth()->user());
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->back()->with('success', 'Reply sent successfully');
    }

    public function close(Ticket $ticket)
    {
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
