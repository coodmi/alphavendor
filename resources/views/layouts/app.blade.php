<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AlphaVendor - Multi Vendor Marketplace')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('css/retail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wholesale.css') }}">
    <link rel="stylesheet" href="{{ asset('css/export.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    {{-- <h1><span class="logo-icon">R</span></h1> --}}
                    <img src="{{ asset('/alphainno.png') }}" alt="AlphaVendor Logo" style="height: 40px;">
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="I am looking for...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                <div class="header-actions">
                    <a href="#" class="action-link">
                        <i class="far fa-heart"></i>
                    </a>
                    <a href="#" class="action-link">
                        <i class="fas fa-shopping-bag"></i>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="action-link user-menu">
                            <i class="far fa-user"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <small>Dashboard</small>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-link" style="background: none; border: none; color: inherit; cursor: pointer; padding: 5px 10px;">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" style="display: flex; flex-direction: row; align-items: center;" class="action-link user-menu ">
                            <i class="far fa-user"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="action-link">
                            <span>Register</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">

                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a></li>

                    <li><a href="{{ route('retail') }}" class="{{ request()->routeIs('retail') ? 'active' : '' }}">Retail</a></li>
                    <li><a href="{{ route('wholesale') }}" class="{{ request()->routeIs('wholesale') ? 'active' : '' }}">Wholesale</a></li>
                    <li><a href="{{ route('export') }}" class="{{ request()->routeIs('export') ? 'active' : '' }}">Export</a></li>
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a></li>
                </ul>
                <a href="#" class="daily-deals">
                    <i class="fas fa-tag"></i>
                    Daily Deals
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px auto; max-width: 1200px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px auto; max-width: 1200px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>AlphaVendor is your trusted multi-vendor marketplace for retail, wholesale, and export businesses.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Track Order</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Shipping Info</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 AlphaVendor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
