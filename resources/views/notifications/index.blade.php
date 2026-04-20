@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('sidebar-menu')
    @php $role = auth()->user()->role; @endphp
    @if($role === 'admin') @include('dashboards.partials.admin-sidebar')
    @elseif($role === 'retailer') @include('dashboards.partials.retailer-sidebar')
    @elseif($role === 'wholesaler') @include('dashboards.partials.wholesaler-sidebar')
    @elseif($role === 'exporter') @include('dashboards.partials.exporter-sidebar')
    @elseif($role === 'importer') @include('dashboards.partials.importer-sidebar')
    @else @include('dashboards.partials.user-sidebar')
    @endif
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
            <p class="text-gray-500 text-sm mt-1" id="unreadSummary">Loading...</p>
        </div>
        <button onclick="markAllRead()"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
            <i class="fas fa-check-double"></i> Mark All Read
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-5 border-b border-gray-200">
        <button onclick="filterNotifications('all')" id="tab-all"
            class="notif-tab px-4 py-2 text-sm font-semibold border-b-2 border-indigo-600 text-indigo-600 -mb-px">All</button>
        <button onclick="filterNotifications('unread')" id="tab-unread"
            class="notif-tab px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 -mb-px">Unread</button>
    </div>

    <!-- Notification List -->
    <div id="notifList" class="space-y-3">
        <div class="text-center py-12 text-gray-400">
            <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
            Loading notifications...
        </div>
    </div>

    <!-- Load More -->
    <div id="loadMoreWrap" class="hidden text-center mt-6">
        <button onclick="loadMore()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
            Load More
        </button>
    </div>
</div>

<script>
let currentFilter = 'all';
let currentPage = 1;
let totalPages = 1;

const typeConfig = {
    info:    { icon: 'fa-info-circle',         bg: '#eff6ff', border: '#bfdbfe', color: '#2563eb' },
    success: { icon: 'fa-check-circle',        bg: '#f0fdf4', border: '#bbf7d0', color: '#16a34a' },
    warning: { icon: 'fa-exclamation-triangle',bg: '#fffbeb', border: '#fde68a', color: '#d97706' },
    error:   { icon: 'fa-times-circle',        bg: '#fef2f2', border: '#fecaca', color: '#dc2626' },
};

async function loadNotifications(page = 1) {
    const params = new URLSearchParams({ page });
    if (currentFilter === 'unread') params.set('status', 'unread');

    const res = await fetch(`/notifications?${params}`);
    const data = await res.json();
    const notifications = data.data || [];
    totalPages = data.last_page || 1;
    currentPage = data.current_page || 1;

    const unreadCount = notifications.filter(n => !n.read_at).length;
    document.getElementById('unreadSummary').textContent =
        data.total + ' total · ' + (data.total - (data.total - unreadCount)) + ' unread';

    const list = document.getElementById('notifList');

    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-bell-slash text-5xl mb-4 block opacity-30"></i>
                <p class="text-lg font-medium">No notifications</p>
                <p class="text-sm mt-1">You're all caught up!</p>
            </div>`;
        document.getElementById('loadMoreWrap').classList.add('hidden');
        return;
    }

    const html = notifications.map(n => renderNotif(n)).join('');
    if (page === 1) list.innerHTML = html;
    else list.insertAdjacentHTML('beforeend', html);

    document.getElementById('loadMoreWrap').classList.toggle('hidden', currentPage >= totalPages);
}

function renderNotif(n) {
    const cfg = typeConfig[n.type] || typeConfig.info;
    const isUnread = !n.read_at;
    const time = new Date(n.created_at).toLocaleString('en-GB', {day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
    const url = n.data?.url || null;

    return `
    <div id="notif-${n.id}" onclick="handleClick(${n.id}, '${url || ''}')"
        style="background:${isUnread ? cfg.bg : '#fff'};border:1px solid ${isUnread ? cfg.border : '#e5e7eb'};border-radius:12px;padding:16px 18px;cursor:pointer;transition:all .2s;display:flex;gap:14px;align-items:flex-start;"
        onmouseenter="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'"
        onmouseleave="this.style.boxShadow='none'">
        <div style="width:40px;height:40px;border-radius:50%;background:${cfg.color}20;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas ${cfg.icon}" style="color:${cfg.color};font-size:16px;"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                <p style="font-weight:${isUnread ? '700' : '600'};color:#1e293b;font-size:14px;margin:0;">${n.title}</p>
                ${isUnread ? '<span style="width:8px;height:8px;background:#6366f1;border-radius:50%;flex-shrink:0;margin-top:4px;"></span>' : ''}
            </div>
            <p style="color:#64748b;font-size:13px;margin:4px 0 6px;line-height:1.5;">${n.message}</p>
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#94a3b8;font-size:12px;"><i class="far fa-clock" style="margin-right:4px;"></i>${time}</span>
                <button onclick="event.stopPropagation();deleteNotif(${n.id})"
                    style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:12px;padding:2px 6px;border-radius:4px;"
                    onmouseenter="this.style.color='#ef4444'" onmouseleave="this.style.color='#94a3b8'">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>`;
}

async function handleClick(id, url) {
    await fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    const el = document.getElementById('notif-' + id);
    if (el) el.style.background = '#fff';
    if (url) window.location.href = url;
    updateBadge();
}

async function markAllRead() {
    await fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    loadNotifications(1);
    updateBadge();
}

async function deleteNotif(id) {
    if (!confirm('Delete this notification?')) return;
    await fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    document.getElementById('notif-' + id)?.remove();
    updateBadge();
}

function filterNotifications(filter) {
    currentFilter = filter;
    currentPage = 1;
    document.querySelectorAll('.notif-tab').forEach(t => {
        t.style.borderColor = 'transparent';
        t.style.color = '#6b7280';
    });
    const active = document.getElementById('tab-' + filter);
    active.style.borderColor = '#4f46e5';
    active.style.color = '#4f46e5';
    loadNotifications(1);
}

function loadMore() { loadNotifications(currentPage + 1); }

async function updateBadge() {
    const res = await fetch('/notifications/unread-count');
    const data = await res.json();
    const badge = document.getElementById('headerNotificationBadge');
    if (badge) {
        badge.textContent = data.count;
        badge.style.display = data.count > 0 ? 'flex' : 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => loadNotifications(1));
</script>
@endsection
