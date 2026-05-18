@extends('layouts.dashboard')
@section('title', 'Reminders')
@section('page-title', 'Reminders')

@section('sidebar-menu')
    @php $role = auth()->user()->role; @endphp
    @if($role === 'retailer')       @include('dashboards.partials.retailer-sidebar')
    @elseif($role === 'wholesaler') @include('dashboards.partials.wholesaler-sidebar')
    @elseif(in_array($role, ['exporter', 'importer'])) @include('dashboards.partials.vendor-portal-sidebar')
    @endif
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-bell text-indigo-500 mr-2"></i>Reminders
            </h2>
            <p class="text-gray-500 text-sm mt-1">
                Notices and reminders from Admin
                @if($unreadCount > 0)
                    · <span class="font-semibold text-indigo-600">{{ $unreadCount }} unread</span>
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
        <form action="{{ route('seller.reminders.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
        </form>
        @endif
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Reminder List --}}
    @forelse($reminders as $reminder)
    @php
        $typeColors = [
            'info'    => ['bg' => '#eff6ff', 'border' => '#bfdbfe', 'icon_bg' => '#dbeafe', 'icon_color' => '#2563eb', 'icon' => 'fa-info-circle'],
            'warning' => ['bg' => '#fffbeb', 'border' => '#fde68a', 'icon_bg' => '#fef3c7', 'icon_color' => '#d97706', 'icon' => 'fa-exclamation-triangle'],
            'error'   => ['bg' => '#fef2f2', 'border' => '#fecaca', 'icon_bg' => '#fee2e2', 'icon_color' => '#dc2626', 'icon' => 'fa-times-circle'],
            'success' => ['bg' => '#f0fdf4', 'border' => '#bbf7d0', 'icon_bg' => '#dcfce7', 'icon_color' => '#16a34a', 'icon' => 'fa-check-circle'],
        ];
        $c = $typeColors[$reminder->type] ?? $typeColors['info'];
    @endphp
    <div id="reminder-{{ $reminder->id }}"
         style="background:{{ $reminder->is_read ? '#fff' : $c['bg'] }};border:1.5px solid {{ $reminder->is_read ? '#e5e7eb' : $c['border'] }};border-radius:16px;padding:20px;transition:all .2s;">
        <div class="flex items-start gap-4">
            {{-- Icon --}}
            <div style="width:44px;height:44px;border-radius:50%;background:{{ $c['icon_bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas {{ $c['icon'] }}" style="color:{{ $c['icon_color'] }};font-size:18px;"></i>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <h3 class="font-bold text-gray-800 text-base leading-snug">
                        {{ $reminder->title }}
                        @if(!$reminder->is_read)
                            <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full ml-1.5 align-middle"></span>
                        @endif
                    </h3>
                    <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                        {{ $reminder->created_at->diffForHumans() }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $reminder->message }}</p>

                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fas fa-user-shield"></i>
                        <span>From: <strong class="text-gray-600">{{ $reminder->sender->name ?? 'Admin' }}</strong></span>
                        <span>·</span>
                        <span>{{ $reminder->created_at->format('d M Y, h:i A') }}</span>
                    </div>

                    @if(!$reminder->is_read)
                    <form action="{{ route('seller.reminders.read', $reminder) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-indigo-400 hover:text-indigo-600 text-gray-500 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-check"></i> Mark as Read
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center gap-1 text-xs text-green-600 font-medium">
                        <i class="fas fa-check-double"></i> Read
                        @if($reminder->read_at)
                            · {{ \Carbon\Carbon::parse($reminder->read_at)->format('d M, h:i A') }}
                        @endif
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-20 text-gray-400">
        <i class="fas fa-bell-slash text-6xl mb-4 block opacity-20"></i>
        <p class="text-xl font-medium text-gray-500">No reminders yet</p>
        <p class="text-sm mt-1">Admin notices and reminders will appear here</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($reminders->hasPages())
    <div class="pt-2">
        {{ $reminders->links() }}
    </div>
    @endif

</div>
@endsection
