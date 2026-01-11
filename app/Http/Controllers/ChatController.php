<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Get all conversations for authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin sees all conversations
            $conversations = ChatConversation::with(['user', 'latestMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();
        } else {
            // Users see only their conversations
            $conversations = ChatConversation::where('user_id', $user->id)
                ->with(['admin', 'latestMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();
        }

        return response()->json($conversations);
    }

    /**
     * Create a new conversation.
     */
    public function storeConversation(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'nullable|in:low,normal,high,urgent',
        ]);

        $conversation = ChatConversation::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'] ?? 'normal',
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        // Create first message
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_admin' => Auth::user()->role === 'admin',
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation->load(['user', 'latestMessage'])
        ]);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function getMessages($conversationId)
    {
        $user = Auth::user();
        $conversation = ChatConversation::findOrFail($conversationId);

        // Check authorization
        if ($user->role !== 'admin' && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'conversation' => $conversation->load(['user', 'admin']),
            'messages' => $messages
        ]);
    }

    /**
     * Send a message in a conversation.
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $conversation = ChatConversation::findOrFail($conversationId);

        // Check authorization
        if ($user->role !== 'admin' && $conversation->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'attachment_path' => $attachmentPath,
            'is_admin' => $user->role === 'admin',
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'status' => $user->role === 'admin' ? 'in_progress' : $conversation->status,
        ]);

        // Assign admin if not assigned yet
        if ($user->role === 'admin' && !$conversation->admin_id) {
            $conversation->update(['admin_id' => $user->id]);
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('user')
        ]);
    }

    /**
     * Update conversation status.
     */
    public function updateStatus(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);

        // Only admins can update status
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $conversation->update([
            'status' => $validated['status'],
            'admin_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation
        ]);
    }

    /**
     * Get unread conversations count.
     */
    public function unreadCount()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Count conversations with unread messages from users
            $count = ChatConversation::whereHas('messages', function($query) {
                $query->whereNull('read_at')
                      ->where('is_admin', false);
            })->count();
        } else {
            // Count conversations with unread messages from admin
            $count = ChatConversation::where('user_id', $user->id)
                ->whereHas('messages', function($query) use ($user) {
                    $query->whereNull('read_at')
                          ->where('user_id', '!=', $user->id);
                })->count();
        }

        return response()->json(['count' => $count]);
    }
}
