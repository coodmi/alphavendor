@extends('layouts.dashboard')

@section('title', 'Importer Dashboard')
@section('page-title', 'Importer Dashboard')

@section('sidebar-menu')
    @include('dashboards.partials.vendor-portal-sidebar')
@endsection

@php $reportsRoute = \App\Support\VendorPortal::reportsRoute(); @endphp

@section('content')
<!-- Verification Alert Banner -->
@if(auth()->user()->needsVerification())
    @if(auth()->user()->verification_status === 'unverified')
    <div style="background: linear-gradient(135deg, #1a6b73 0%, #d97706 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">⚠️</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Account Verification Required</h3>
                <p style="margin: 0 0 12px 0; opacity: 0.95;">Your account is not verified. Please upload the required documents to start selling products.</p>
                <a href="{{ route('verification.index') }}" style="background: white; color: #d97706; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                    <i class="fas fa-upload"></i> Upload Documents Now
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @elseif(auth()->user()->verification_status === 'pending')
    <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">⏳</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Verification Under Review</h3>
                <p style="margin: 0; opacity: 0.95;">Your documents have been submitted and are currently being reviewed by our team. You will be notified once your account is verified.</p>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @elseif(auth()->user()->verification_status === 'rejected')
    <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="font-size: 48px;">❌</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 600;">Verification Rejected</h3>
                <p style="margin: 0 0 8px 0; opacity: 0.95;">Your verification was rejected. Please review the feedback and resubmit your documents.</p>
                @if(auth()->user()->rejection_reason)
                <p style="margin: 0 0 12px 0; padding: 12px; background: rgba(255,255,255,0.2); border-radius: 8px; font-size: 14px;">
                    <strong>Reason:</strong> {{ auth()->user()->rejection_reason }}
                </p>
                @endif
                <a href="{{ route('verification.index') }}" style="background: white; color: #dc2626; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;">
                    <i class="fas fa-redo"></i> Resubmit Documents
                </a>
            </div>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px;">×</button>
        </div>
    </div>
    @endif
@endif

<div style="display: grid; grid-template-columns: 1fr auto; gap: 20px; margin-bottom: 30px; align-items: start;">
    <div>
        <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="color: #7f8c8d;">Manage your importing operations and supplier relationships.</p>
    </div>

    <!-- Profile Card -->
    <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); min-width: 250px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile"
                    style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #3498db;">
            @else
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: 600;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h3 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 16px;">{{ Auth::user()->name }}</h3>
                <p style="margin: 0; color: #7f8c8d; font-size: 13px;">{{ ucfirst(Auth::user()->role) }}</p>
                <a href="{{ route('profile.show') }}" style="font-size: 12px; color: #3498db; text-decoration: none;">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon">🌍</div>
            <div class="stat-info">
                <h3>{{ $totalProducts }}</h3>
                <p>Import Products</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📮</div>
            <div class="stat-info">
                <h3>{{ $totalOrders }}</h3>
                <p>International Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->total_earned) }}</h3>
                <p>Total Earnings</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💵</div>
            <div class="stat-info">
                <h3> {{ currency($wallet->balance) }}</h3>
                <p>Available Balance</p>
            </div>
        </div>
        <div class="stat-card price-range-card">
            <div style="width: 100%;">
                <h3 class="price-range-title">Price Range (FOB)</h3>
                <div class="price-range-slider">
                    <input type="range" min="0" max="10000" value="0" class="range-min" id="priceMin">
                    <input type="range" min="0" max="10000" value="10000" class="range-max" id="priceMax">
                </div>
                <div class="price-display">
                    <span id="minPrice">৳0</span>
                    <span id="maxPrice">৳10,000+</span>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Certifications Management Section -->
        <div class="dashboard-section">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Import Certifications & Rating</h2>
                <button onclick="openCertificationsModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-150 flex items-center gap-2">
                    <i class="fas fa-certificate"></i> Manage Certifications
                </button>
            </div>

            <!-- Current Certifications Display -->
            <div class="grid grid-cols-1 gap-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-700">
                            <i class="fas fa-award text-indigo-600"></i> Your Certifications
                            <span class="ml-2 px-2 py-1 bg-indigo-600 text-white text-xs rounded-full">{{ $certifications->count() }}</span>
                        </h3>
                        <a href="{{ route('importer.certifications') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div id="currentCertifications">
                        @if($certifications->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($certifications as $cert)
                                    <div class="p-3 bg-white border-2 border-indigo-200 rounded-lg hover:shadow-md transition-shadow">
                                        <div class="flex items-start gap-2">
                                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-certificate text-indigo-600 text-lg"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-800 text-sm truncate" title="{{ $cert->name }}">{{ $cert->name }}</h4>
                                                @if($cert->code)
                                                    <p class="text-xs text-gray-500 mt-1">{{ $cert->code }}</p>
                                                @endif
                                                @if($cert->expiry_date)
                                                    <div class="mt-2 flex items-center gap-1">
                                                        <i class="fas fa-clock text-xs {{ $cert->expiry_date->isFuture() ? 'text-green-500' : 'text-red-500' }}"></i>
                                                        <span class="text-xs {{ $cert->expiry_date->isFuture() ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $cert->expiry_date->format('M d, Y') }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($cert->issuing_authority)
                                                    <p class="text-xs text-gray-400 mt-1 truncate" title="{{ $cert->issuing_authority }}">{{ $cert->issuing_authority }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-certificate text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500 text-sm mb-4">No certifications added yet</p>
                                <a href="{{ route('importer.certifications') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                                    <i class="fas fa-plus mr-2"></i>Add Your First Certification
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Importer Rating Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-star text-teal-600"></i> Importer Rating
                    </h3>
                    <div id="currentRating" class="flex items-center gap-3">
                        @if(Auth::user()->importer_rating ?? Auth::user()->exporter_rating)
                            <span class="text-3xl font-bold text-indigo-600">{{ Auth::user()->importer_rating ?? Auth::user()->exporter_rating }}</span>
                            <span class="text-gray-600">/ 5.0</span>
                            <div class="flex text-teal-500 text-xl ml-2">
                                @php $ratingToShow = Auth::user()->importer_rating ?? Auth::user()->exporter_rating; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $ratingToShow)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $ratingToShow)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No rating set yet</p>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">
                        <i class="fas fa-chart-line text-green-500"></i> Import Statistics
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Total Products:</span>
                            <span class="font-semibold text-gray-800">{{ $totalProducts }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Active Certifications:</span>
                            <span class="font-semibold text-gray-800">{{ $certifications->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 text-sm">Total Orders:</span>
                            <span class="font-semibold text-gray-800">{{ $totalOrders }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-section">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <h2 style="margin:0;">Report Analysis</h2>
                <a href="{{ route($reportsRoute) }}" style="padding:10px 18px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border-radius:8px;text-decoration:none;font-size:14px;font-weight:600;">
                    <i class="fas fa-chart-pie"></i> View Full Report Analysis
                </a>
            </div>
            @isset($reportSummary)
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;">
                @foreach([
                    ['Today Orders','today_orders','fa-shopping-cart','#3b82f6'],
                    ['Yesterday Orders','yesterday_orders','fa-calendar-day','#6366f1'],
                    ['Today Returns','today_return','fa-undo','#ef4444'],
                    ['Today Cancels','today_cancel','fa-ban','#f97316'],
                    ['Today Exchange','today_exchange','fa-exchange-alt','#0ea5e9'],
                    ['Product Sell','product_sell','fa-tag','#14b8a6'],
                    ['Product Stock','product_stock','fa-warehouse','#f59e0b'],
                ] as [$label, $key, $icon, $color])
                <div style="background:#f8fafc;border-radius:10px;padding:14px;border-left:4px solid {{ $color }};">
                    <div style="font-size:22px;font-weight:700;color:#1e293b;">{{ number_format($reportSummary[$key] ?? 0) }}</div>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;"><i class="fas {{ $icon }}" style="color:{{ $color }};margin-right:4px;"></i>{{ $label }}</div>
                </div>
                @endforeach
            </div>
            @endisset
        </div>

        <div class="dashboard-section">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('importer.products') }}" class="btn btn-primary">Add Import Product</a>
                <a href="{{ route('vendor.orders') }}" class="btn btn-success">View Orders</a>
                <a href="{{ route($reportsRoute) }}" class="btn btn-info">Report Analysis</a>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>Recent Activity</h2>
            <p>No recent activity yet. Start by adding your first import product!</p>
        </div>
    </div>
</div>

<!-- Certifications Management Modal -->
<div id="certificationsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl shadow-2xl">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600">
            <h3 class="text-xl font-bold text-white">
                <i class="fas fa-certificate"></i> Manage Import Certifications
            </h3>
            <button onclick="closeCertificationsModal()" class="text-white hover:text-gray-200 transition-colors duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="certificationsForm" class="p-6 space-y-6">
            @csrf

            <!-- Certifications Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Import Certifications</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-center cursor-pointer p-4 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all duration-150 {{ in_array('iso_certified', Auth::user()->certifications ?? []) ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <input type="checkbox" name="certifications[]" value="iso_certified"
                            {{ in_array('iso_certified', Auth::user()->certifications ?? []) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700 font-medium">
                            <i class="fas fa-certificate text-indigo-600"></i> ISO Certified
                        </span>
                    </label>

                    <label class="flex items-center cursor-pointer p-4 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all duration-150 {{ in_array('ce_certified', Auth::user()->certifications ?? []) ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <input type="checkbox" name="certifications[]" value="ce_certified"
                            {{ in_array('ce_certified', Auth::user()->certifications ?? []) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700 font-medium">
                            <i class="fas fa-certificate text-indigo-600"></i> CE Certified
                        </span>
                    </label>

                    <label class="flex items-center cursor-pointer p-4 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all duration-150 {{ in_array('fda_approved', Auth::user()->certifications ?? []) ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <input type="checkbox" name="certifications[]" value="fda_approved"
                            {{ in_array('fda_approved', Auth::user()->certifications ?? []) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700 font-medium">
                            <i class="fas fa-certificate text-indigo-600"></i> FDA Approved
                        </span>
                    </label>

                    <label class="flex items-center cursor-pointer p-4 border-2 border-gray-300 rounded-lg hover:border-indigo-500 transition-all duration-150 {{ in_array('export_license', Auth::user()->certifications ?? []) ? 'border-indigo-500 bg-indigo-50' : '' }}">
                        <input type="checkbox" name="certifications[]" value="export_license"
                            {{ in_array('export_license', Auth::user()->certifications ?? []) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-gray-700 font-medium">
                            <i class="fas fa-certificate text-indigo-600"></i> Import License
                        </span>
                    </label>
                </div>
            </div>

            <!-- Importer Rating -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-star text-teal-600"></i> Importer Rating
                </label>
                <div class="flex items-center gap-4">
                    <input type="number" name="exporter_rating" id="exporterRatingInput"
                        value="{{ Auth::user()->exporter_rating ?? '' }}"
                        min="0" max="5" step="0.1"
                        placeholder="0.0"
                        class="w-32 px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <span class="text-sm text-gray-600">Rating out of 5 stars (e.g., 4.5)</span>
                </div>
            </div>

            <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCertificationsModal()"
                    class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-150">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #333;
    margin-bottom: 10px;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    font-size: 40px;
}

.stat-info h3 {
    font-size: 32px;
    margin: 0;
    color: #007bff;
}

.stat-info p {
    margin: 5px 0 0;
    color: #666;
}

.dashboard-content {
    display: grid;
    gap: 30px;
}

.dashboard-section {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-section h2 {
    margin-bottom: 20px;
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

/* Price Range Card Styles */
.price-range-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: block;
}

.price-range-title {
    font-size: 16px;
    color: #2c3e50;
    margin: 0 0 15px 0;
    font-weight: 600;
    border-bottom: 3px solid #0a4a50;
    padding-bottom: 8px;
}

.price-range-slider {
    margin: 20px 0;
    position: relative;
    height: 5px;
    background: #ddd;
    border-radius: 5px;
}

.price-range-slider input[type="range"] {
    position: absolute;
    width: 100%;
    height: 5px;
    background: transparent;
    pointer-events: none;
    -webkit-appearance: none;
    appearance: none;
    top: -7px;
    margin: 0;
    padding: 0;
}

.price-range-slider input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #3498db;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    transition: transform 0.2s, box-shadow 0.2s;
    border: 2px solid #fff;
}

.price-range-slider input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 3px 8px rgba(0,0,0,0.4);
}

.price-range-slider input[type="range"]::-webkit-slider-thumb:active {
    transform: scale(1.1);
}

.price-range-slider input[type="range"]::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border: 2px solid #fff;
    border-radius: 50%;
    background: #3498db;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    transition: transform 0.2s, box-shadow 0.2s;
}

.price-range-slider input[type="range"]::-moz-range-thumb:hover {
    transform: scale(1.15);
    box-shadow: 0 3px 8px rgba(0,0,0,0.4);
}

.price-range-slider input[type="range"]::-moz-range-thumb:active {
    transform: scale(1.1);
}

.price-range-slider input[type="range"]::-webkit-slider-runnable-track {
    width: 100%;
    height: 5px;
    background: transparent;
    border: none;
    outline: none;
}

.price-range-slider input[type="range"]::-moz-range-track {
    width: 100%;
    height: 5px;
    background: transparent;
    border: none;
    outline: none;
}

.price-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rangeMin = document.getElementById('priceMin');
    const rangeMax = document.getElementById('priceMax');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');

    function updatePriceDisplay() {
        let min = parseInt(rangeMin.value);
        let max = parseInt(rangeMax.value);

        // Ensure min is not greater than max
        if (min > max) {
            rangeMin.value = max;
            min = max;
        }

        // Update display
        minPrice.textContent = '৳' + min.toLocaleString();
        maxPrice.textContent = max >= 10000 ? '৳10,000+' : '৳' + max.toLocaleString();

        // Update slider background gradient
        const percent1 = (min / 10000) * 100;
        const percent2 = (max / 10000) * 100;
        document.querySelector('.price-range-slider').style.background =
            `linear-gradient(to right, #ddd ${percent1}%, #3498db ${percent1}%, #3498db ${percent2}%, #ddd ${percent2}%)`;
    }

    rangeMin.addEventListener('input', updatePriceDisplay);
    rangeMax.addEventListener('input', updatePriceDisplay);

    // Initialize
    updatePriceDisplay();
});

// Certifications Modal Functions
function openCertificationsModal() {
    document.getElementById('certificationsModal').classList.remove('hidden');
}

function closeCertificationsModal() {
    document.getElementById('certificationsModal').classList.add('hidden');
}

// Handle certifications form submission
document.getElementById('certificationsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        certifications: [],
        exporter_rating: formData.get('exporter_rating')
    };

    // Get all checked certifications
    formData.getAll('certifications[]').forEach(cert => {
        data.certifications.push(cert);
    });

    fetch('{{ route("importer.certifications.bulk-update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Update current certifications display
            const currentCertsDiv = document.getElementById('currentCertifications');
            if (result.certifications && result.certifications.length > 0) {
                currentCertsDiv.innerHTML = result.certifications.map(cert => {
                    const label = cert.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    return `<span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle"></i> ${label}
                    </span>`;
                }).join('');
            } else {
                currentCertsDiv.innerHTML = '<p class="text-gray-500 text-sm">No certifications added yet</p>';
            }

            // Update rating display
            const currentRatingDiv = document.getElementById('currentRating');
            if (result.exporter_rating) {
                const rating = parseFloat(result.exporter_rating);
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<i class="fas fa-star"></i>';
                    } else if (i - 0.5 <= rating) {
                        starsHtml += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        starsHtml += '<i class="far fa-star"></i>';
                    }
                }
                currentRatingDiv.innerHTML = `
                    <span class="text-3xl font-bold text-indigo-600">${rating}</span>
                    <span class="text-gray-600">/ 5.0</span>
                    <div class="flex text-teal-500 text-xl ml-2">${starsHtml}</div>
                `;
            } else {
                currentRatingDiv.innerHTML = '<p class="text-gray-500 text-sm">No rating set yet</p>';
            }

            closeCertificationsModal();

            // Show success message
            alert(result.message);
        } else {
            alert('Failed to update certifications: ' + (result.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating certifications');
    });
});
</script>
@endsection
