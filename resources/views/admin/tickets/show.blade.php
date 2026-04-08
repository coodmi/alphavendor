@extends('layouts.dashboard')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $ticket->ticket_number }}</h2>
                        <p class="text-gray-600 mt-1">{{ $ticket->subject }}</p>
                    </div>
                    <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Tickets
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Ticket Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">User</label>
                            <p class="text-gray-900">{{ $ticket->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $ticket->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Category</label>
                            <p class="text-gray-900">{{ ucfirst($ticket->category) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Created</label>
                            <p class="text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-4">
                        <!-- Status -->
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Status</label>
                            <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="waiting_response" {{ $ticket->status === 'waiting_response' ? 'selected' : '' }}>Waiting Response</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </form>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Priority</label>
                            <form action="{{ route('admin.tickets.update-priority', $ticket) }}" method="POST" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <select name="priority" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </form>
                        </div>

                        <!-- Assign -->
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Assign To</label>
                            <form action="{{ route('admin.tickets.assign', $ticket) }}" method="POST" class="mt-1">
                                @csrf
                                @method('PATCH')
                                <select name="assigned_to" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Unassigned</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Original Message -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Original Message</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Replies -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">Conversation</h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                @forelse($ticket->replies as $reply)
                    <div class="flex {{ $reply->is_admin_reply ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-2xl {{ $reply->is_admin_reply ? 'bg-blue-50' : 'bg-gray-50' }} p-4 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-semibold text-sm">{{ $reply->user->name }}</span>
                                @if($reply->is_admin_reply)
                                    <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">Admin</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">No replies yet</p>
                @endforelse
            </div>
        </div>

        <!-- Reply Form -->
        @if($ticket->status !== 'closed')
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800">Add Reply</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Type your reply here..."></textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
