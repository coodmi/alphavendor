<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - AlphaVendor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }

        /* Dashboard Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .sidebar-header .logo-img {
            width: 60px;
            height: auto;
            margin: 0 auto 12px;
            display: block;
        }

        .sidebar-header h2 {
            font-size: 22px;
            margin-bottom: 8px;
            font-weight: 700;
            color: #ffffff;
        }

        .sidebar-header .role-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #0d5c63;
            border-radius: 12px;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section-title {
            padding: 0 20px;
            font-size: 11px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            font-size: 14px;
        }

        .menu-item:hover {
            background: rgba(255,165,0,0.15);
            color: #ffffff;
            border-left-color: #0d5c63;
        }

        .menu-item.active {
            background: rgba(255,165,0,0.2);
            color: #ffffff;
            border-left-color: #0d5c63;
        }

        .menu-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 16px;
        }

        .menu-item .badge {
            margin-left: auto;
            background: #e74c3c;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: all 0.3s;
        }

        /* Header */
        .dashboard-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left h1 {
            font-size: 24px;
            color: #2c3e50;
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 20px;
            color: #7f8c8d;
            transition: color 0.3s;
        }

        .notification-icon:hover {
            color: #2c3e50;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Profile Menu */
        .profile-menu {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .profile-trigger:hover {
            background: #f8f9fa;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3498db;
        }

        .profile-avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .profile-role {
            font-size: 12px;
            color: #7f8c8d;
        }

        .profile-dropdown-icon {
            font-size: 12px;
            color: #7f8c8d;
            transition: transform 0.3s;
        }

        .profile-menu.active .profile-dropdown-icon {
            transform: rotate(180deg);
        }

        /* Dropdown */
        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
        }

        .profile-menu.active .profile-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        .dropdown-header .name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .dropdown-header .email {
            font-size: 12px;
            color: #7f8c8d;
        }

        .dropdown-menu {
            padding: 8px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            font-size: 14px;
        }

        .dropdown-divider {
            height: 1px;
            background: #ecf0f1;
            margin: 8px 0;
        }

        .dropdown-item.logout {
            color: #e74c3c;
        }

        .dropdown-item.logout:hover {
            background: #fee;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: #2c3e50;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .profile-info {
                display: none;
            }
        }

        /* Dashboard Mobile Responsive Styles */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-header h1 {
                font-size: 22px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 15px;
            }

            .content-header h1 {
                font-size: 20px;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-value {
                font-size: 28px;
            }

            .profile-info {
                display: none;
            }

            .profile-dropdown-icon {
                display: none;
            }

            .notification-icon {
                font-size: 18px;
            }

            .header-right {
                gap: 15px;
            }

            /* Make tables scrollable on mobile */
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                min-width: 600px;
            }

            /* Form adjustments */
            .form-row {
                flex-direction: column;
            }

            .form-group {
                width: 100% !important;
            }

            /* Button adjustments */
            .btn {
                width: 100%;
                justify-content: center;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .sidebar-toggle {
                top: 10px;
                left: 10px;
            }

            .main-content {
                padding: 10px;
            }

            .content-header {
                padding: 10px;
            }

            .content-header h1 {
                font-size: 18px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-label {
                font-size: 12px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('home') }}" style="display: inline-block; line-height: 0; text-decoration: none;">
                    <img src="{{ asset('airmarket.png') }}" alt="AlphaVendor Logo" class="logo-img" style="cursor: pointer;">
                </a>
                <span class="role-badge">
                    @if(Auth::user()->role === 'exporter')
                        IMPORTER
                    @else
                        {{ ucfirst(Auth::user()->role) }}
                    @endif
                </span>
            </div>
            <nav class="sidebar-menu">
                @if(Auth::user()->isAdmin())
                    @include('dashboards.partials.admin-sidebar')
                @else
                    @yield('sidebar-menu')
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="dashboard-header">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="notification-icon" id="notificationBell" onclick="toggleNotificationDropdown()" style="cursor: pointer; position: relative;">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge" id="headerNotificationBadge" style="display: none;">0</span>
                    </div>

                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" style="display: none; position: absolute; top: 60px; right: 80px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 350px; max-height: 400px; overflow-y: auto; z-index: 1000;">
                        <div style="padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="margin: 0; color: #2c3e50; font-size: 16px;">Notifications</h4>
                            <a href="#" onclick="event.preventDefault(); markAllHeaderNotificationsRead();" style="color: #667eea; font-size: 13px; text-decoration: none;">Mark all read</a>
                        </div>
                        <div id="headerNotificationList" style="max-height: 300px; overflow-y: auto;">
                            <div style="padding: 30px 20px; text-align: center; color: #7f8c8d;">
                                <i class="fas fa-bell-slash" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px;"></i>
                                <p style="margin: 0; font-size: 14px;">No new notifications</p>
                            </div>
                        </div>
                        <div style="padding: 12px 15px; border-top: 1px solid #eee; text-align: center;">
                            <a href="{{ route('notifications.page') }}" style="color: #667eea; font-size: 13px; text-decoration: none; font-weight: 600;">View All Notifications</a>
                        </div>
                    </div>

                    <!-- Profile Menu -->
                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-trigger" onclick="toggleProfileMenu()">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile" class="profile-avatar">
                            @else
                                <div class="profile-avatar-placeholder">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="profile-info">
                                <span class="profile-name">{{ Auth::user()->name }}</span>
                                <span class="profile-role">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                            <i class="fas fa-chevron-down profile-dropdown-icon"></i>
                        </div>

                        <!-- Dropdown -->
                        <div class="profile-dropdown">
                            <div class="dropdown-header">
                                <div class="name">{{ Auth::user()->name }}</div>
                                <div class="email">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="dropdown-menu">
                                <a href="{{ route('profile.show') }}" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer;">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;"></div>

    <script>
        // Sidebar Scroll Position Management
        const sidebar = document.getElementById('sidebar');
        
        // Restore scroll position on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedScrollPosition = sessionStorage.getItem('sidebarScrollPosition');
            if (savedScrollPosition && sidebar) {
                sidebar.scrollTop = parseInt(savedScrollPosition);
            }
        });
        
        // Save scroll position when clicking any menu item
        if (sidebar) {
            const menuItems = sidebar.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                });
            });
            
            // Also save on scroll (debounced)
            let scrollTimeout;
            sidebar.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    sessionStorage.setItem('sidebarScrollPosition', sidebar.scrollTop);
                }, 100);
            });
        }
        
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('active');
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            if (!profileMenu.contains(event.target)) {
                profileMenu.classList.remove('active');
            }
            
            // Close notification dropdown when clicking outside
            const notificationBell = document.getElementById('notificationBell');
            const notificationDropdown = document.getElementById('notificationDropdown');
            if (notificationDropdown && !notificationBell.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.style.display = 'none';
            }
        });

        // Notification Bell Functions
        let notificationDropdownOpen = false;
        
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            notificationDropdownOpen = !notificationDropdownOpen;
            dropdown.style.display = notificationDropdownOpen ? 'block' : 'none';
            
            if (notificationDropdownOpen) {
                loadHeaderNotifications();
            }
        }
        
        async function loadHeaderNotifications() {
            try {
                const response = await fetch('/notifications?status=unread');
                const data = await response.json();
                const notifications = data.data || [];
                
                const container = document.getElementById('headerNotificationList');
                
                if (notifications.length === 0) {
                    container.innerHTML = `
                        <div style="padding: 30px 20px; text-align: center; color: #7f8c8d;">
                            <i class="fas fa-bell-slash" style="font-size: 32px; opacity: 0.3; margin-bottom: 10px;"></i>
                            <p style="margin: 0; font-size: 14px;">No new notifications</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = notifications.slice(0, 5).map(notif => {
                        const typeIcons = {
                            info: { icon: 'fa-info-circle', color: '#3b82f6' },
                            success: { icon: 'fa-check-circle', color: '#10b981' },
                            warning: { icon: 'fa-exclamation-triangle', color: '#f59e0b' },
                            error: { icon: 'fa-times-circle', color: '#ef4444' }
                        };
                        const style = typeIcons[notif.type] || typeIcons.info;
                        
                        return `
                            <div style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: background 0.2s;" onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background='white'" onclick="markHeaderNotificationRead(${notif.id})">
                                <div style="display: flex; gap: 10px;">
                                    <i class="fas ${style.icon}" style="color: ${style.color}; font-size: 16px; margin-top: 2px;"></i>
                                    <div style="flex: 1;">
                                        <h5 style="margin: 0 0 3px 0; color: #2c3e50; font-size: 13px; font-weight: 600;">${notif.title}</h5>
                                        <p style="margin: 0 0 3px 0; color: #7f8c8d; font-size: 12px; line-height: 1.4;">${notif.message.substring(0, 60)}${notif.message.length > 60 ? '...' : ''}</p>
                                        <span style="color: #95a5a6; font-size: 11px;">${formatNotificationDate(notif.created_at)}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }
        
        async function markHeaderNotificationRead(id) {
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                loadHeaderNotifications();
                updateNotificationBadge();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
        
        async function markAllHeaderNotificationsRead() {
            try {
                await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                loadHeaderNotifications();
                updateNotificationBadge();
                showToast('All notifications marked as read', 'success');
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        }
        
        async function updateNotificationBadge() {
            try {
                const response = await fetch('/notifications/unread-count', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch notification count');
                }
                
                const data = await response.json();
                const count = data.count || 0;
                
                const badge = document.getElementById('headerNotificationBadge');
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error updating notification badge:', error);
                // Silently fail - don't show error to user
            }
        }
        
        function formatNotificationDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000);
            
            if (diff < 60) return 'Just now';
            if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            if (diff < 604800) return Math.floor(diff / 86400) + 'd ago';
            
            return date.toLocaleDateString();
        }
        
        // Update badge on page load and every 30 seconds (only if user is authenticated)
        @auth
        // Wait for page to fully load before making notification request
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(updateNotificationBadge, 500);
            });
        } else {
            setTimeout(updateNotificationBadge, 500);
        }
        setInterval(updateNotificationBadge, 30000);
        @endauth

        // Toast Notification System
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');

            // Toast styling
            toast.style.cssText = `
                background: ${type === 'success' ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'};
                color: white;
                padding: 16px 20px;
                border-radius: 8px;
                margin-bottom: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                gap: 12px;
                animation: slideIn 0.3s ease-out;
                position: relative;
                overflow: hidden;
            `;

            // Icon based on type
            const icon = type === 'success'
                ? '<i class="fas fa-check-circle" style="font-size: 20px;"></i>'
                : '<i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>';

            // Toast content
            toast.innerHTML = `
                ${icon}
                <span style="flex: 1; font-size: 14px;">${message}</span>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 18px; opacity: 0.8; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            `;

            toastContainer.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Show toasts for session messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            #toastContainer {
                left: 20px;
                right: 20px;
                min-width: auto;
            }
        }
    </style>

    <script>
        // Global showSection function for sidebar navigation
        function showSection(section) {
            // For pages that use inline sections (like admin.blade.php)
            const sectionMap = {
                'vendor-payouts': 'vendor-payouts-section',
                'vendor-reviews': 'vendor-reviews-section',
                'commissions': 'commissions-section',
                'home-page': 'home-page-section',
                'offers': 'offers-section',
                'wholesale-page': 'wholesale-page-section',
                'import-page': 'import-page-section',
                'about-page': 'about-page-section',
                'contact-page': 'contact-page-section',
                'transactions': 'transactions-section',
                'payment-gateway': 'payment-gateway-section',
                'offline-payment': 'offline-payment-section',
                'delivery-tracking': 'delivery-tracking-section',
                'warehouse': 'warehouse-section',
                'live-chat': 'live-chat-section'
            };

            const sectionId = sectionMap[section];
            
            // Check if we're on a page with inline sections
            if (sectionId && document.getElementById(sectionId)) {
                // Hide all sections
                document.querySelectorAll('.content-section').forEach(el => {
                    el.style.display = 'none';
                });

                // Remove active class from all menu items
                document.querySelectorAll('.menu-item').forEach(el => {
                    el.classList.remove('active');
                });

                // Show selected section
                const sectionElement = document.getElementById(sectionId);
                if (sectionElement) {
                    sectionElement.style.display = 'block';
                }

                // Activate the corresponding menu item
                const menuItem = document.querySelector(`a[onclick="showSection('${section}')"]`);
                if (menuItem) {
                    menuItem.classList.add('active');
                }
            } else {
                // If section doesn't exist on current page, redirect to admin dashboard with hash
                window.location.href = "{{ route('admin.dashboard') }}#" + section;
            }
        }

        // On page load, check if there's a hash and show that section
        window.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                setTimeout(() => showSection(hash), 100);
            }
        });
    </script>

    @stack('scripts')

    <script>
        // Mobile Sidebar Toggle for Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    if (sidebar.classList.contains('active')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Sidebar stays open - removed auto-close on menu click
        });
    </script>
</body>
</html>
