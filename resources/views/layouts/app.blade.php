<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AlphaVendor - Multi Vendor Marketplace')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('css/retail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wholesale.css') }}">
    <link rel="stylesheet" href="{{ asset('css/export.css') }}">
    <link rel="stylesheet" href="{{ asset('css/import.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sellers.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    {{-- <h1><span class="logo-icon">R</span></h1> --}}
                    <img src="{{ asset('/airmarket.png') }}" alt="AlphaVendor Logo" style="height: 40px;">
                </div>
                <div class="search-bar">
                    <input type="text" placeholder="I am looking for...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
                <div class="header-actions">
                    <a href="#" class="action-link">
                        <i class="far fa-heart"></i>
                    </a>
                    <a href="{{ route('cart.index') }}" class="action-link" style="position: relative;">
                        <i class="fas fa-shopping-bag"></i>
                        @php
                            $cart = Session::get('cart', []);
                            $cartCount = array_sum(array_column($cart, 'quantity'));
                        @endphp
                        @if($cartCount > 0)
                            <span style="position: absolute; top: -8px; right: -8px; background: #FFA500; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">{{ $cartCount }}</span>
                        @endif
                    </a>
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="action-link user-menu flex items-center gap-2 focus:outline-none">
                                <i class="far fa-user"></i>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-dashboard w-5"></i>
                                    <span>Dashboard</span>
                                </a>

                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-user w-5"></i>
                                    <span>My Profile</span>
                                </a>

                                <a href="{{ route('orders.my') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-shopping-bag w-5"></i>
                                    <span>My Orders</span>
                                </a>

                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-heart w-5"></i>
                                    <span>Wishlist</span>
                                </a>

                                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-cog w-5"></i>
                                    <span>Settings</span>
                                </a>

                                <div class="border-t border-gray-200 mt-2 pt-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                                            <i class="fas fa-sign-out-alt w-5"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
                    <li><a href="{{ route('export') }}" class="{{ request()->routeIs('export') ? 'active' : '' }}">Import</a></li>
                    <li><a href="{{ route('sellers.index') }}" class="{{ request()->routeIs('sellers.*') ? 'active' : '' }}">Sellers</a></li>
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
