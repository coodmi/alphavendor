@extends('layouts.dashboard')
@section('title', 'Live Chat & Support')
@section('page-title', 'Live Chat')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-7xl mx-auto">

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg">{{ session('success') }}</div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        <button onclick="switchTab('inbox')" id="tab-inbox"
            class="chat-tab px-5 py-2.5 text-sm font-semibold border-b-2 border-indigo-600 text-indigo-600 -mb-px">
            💬 Inbox
            @if($unreadCount > 0)
                <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $unreadCount }}</span>
            @endif
        </button>
        <button onclick="switchTab('faqs')" id="tab-faqs"
            class="chat-tab px-5 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px">
            ❓ Quick Replies / FAQ
        </button>
        <button onclick="switchTab('settings')" id="tab-settings"
            class="chat-tab px-5 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px">
            ⚙️ Settings
        </button>
    </div>

    {{-- ===== INBOX TAB ===== --}}
    <div id="panel-inbox">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" style="height:600px;">

            {{-- Conversation List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-y-auto">
                <div class="px-4 py-3 border-b border-gray-100 font-semibold text-gray-700 text-sm">
                    All Conversations ({{ $conversations->total() }})
                </div>
                @forelse($conversations as $conv)
                <div onclick="loadConversation({{ $conv->id }})"
                    id="conv-item-{{ $conv->id }}"
                    class="conv-item px-4 py-3 border-b border-gray-50 cursor-pointer hover:bg-indigo-50 transition">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-sm text-gray-800">
                            {{ $conv->user?->name ?? 'Guest' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $conv->last_message_at?->diffForHumans() }}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">{{ $conv->latestMessage?->message ?? 'No messages' }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $conv->status === 'open' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $conv->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $conv->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $conv->status === 'closed' ? 'bg-gray-100 text-gray-500' : '' }}">
                        {{ ucfirst(str_replace('_',' ',$conv->status)) }}
                    </span>
                </div>
                @empty
                <div class="px-4 py-12 text-center text-gray-400">
                    <i class="fas fa-comments text-4xl mb-3 block opacity-30"></i>
                    No conversations yet
                </div>
                @endforelse
            </div>

            {{-- Chat Panel --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div id="chat-placeholder" class="flex-1 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-comments text-5xl mb-3 block opacity-20"></i>
                        <p>Select a conversation to view messages</p>
                    </div>
                </div>

                <div id="chat-panel" class="hidden flex-col h-full">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800" id="panel-user-name">—</p>
                            <p class="text-xs text-gray-400" id="panel-conv-subject">—</p>
                        </div>
                        <span id="panel-status-badge" class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Open</span>
                    </div>

                    <div id="admin-chat-body" class="flex-1 overflow-y-auto p-5 space-y-3 bg-gray-50" style="max-height:400px;"></div>

                    <div class="px-4 py-3 border-t border-gray-100 flex gap-3">
                        <input type="text" id="admin-reply-input" placeholder="Type your reply..."
                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                        <button onclick="sendAdminReply()"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FAQ TAB ===== --}}
    <div id="panel-faqs" class="hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Add FAQ --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Add Quick Reply / FAQ</h3>
                <form action="{{ route('admin.chatbot.faqs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Question / Keyword</label>
                        <input type="text" name="question" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none"
                            placeholder="e.g. How do I track my order?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Answer</label>
                        <textarea name="answer" rows="4" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 outline-none resize-none"
                            placeholder="The auto-reply message..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" min="0"
                            class="w-32 px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none">
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                        Add FAQ
                    </button>
                </form>
            </div>

            {{-- FAQ List --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Existing FAQs ({{ $faqs->count() }})</h3>
                @forelse($faqs as $faq)
                <div class="border border-gray-100 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-gray-800">{{ $faq->question }}</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <button onclick="editFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}', '{{ addslashes($faq->answer) }}', {{ $faq->sort_order }})"
                                class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-medium transition">
                                Edit
                            </button>
                            <form action="{{ route('admin.chatbot.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Delete this FAQ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded text-xs font-medium transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center py-8">No FAQs yet. Add one to get started.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== SETTINGS TAB ===== --}}
    <div id="panel-settings" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
            <h3 class="font-bold text-gray-800 mb-5">Chat Widget Settings</h3>
            <form action="{{ route('admin.chatbot.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="chatbot_enabled" id="chatbot_enabled" value="1"
                        {{ ($settings['chatbot_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                        class="w-4 h-4 accent-indigo-600">
                    <label for="chatbot_enabled" class="text-sm font-medium text-gray-700">Enable Chat Widget</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Chat Name</label>
                    <input type="text" name="chatbot_name" value="{{ $settings['chatbot_name'] ?? 'AlphaVendor Support' }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Welcome Message</label>
                    <textarea name="chatbot_welcome_message" rows="3"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-400 resize-none">{{ $settings['chatbot_welcome_message'] ?? "Hello! 👋 Welcome to AlphaVendor. How can I help you today?" }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Input Placeholder</label>
                    <input type="text" name="chatbot_placeholder" value="{{ $settings['chatbot_placeholder'] ?? 'Type your message...' }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Theme Color</label>
                    <input type="color" name="chatbot_theme_color" value="{{ $settings['chatbot_theme_color'] ?? '#0d5c63' }}"
                        class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                    Save Settings
                </button>
            </form>
        </div>
    </div>

</div>

{{-- Edit FAQ Modal --}}
<div id="editFaqModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <h3 class="font-bold text-gray-800 mb-4">Edit FAQ</h3>
        <form id="editFaqForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Question</label>
                <input type="text" name="question" id="edit-question" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Answer</label>
                <textarea name="answer" id="edit-answer" rows="4" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-400 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="edit-sort" min="0"
                    class="w-32 px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('editFaqModal').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentConvId = null;
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// Tab switching
function switchTab(tab) {
    ['inbox','faqs','settings'].forEach(t => {
        document.getElementById('panel-' + t).classList.toggle('hidden', t !== tab);
        const btn = document.getElementById('tab-' + t);
        btn.style.borderColor = t === tab ? '#4f46e5' : 'transparent';
        btn.style.color = t === tab ? '#4f46e5' : '#6b7280';
    });
}

// Load conversation
function loadConversation(id) {
    currentConvId = id;
    document.querySelectorAll('.conv-item').forEach(el => el.classList.remove('bg-indigo-100'));
    document.getElementById('conv-item-' + id)?.classList.add('bg-indigo-100');

    fetch(`/admin/chatbot/conversations/${id}`, { headers:{'Accept':'application/json'} })
    .then(r=>r.json()).then(d=>{
        document.getElementById('chat-placeholder').classList.add('hidden');
        const panel = document.getElementById('chat-panel');
        panel.classList.remove('hidden');
        panel.classList.add('flex');

        document.getElementById('panel-user-name').textContent = d.conversation.user?.name ?? 'Guest';
        document.getElementById('panel-conv-subject').textContent = d.conversation.subject ?? '';
        document.getElementById('panel-status-badge').textContent = d.conversation.status;

        const body = document.getElementById('admin-chat-body');
        body.innerHTML = '';
        d.messages.forEach(m => appendAdminMsg(m.message, m.is_admin, m.created_at));
        body.scrollTop = body.scrollHeight;
    });
}

function appendAdminMsg(text, isAdmin, time) {
    const body = document.getElementById('admin-chat-body');
    const div = document.createElement('div');
    div.className = 'flex gap-3 ' + (isAdmin ? 'flex-row-reverse' : '');
    const t = time ? new Date(time).toLocaleTimeString('en-GB',{hour:'2-digit',minute:'2-digit'}) : '';
    div.innerHTML = `
        <div style="width:34px;height:34px;border-radius:50%;background:${isAdmin?'#4f46e5':'#e5e7eb'};color:${isAdmin?'#fff':'#374151'};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:13px;">
            <i class="fas fa-${isAdmin?'shield-alt':'user'}"></i>
        </div>
        <div>
            <div style="background:${isAdmin?'#4f46e5':'#fff'};color:${isAdmin?'#fff':'#1f2937'};padding:10px 14px;border-radius:12px;font-size:14px;line-height:1.5;box-shadow:0 1px 3px rgba(0,0,0,.08);">${escHtml(text)}</div>
            <div style="font-size:11px;color:#9ca3af;margin-top:3px;${isAdmin?'text-align:right':''}">${t}</div>
        </div>`;
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
}

function sendAdminReply() {
    if (!currentConvId) return;
    const input = document.getElementById('admin-reply-input');
    const text = input.value.trim();
    if (!text) return;
    input.value = '';

    fetch(`/admin/chatbot/conversations/${currentConvId}/reply`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body: JSON.stringify({ message: text })
    }).then(r=>r.json()).then(d=>{
        if (d.success) appendAdminMsg(d.message.message, true, d.message.created_at);
    });
}

document.getElementById('admin-reply-input')?.addEventListener('keypress', e => {
    if (e.key === 'Enter') sendAdminReply();
});

// Edit FAQ
function editFaq(id, question, answer, sort) {
    document.getElementById('editFaqForm').action = `/admin/chatbot/faqs/${id}`;
    document.getElementById('edit-question').value = question;
    document.getElementById('edit-answer').value = answer;
    document.getElementById('edit-sort').value = sort;
    document.getElementById('editFaqModal').classList.remove('hidden');
}

function escHtml(t) {
    return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Auto-refresh inbox every 10s
setInterval(() => {
    if (currentConvId) loadConversation(currentConvId);
}, 10000);
</script>
@endsection
