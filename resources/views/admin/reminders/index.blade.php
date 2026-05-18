@extends('layouts.dashboard')
@section('title', 'Seller Reminders')
@section('page-title', 'Seller Reminders')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Seller Reminders</h2>
            <p class="text-gray-500 text-sm mt-1">Send notices and reminders to sellers</p>
        </div>
        <a href="{{ route('admin.reminders.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm transition">
            <i class="fas fa-paper-plane"></i> Send New Reminder
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
        <i class="fas fa-check-circle text-green-500"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-xl shadow-sm p-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search title or message..."
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="">All Types</option>
                <option value="info"    {{ request('type') === 'info'    ? 'selected' : '' }}>Info</option>
                <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="error"   {{ request('type') === 'error'   ? 'selected' : '' }}>Error</option>
                <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Success</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        @if(request()->hasAny(['search','type']))
        <a href="{{ route('admin.reminders.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200 transition">
            Clear
        </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($reminders->count())
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Recipients</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Sent By</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($reminders as $reminder)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $reminder->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ Str::limit($reminder->message, 60) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $reminder->type_badge }}">
                            <i class="fas {{ $reminder->type_icon }} text-xs"></i>
                            {{ ucfirst($reminder->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-gray-700">{{ $reminder->recipients_count }}</span>
                        <span class="text-xs text-gray-400 ml-1">
                            @if($reminder->recipient_type === 'all') (All Sellers)
                            @elseif($reminder->recipient_type === 'role') ({{ ucfirst($reminder->recipient_role) }})
                            @else (Specific)
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $reminder->sender->name ?? 'Admin' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $reminder->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.reminders.show', $reminder) }}"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <form action="{{ route('admin.reminders.destroy', $reminder) }}" method="POST"
                                  onsubmit="return confirm('Delete this reminder?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reminders->links() }}
        </div>
        @else
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-bell-slash text-5xl mb-4 block opacity-30"></i>
            <p class="text-lg font-medium text-gray-500">No reminders sent yet</p>
            <p class="text-sm mt-1">Click "Send New Reminder" to get started</p>
        </div>
        @endif
    </div>

</div>
@endsection
