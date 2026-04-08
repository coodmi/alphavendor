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
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo">
                    {{-- <h1><span class="logo-icon">R</span></h1> --}}
                    <a href="{{ route('home') }}" style="display: inline-block; line-height: 0;">
                        @if($siteSettings->site_logo)
                            <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ $siteSettings->site_name }} Logo" style="height: 40px; cursor: pointer;">
                        @else
                            <span style="font-size: 22px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">
                                Alpha<span style="color: #f97316;">Vendor</span>
                            </span>
                        @endif
                    </a>
                </div>
                <form class="search-bar" action="{{ route('shop') }}" method="GET">
                    <input type="text" name="search" placeholder="I am looking for..." value="{{ request('search') }}">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <div class="header-actions">
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="action-link" style="position: relative;">
                            <i class="far fa-heart"></i>
                            @php
                                $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span style="position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="action-link">
                            <i class="far fa-heart"></i>
                        </a>
                    @endauth
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

                                <a href="{{ route('orders.my-orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-shopping-bag w-5"></i>
                                    <span>My Orders</span>
                                </a>

                                <a href="{{ route('customer.returns.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-undo w-5"></i>
                                    <span>Returns & Refunds</span>
                                </a>

                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    <i class="fas fa-heart w-5"></i>
                                    <span>Wishlist</span>
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

    <!-- Mobile Nav Strip (visible on mobile only) -->
    <div class="mobile-nav-strip">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
        <a href="{{ route('retail') }}" class="{{ request()->routeIs('retail') ? 'active' : '' }}">Retail</a>
        <a href="{{ route('wholesale') }}" class="{{ request()->routeIs('wholesale') ? 'active' : '' }}">Wholesale</a>
        <a href="{{ route('import') }}" class="{{ request()->routeIs('import') ? 'active' : '' }}">Import</a>
        <a href="{{ route('sellers.index') }}" class="{{ request()->routeIs('sellers.*') ? 'active' : '' }}">Sellers</a>
    </div>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Navigation Menu -->
    <nav class="mobile-nav-menu" id="mobileNavMenu">
        <div class="mobile-nav-header">
            @if($siteSettings->site_logo)
                <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ $siteSettings->site_name }} Logo" style="height: 32px;">
            @else
                <img src="{{ asset('/airmarket.png') }}" alt="{{ $siteSettings->site_name }} Logo" style="height: 32px;">
            @endif
            <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="mobile-nav-items">
            @auth
                <li><a href="{{ route('wishlist.index') }}" style="border-bottom: 2px solid rgba(0,0,0,0.05);">
                    <i class="fas fa-heart"></i> Wishlist
                    @php
                        $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count();
                    @endphp
                    @if($wishlistCount > 0)
                        <span style="margin-left: auto; background: #e74c3c; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">{{ $wishlistCount }}</span>
                    @endif
                </a></li>
            @endauth
            <li><a href="{{ route('cart.index') }}" style="border-bottom: 2px solid rgba(0,0,0,0.05);">
                <i class="fas fa-shopping-bag"></i> Shopping Cart
                @php
                    $cart = Session::get('cart', []);
                    $cartCount = array_sum(array_column($cart, 'quantity'));
                @endphp
                @if($cartCount > 0)
                    <span style="margin-left: auto; background: #FFA500; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">{{ $cartCount }}</span>
                @endif
            </a></li>
            <li style="border-bottom: 2px solid rgba(0,0,0,0.05); margin-bottom: 12px;"></li>
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Home
            </a></li>
            <li><a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Shop
            </a></li>
            <li><a href="{{ route('retail') }}" class="{{ request()->routeIs('retail') ? 'active' : '' }}">
                <i class="fas fa-store"></i> Retail
            </a></li>
            <li><a href="{{ route('wholesale') }}" class="{{ request()->routeIs('wholesale') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i> Wholesale
            </a></li>
            <li><a href="{{ route('import') }}" class="{{ request()->routeIs('import') ? 'active' : '' }}">
                <i class="fas fa-shipping-fast"></i> Import
            </a></li>
            <li><a href="{{ route('sellers.index') }}" class="{{ request()->routeIs('sellers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Sellers
            </a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                <i class="fas fa-info-circle"></i> About Us
            </a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Contact Us
            </a></li>
            @auth
                <li style="border-top: 2px solid rgba(0,0,0,0.05); margin-top: 12px; padding-top: 12px;"></li>
                <li><a href="{{ route('dashboard') }}">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a></li>
                <li><a href="{{ route('orders.my-orders') }}">
                    <i class="fas fa-shopping-bag"></i> My Orders
                </a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 16px 20px; color: #e74c3c; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 14px; font-size: 15px;">
                            <i class="fas fa-sign-out-alt" style="font-size: 18px; width: 24px; text-align: center;"></i> Logout
                        </button>
                    </form>
                </li>
            @else
                <li style="border-top: 2px solid rgba(0,0,0,0.05); margin-top: 12px; padding-top: 12px;"></li>
                <li><a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a></li>
                <li><a href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i> Register
                </a></li>
            @endauth
        </ul>
    </nav>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">

                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a></li>

                    <li><a href="{{ route('retail') }}" class="{{ request()->routeIs('retail') ? 'active' : '' }}">Retail</a></li>
                    <li><a href="{{ route('wholesale') }}" class="{{ request()->routeIs('wholesale') ? 'active' : '' }}">Wholesale</a></li>
                    <li><a href="{{ route('import') }}" class="{{ request()->routeIs('import') ? 'active' : '' }}">Import</a></li>
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
                    <div class="footer-logo" style="margin-bottom: 15px;">
                        @if($siteSettings->site_logo)
                            <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ $siteSettings->site_name }} Logo" style="height: 40px;">
                        @else
                            <img src="{{ asset('/airmarket.png') }}" alt="{{ $siteSettings->site_name }} Logo" style="height: 40px;">
                        @endif
                    </div>
                    <p>{{ $siteSettings->footer_text ?: ($siteSettings->site_name . ' is your trusted multi-vendor marketplace for retail, wholesale, and import businesses.') }}</p>
                </div>
                <div class="footer-section">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('shop') }}">Shop</a></li>
                        <li><a href="{{ route('retail') }}">Retail</a></li>
                        <li><a href="{{ route('wholesale') }}">Wholesale</a></li>
                        <li><a href="{{ route('import') }}">Import</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="javascript:void(0)" onclick="document.getElementById('chat-toggle').click()">Help Center</a></li>
                        <li><a href="{{ route('orders.my-orders') }}">Track Order</a></li>
                        <li><a href="{{ route('customer.returns.index') }}">Returns</a></li>
                        <li><a href="{{ route('shipping-info') }}">Shipping Info</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Connect With Us</h3>
                    <div class="social-links">
                        @if($siteSettings->facebook_url)
                            <a href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if($siteSettings->twitter_url)
                            <a href="{{ $siteSettings->twitter_url }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if($siteSettings->instagram_url)
                            <a href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($siteSettings->linkedin_url)
                            <a href="{{ $siteSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin"></i></a>
                        @endif
                    </div>
                    
                    <!-- Payment Methods -->
                    @if($siteSettings->payment_logo_1 || $siteSettings->payment_logo_2 || $siteSettings->payment_logo_3)
                        <div style="margin-top: 30px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                            @if($siteSettings->payment_logo_1)
                                <div style="background: white; padding: 5px 8px; border-radius: 4px; display: flex; align-items: center; justify-content: center; min-width: 45px; height: 28px;">
                                    <img src="{{ asset('storage/' . $siteSettings->payment_logo_1) }}" alt="Payment Method 1" style="max-height: 22px; max-width: 50px; object-fit: contain;">
                                </div>
                            @endif
                            @if($siteSettings->payment_logo_2)
                                <div style="background: white; padding: 5px 8px; border-radius: 4px; display: flex; align-items: center; justify-content: center; min-width: 45px; height: 28px;">
                                    <img src="{{ asset('storage/' . $siteSettings->payment_logo_2) }}" alt="Payment Method 2" style="max-height: 22px; max-width: 50px; object-fit: contain;">
                                </div>
                            @endif
                            @if($siteSettings->payment_logo_3)
                                <div style="background: white; padding: 5px 8px; border-radius: 4px; display: flex; align-items: center; justify-content: center; min-width: 45px; height: 28px;">
                                    <img src="{{ asset('storage/' . $siteSettings->payment_logo_3) }}" alt="Payment Method 3" style="max-height: 22px; max-width: 50px; object-fit: contain;">
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="footer-bottom">
                <p>{{ $siteSettings->footer_copyright ?: '© ' . date('Y') . ' ' . $siteSettings->site_name . '. All rights reserved.' }}</p>
                <p>Design & Developed By <a href="https://alphainno.com" target="_blank" rel="noopener noreferrer" style="color: #FFA500; text-decoration: none;">Alphainno</a></p>
            </div>
        </div>
    </footer>

    <!-- Live Chat Widget -->
    <div id="chatbot-container">
        <button id="chat-toggle" class="chat-toggle">
            <i class="fas fa-comments"></i>
            <span class="chat-badge" id="chat-unread-badge" style="display:none;">0</span>
        </button>

        <div id="chat-window" class="chat-window">
            <div class="chat-header">
                <div class="chat-header-info">
                    <i class="fas fa-headset" style="font-size:28px;"></i>
                    <div>
                        <h4>AlphaVendor Support</h4>
                        <span class="chat-status">● Online</span>
                    </div>
                </div>
                <button id="chat-close" class="chat-close-btn"><i class="fas fa-times"></i></button>
            </div>

            <div class="chat-body" id="chat-body">
                <div class="chat-message bot-message">
                    <div class="message-avatar"><i class="fas fa-headset"></i></div>
                    <div class="message-content">
                        <p>Hello! 👋 Welcome to AlphaVendor. How can I help you today?</p>
                        <span class="message-time">Just now</span>
                    </div>
                </div>
                <div id="faq-chips" class="quick-replies"></div>
            </div>

            <div class="chat-footer">
                <input type="text" id="chat-input" placeholder="Type your message..." autocomplete="off"/>
                <button id="chat-send" class="chat-send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <style>
        #chatbot-container { position:fixed!important; bottom:20px!important; right:20px!important; z-index:999999!important; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; }
        .chat-toggle { width:60px!important; height:60px!important; border-radius:50%!important; background:linear-gradient(135deg,#FFA500,#FF8C00)!important; border:none!important; color:white!important; font-size:24px!important; cursor:pointer!important; box-shadow:0 4px 12px rgba(255,165,0,.4)!important; transition:all .3s!important; position:relative!important; display:flex!important; align-items:center!important; justify-content:center!important; }
        .chat-toggle:hover { transform:scale(1.1); }
        .chat-badge { position:absolute; top:-5px; right:-5px; background:#ff4444; color:white; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:bold; border:2px solid white; }
        .chat-window { position:absolute; bottom:80px; right:0; width:380px; height:560px; background:white; border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.15); display:none; flex-direction:column; overflow:hidden; animation:slideUp .3s ease; }
        .chat-window.active { display:flex; }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .chat-header { background:linear-gradient(135deg,#FFA500,#FF8C00); color:white; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; }
        .chat-header-info { display:flex; align-items:center; gap:12px; }
        .chat-header-info h4 { margin:0; font-size:15px; font-weight:700; }
        .chat-status { font-size:12px; opacity:.9; }
        .chat-close-btn { background:none; border:none; color:white; font-size:18px; cursor:pointer; opacity:.8; }
        .chat-close-btn:hover { opacity:1; }
        .chat-body { flex:1; padding:16px; overflow-y:auto; background:#f8f9fa; display:flex; flex-direction:column; gap:12px; }
        .chat-message { display:flex; gap:10px; animation:fadeIn .3s ease; }
        .user-message { flex-direction:row-reverse; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .message-avatar { width:34px; height:34px; border-radius:50%; background:#FFA500; color:white; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; }
        .user-message .message-avatar { background:#6c757d; }
        .message-content p { background:white; padding:10px 14px; border-radius:12px; margin:0 0 3px; box-shadow:0 1px 2px rgba(0,0,0,.06); line-height:1.5; font-size:14px; }
        .user-message .message-content p { background:#FFA500; color:white; }
        .message-time { font-size:11px; color:#9ca3af; padding-left:4px; }
        .quick-replies { display:flex; flex-wrap:wrap; gap:8px; margin-top:4px; }
        .quick-reply-btn { background:white; border:1px solid #e0e0e0; padding:7px 13px; border-radius:20px; font-size:13px; cursor:pointer; transition:all .2s; }
        .quick-reply-btn:hover { background:#FFA500; color:white; border-color:#FFA500; }
        .chat-footer { padding:12px 16px; background:white; border-top:1px solid #e0e0e0; display:flex; gap:10px; }
        #chat-input { flex:1; padding:11px 16px; border:1px solid #e0e0e0; border-radius:24px; outline:none; font-size:14px; }
        #chat-input:focus { border-color:#FFA500; }
        .chat-send-btn { width:44px; height:44px; border-radius:50%; background:#FFA500; border:none; color:white; cursor:pointer; transition:all .2s; display:flex; align-items:center; justify-content:center; }
        .chat-send-btn:hover { background:#FF8C00; transform:scale(1.05); }
        .typing-indicator p { background:white!important; color:#9ca3af!important; font-style:italic; }
        @media(max-width:480px){ .chat-window{width:calc(100vw - 40px);height:calc(100vh - 120px);} }
    </style>

    <script>
    (function() {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
        let pollInterval = null;
        let lastMsgId = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('chat-toggle');
            const win    = document.getElementById('chat-window');
            const close  = document.getElementById('chat-close');
            const input  = document.getElementById('chat-input');
            const send   = document.getElementById('chat-send');
            const body   = document.getElementById('chat-body');
            const badge  = document.getElementById('chat-unread-badge');

            // Load FAQ chips
            fetch('/chat/faqs').then(r=>r.json()).then(faqs=>{
                const chips = document.getElementById('faq-chips');
                if (!chips) return;
                faqs.slice(0,4).forEach(faq=>{
                    const btn = document.createElement('button');
                    btn.className = 'quick-reply-btn';
                    btn.textContent = faq.question;
                    btn.onclick = () => sendMsg(faq.question);
                    chips.appendChild(btn);
                });
            }).catch(()=>{});

            toggle.addEventListener('click', function() {
                win.classList.toggle('active');
                if (win.classList.contains('active')) {
                    badge.style.display = 'none';
                    input.focus();
                    loadMessages();
                    startPolling();
                } else {
                    stopPolling();
                }
            });

            close.addEventListener('click', function() {
                win.classList.remove('active');
                stopPolling();
            });

            send.addEventListener('click', () => sendMsg(input.value));
            input.addEventListener('keypress', e => { if(e.key==='Enter') sendMsg(input.value); });

            function sendMsg(text) {
                text = text.trim();
                if (!text) return;
                input.value = '';
                appendMsg(text, false);
                document.getElementById('faq-chips')?.remove();

                // Show typing
                const typing = appendTyping();

                fetch('/chat/widget/send', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
                    body: JSON.stringify({ message: text })
                }).then(r=>r.json()).then(d=>{
                    typing.remove();
                    if (d.auto_reply) appendMsg(d.auto_reply, true);
                }).catch(()=>{ typing.remove(); });
            }

            function appendMsg(text, isAdmin) {
                const div = document.createElement('div');
                div.className = 'chat-message ' + (isAdmin ? 'bot-message' : 'user-message');
                div.innerHTML = `
                    <div class="message-avatar"><i class="fas fa-${isAdmin?'headset':'user'}"></i></div>
                    <div class="message-content"><p>${escHtml(text)}</p><span class="message-time">Just now</span></div>`;
                body.appendChild(div);
                body.scrollTop = body.scrollHeight;
                return div;
            }

            function appendTyping() {
                const div = document.createElement('div');
                div.className = 'chat-message bot-message typing-indicator';
                div.innerHTML = `<div class="message-avatar"><i class="fas fa-headset"></i></div><div class="message-content"><p>Typing...</p></div>`;
                body.appendChild(div);
                body.scrollTop = body.scrollHeight;
                return div;
            }

            function loadMessages() {
                fetch('/chat/widget/messages', { headers:{'Accept':'application/json'} })
                .then(r=>r.json()).then(d=>{
                    const msgs = d.messages || [];
                    // Only add new messages
                    msgs.forEach(m=>{
                        if (m.id > lastMsgId) {
                            lastMsgId = m.id;
                            // Don't re-add messages already shown from user input
                            if (m.is_admin) appendMsg(m.message, true);
                        }
                    });
                    if (msgs.length) lastMsgId = Math.max(...msgs.map(m=>m.id));
                }).catch(()=>{});
            }

            function startPolling() {
                stopPolling();
                pollInterval = setInterval(()=>{
                    fetch('/chat/widget/messages', { headers:{'Accept':'application/json'} })
                    .then(r=>r.json()).then(d=>{
                        const msgs = d.messages || [];
                        msgs.forEach(m=>{
                            if (m.id > lastMsgId && m.is_admin) {
                                lastMsgId = m.id;
                                appendMsg(m.message, true);
                            }
                        });
                    }).catch(()=>{});
                }, 5000);
            }

            function stopPolling() {
                if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
            }

            function escHtml(t) {
                return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }
        });
    })();
    </script>    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNavMenu = document.getElementById('mobileNavMenu');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const mobileNavClose = document.getElementById('mobileNavClose');

            function openMobileMenu() {
                mobileNavMenu.classList.add('active');
                mobileNavOverlay.style.display = 'block';
                setTimeout(() => mobileNavOverlay.classList.add('active'), 10);
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileNavMenu.classList.remove('active');
                mobileNavOverlay.classList.remove('active');
                setTimeout(() => {
                    mobileNavOverlay.style.display = 'none';
                }, 300);
                document.body.style.overflow = '';
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', openMobileMenu);
            }

            if (mobileNavClose) {
                mobileNavClose.addEventListener('click', closeMobileMenu);
            }

            if (mobileNavOverlay) {
                mobileNavOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close menu when clicking on a link
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-items a');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
