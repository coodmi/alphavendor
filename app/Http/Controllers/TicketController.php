<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Get all tickets with filtering
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = SupportTicket::with(['user', 'category', 'assignedTo', 'latestMessage']);

        // Role-based filtering
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        } else {
            // Admin filters
            if ($request->has('assigned_to') && $request->assigned_to) {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Category filter
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($tickets);
    }

    /**
     * Create new ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        // Create first message
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['description'],
            'is_staff' => Auth::user()->role === 'admin',
        ]);

        // Notify admins about new ticket
        \App\Services\NotificationService::ticketCreated($ticket);

        return response()->json([
            'success' => true,
            'ticket' => $ticket->load(['category', 'user'])
        ]);
    }

    /**
     * Get ticket details with messages
     */
    public function show($id)
    {
        $user = Auth::user();
        $ticket = SupportTicket::with(['user', 'category', 'assignedTo'])
            ->findOrFail($id);

        // Authorization check
        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $ticket->messages()
            ->with('user')
            ->where(function($query) use ($user) {
                if ($user->role !== 'admin') {
                    $query->where('is_internal', false);
                }
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        $ticket->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'ticket' => $ticket,
            'messages' => $messages
        ]);
    }

    /**
     * Add message to ticket
     */
    public function addMessage(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $ticket = SupportTicket::findOrFail($id);

        // Authorization check
        if ($user->role !== 'admin' && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'attachments' => $attachmentPaths,
            'is_internal' => $validated['is_internal'] ?? false,
            'is_staff' => $user->role === 'admin',
        ]);

        // Auto-assign to admin if first staff response
        if ($user->role === 'admin' && !$ticket->assigned_to) {
            $ticket->update([
                'assigned_to' => $user->id,
                'status' => 'in_progress'
            ]);
        }

        // Send notification
        \App\Services\NotificationService::ticketReplied($ticket, $user);

        return response()->json([
            'success' => true,
            'message' => $message->load('user')
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,pending_customer,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Only admins can update status
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved' && !$ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        }

        if ($validated['status'] === 'closed' && !$ticket->closed_at) {
            $updateData['closed_at'] = now();
        }

        $ticket->update($updateData);

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    /**
     * Assign ticket to staff
     */
    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Only admins can assign
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ticket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress'
        ]);

        return response()->json([
            'success' => true,
            'ticket' => $ticket->load('assignedTo')
        ]);
    }

    /**
     * Rate ticket (satisfaction)
     */
    public function rate(Request $request, $id)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        // Only ticket owner can rate
        if ($ticket->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ticket->update([
            'satisfaction_rating' => $validated['rating'],
            'satisfaction_comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!'
        ]);
    }

    /**
     * Get categories
     */
    public function categories()
    {
        $categories = TicketCategory::active()->get();
        return response()->json($categories);
    }

    /**
     * Get ticket statistics
     */
    public function stats()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $stats = [
                'total' => SupportTicket::count(),
                'open' => SupportTicket::open()->count(),
                'in_progress' => SupportTicket::inProgress()->count(),
                'resolved_today' => SupportTicket::resolved()
                    ->whereDate('resolved_at', today())->count(),
                'avg_response_time' => $this->calculateAvgResponseTime(),
                'my_assigned' => SupportTicket::assignedTo($user->id)->count(),
                'high_priority' => SupportTicket::byPriority('high')->count(),
                'urgent' => SupportTicket::byPriority('urgent')->count(),
            ];
        } else {
            $stats = [
                'total' => SupportTicket::where('user_id', $user->id)->count(),
                'open' => SupportTicket::where('user_id', $user->id)->open()->count(),
                'resolved' => SupportTicket::where('user_id', $user->id)->resolved()->count(),
            ];
        }

        return response()->json($stats);
    }

    private function calculateAvgResponseTime()
    {
        $tickets = SupportTicket::whereNotNull('first_response_at')->get();
        
        if ($tickets->isEmpty()) {
            return 0;
        }

        $totalSeconds = 0;
        foreach ($tickets as $ticket) {
            $totalSeconds += $ticket->created_at->diffInSeconds($ticket->first_response_at);
        }

        return round($totalSeconds / $tickets->count() / 60, 1); // Return in minutes
    }
}
