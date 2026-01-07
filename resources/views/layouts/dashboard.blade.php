<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background: #FFA500;
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
            border-left-color: #FFA500;
        }

        .menu-item.active {
            background: rgba(255,165,0,0.2);
            color: #ffffff;
            border-left-color: #FFA500;
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
    </style>
    @stack('styles')
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('airmarket.png') }}" alt="AlphaVendor Logo" class="logo-img">
                <span class="role-badge">{{ ucfirst(Auth::user()->role) }}</span>
            </div>
            <nav class="sidebar-menu">
                @yield('sidebar-menu')
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
                    <div class="notification-icon">
                        <i class="far fa-bell"></i>
                        <span class="notification-badge">3</span>
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
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    Settings
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
        });

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
    @stack('scripts')
</body>
</html>
