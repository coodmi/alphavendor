<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use App\Models\ChatFaq;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        $settings = ChatbotSetting::getAllSettings();
        $faqs = ChatFaq::orderBy('sort_order')->get();
        $conversations = ChatConversation::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);
        $unreadCount = ChatConversation::whereHas('messages', function($q) {
            $q->whereNull('read_at')->where('is_admin', false);
        })->count();

        return view('admin.chatbot.index', compact('settings', 'faqs', 'conversations', 'unreadCount'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'chatbot_enabled'          => 'nullable|boolean',
            'chatbot_name'             => 'required|string|max:100',
            'chatbot_welcome_message'  => 'required|string|max:500',
            'chatbot_placeholder'      => 'required|string|max:100',
            'chatbot_theme_color'      => 'required|string|max:7',
        ]);

        $validated['chatbot_enabled'] = $request->has('chatbot_enabled') ? '1' : '0';

        foreach ($validated as $key => $value) {
            ChatbotSetting::updateSetting($key, $value);
        }

        return redirect()->route('admin.chatbot.index')->with('success', 'Settings updated!');
    }

    // FAQ CRUD
    public function storeFaq(Request $request)
    {
        $request->validate([
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        ChatFaq::create([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'is_active'  => true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'FAQ added!');
    }

    public function updateFaq(Request $request, ChatFaq $faq)
    {
        $request->validate([
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
        ]);

        $faq->update([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'is_active'  => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->back()->with('success', 'FAQ updated!');
    }

    public function destroyFaq(ChatFaq $faq)
    {
        $faq->delete();
        return redirect()->back()->with('success', 'FAQ deleted!');
    }

    // Conversation inbox
    public function conversation(ChatConversation $conversation)
    {
        $conversation->load(['user', 'messages.user']);

        // Mark user messages as read
        $conversation->messages()
            ->where('is_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'conversation' => $conversation,
            'messages'     => $conversation->messages,
        ]);
    }

    public function reply(Request $request, ChatConversation $conversation)
    {
        $request->validate(['message' => 'required|string']);

        $msg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id'         => auth()->id(),
            'message'         => $request->message,
            'is_admin'        => true,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'admin_id'        => auth()->id(),
            'status'          => 'in_progress',
        ]);

        return response()->json(['success' => true, 'message' => $msg->load('user')]);
    }
}
