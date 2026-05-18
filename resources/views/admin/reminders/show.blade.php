@extends('layouts.dashboard')
@section('title', 'Reminder Details')
@section('page-title', 'Reminder Details')
@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reminders.index') }}"
           class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Reminder Details</h2>
    </div>

    {{-- Reminder Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                    {{ $reminder->type === 'warning' ? 'bg-yellow-100' :
                       ($reminder->type === 'error' ? 'bg-red-100' :
                       ($reminder->type === 'success' ? 'bg-green-100' : 'bg-blue-100')) }}">
                    <i class="fas {{ $reminder->type_icon }} text-lg
                        {{ $reminder->type === 'warning' ? 'text-yellow-600' :
                           ($reminder->type === 'error' ? 'text-red-600' :
                           ($reminder->type === 'success' ? 'text-green-600' : 'text-blue-600')) }}"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $reminder->title }}</h3>
                    <p class="text-xs text-gray-400">
                        Sent by <strong>{{ $reminder->sender->name ?? 'Admin' }}</strong>
                        · {{ $reminder->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $reminder->type_badge }}">
                {{ ucfirst($reminder->type) }}
            </span>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $reminder->message }}</div>

        <div class="flex items-center gap-4 text-sm text-gray-500 pt-2 border-t border-gray-100">
            <span><i class="fas fa-users mr-1.5 text-gray-400"></i>
                {{ $reminder->recipients->count() }} recipient(s)
            </span>
            <span>
                @if($reminder->recipient_type === 'all') <i class="fas fa-globe mr-1 text-gray-400"></i> All Sellers
                @elseif($reminder->recipient_type === 'role') <i class="fas fa-user-tag mr-1 text-gray-400"></i> {{ ucfirst($reminder->recipient_role) }}
                @else <i class="fas fa-user mr-1 text-gray-400"></i> Specific Sellers
                @endif
            </span>
        </div>
    </div>

    {{-- Recipients Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Recipients</h3>
            @php
                $readCount = $reminder->recipients->filter(fn($r) => $r->pivot->read_at)->count();
                $total     = $reminder->recipients->count();
            @endphp
            <span class="text-sm text-gray-500">
                <span class="font-semibold text-green-600">{{ $readCount }}</span> / {{ $total }} read
            </span>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Seller</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Read At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($reminder->recipients as $recipient)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $recipient->name }}</p>
                        <p class="text-xs text-gray-400">{{ $recipient->email }}</p>
                    </td>
                    <td class="px-6 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $recipient->role === 'retailer' ? 'bg-teal-100 text-teal-700' :
                               ($recipient->role === 'wholesaler' ? 'bg-blue-100 text-blue-700' :
                               ($recipient->role === 'exporter' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700')) }}">
                            {{ ucfirst($recipient->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        @if($recipient->pivot->read_at)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600">
                                <i class="fas fa-check-circle"></i> Read
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-yellow-600">
                                <i class="fas fa-clock"></i> Unread
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500">
                        {{ $recipient->pivot->read_at ? \Carbon\Carbon::parse($recipient->pivot->read_at)->format('d M Y, h:i A') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
