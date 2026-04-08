@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('sidebar-menu')
    @php
        $userRole = auth()->user()->role;
    @endphp
    @if($userRole === 'admin')
        @include('dashboards.partials.admin-sidebar')
    @elseif($userRole === 'retailer')
        @include('dashboards.partials.retailer-sidebar')
    @elseif($userRole === 'wholesaler')
        @include('dashboards.partials.wholesaler-sidebar')
    @elseif($userRole === 'exporter')
        @include('dashboards.partials.exporter-sidebar')
    @elseif($userRole === 'importer')
        @include('dashboards.partials.importer-sidebar')
    @else
        {{-- Regular User Sidebar --}}
        <div class="menu-section">
            <div class="menu-section-title">Main</div>
            <a href="{{ route('user.dashboard') }}" class="menu-item">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Shopping</div>
            <a href="{{ route('shop') }}" class="menu-item">
                <i class="fas fa-shopping-bag"></i>
                <span>Browse Products</span>
            </a>
            <a href="{{ route('orders.my-orders') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>My Orders</span>
            </a>
            <a href="{{ route('wishlist.index') }}" class="menu-item">
                <i class="fas fa-heart"></i>
                <span>Wishlist</span>
            </a>
            <a href="{{ route('customer.returns.index') }}" class="menu-item">
                <i class="fas fa-undo"></i>
                <span>Returns & Refunds</span>
            </a>
            <a href="{{ route('cart.index') }}" class="menu-item">
                <i class="fas fa-shopping-cart"></i>
                <span>Shopping Cart</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Account</div>
            <a href="{{ route('profile.show') }}" class="menu-item active">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            <a href="{{ route('vendor.tickets.index') }}" class="menu-item">
                <i class="fas fa-ticket-alt"></i>
                <span>Support Tickets</span>
            </a>
        </div>
    @endif
@endsection

@section('content')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .profile-avatar-placeholder-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #667eea;
        font-weight: 700;
        font-size: 48px;
        border: 5px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .profile-header-info {
        flex: 1;
        color: white;
    }
    
    .profile-header-info h1 {
        margin: 0 0 8px 0;
        font-size: 32px;
        font-weight: 700;
    }
    
    .profile-header-info p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }
    
    .profile-role-badge {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-top: 10px;
        backdrop-filter: blur(10px);
    }
    
    .tabs-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .tabs-nav {
        display: flex;
        border-bottom: 2px solid #f0f0f0;
        background: #fafafa;
    }
    
    .tab-button {
        flex: 1;
        padding: 18px 24px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        color: #7f8c8d;
        transition: all 0.3s;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .tab-button:hover {
        background: #f5f5f5;
        color: #2c3e50;
    }
    
    .tab-button.active {
        color: #667eea;
        background: white;
    }
    
    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #667eea;
    }
    
    .tab-content {
        display: none;
        padding: 35px;
        animation: fadeIn 0.3s;
    }
    
    .tab-content.active {
        display: block;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-group-full {
        grid-column: 1 / -1;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-group input:disabled {
        background: #f8f9fa;
        cursor: not-allowed;
    }
    
    .btn-primary {
        padding: 12px 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        padding: 12px 32px;
        background: #e67e22;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 126, 34, 0.4);
    }
    
    .btn-success {
        padding: 10px 20px;
        background: #2ecc71;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }
    
    .btn-danger {
        padding: 10px 20px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
    }
    
    .image-upload-section {
        display: flex;
        align-items: flex-start;
        gap: 30px;
        padding: 25px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 25px;
    }
    
    .current-image {
        text-align: center;
    }
    
    .upload-form {
        flex: 1;
    }
    
    .addresses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .address-card {
        border: 2px solid #e8e8e8;
        padding: 20px;
        border-radius: 10px;
        position: relative;
        transition: all 0.3s;
    }
    
    .address-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .address-card.default {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }
    
    .default-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .address-actions {
        margin-top: 15px;
        display: flex;
        gap: 10px;
    }
    
    .help-text {
        font-size: 13px;
        color: #7f8c8d;
        margin-top: 5px;
    }
    
    .error-text {
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
    }
    
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            padding: 30px 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .tabs-nav {
            flex-direction: column;
        }
        
        .tab-content {
            padding: 20px;
        }
        
        .image-upload-section {
            flex-direction: column;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="profile-avatar-large">
        @else
            <div class="profile-avatar-placeholder-large">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        
        <div class="profile-header-info">
            <h1>{{ $user->name }}</h1>
            <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
            <span class="profile-role-badge">
                <i class="fas fa-user-tag"></i> {{ ucfirst($user->role) }}
            </span>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container">
        <div class="tabs-nav">
            <button class="tab-button active" onclick="switchTab('profile')">
                <i class="fas fa-user"></i> Profile Information
            </button>
            <button class="tab-button" onclick="switchTab('picture')">
                <i class="fas fa-camera"></i> Profile Picture
            </button>
            <button class="tab-button" onclick="switchTab('password')">
                <i class="fas fa-lock"></i> Change Password
            </button>
            <button class="tab-button" onclick="switchTab('addresses')">
                <i class="fas fa-map-marker-alt"></i> Addresses
            </button>
        </div>

        <!-- Profile Information Tab -->
        <div id="profile-tab" class="tab-content active">
            <h3 style="margin: 0 0 25px 0; color: #2c3e50; font-size: 20px;">
                <i class="fas fa-user-circle"></i> Personal Information
            </h3>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Account Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Member Since</label>
                        <input type="text" value="{{ $user->created_at->format('F d, Y') }}" disabled>
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Picture Tab -->
        <div id="picture-tab" class="tab-content">
            <h3 style="margin: 0 0 25px 0; color: #2c3e50; font-size: 20px;">
                <i class="fas fa-camera"></i> Profile Picture
            </h3>
            
            <div class="image-upload-section">
                <div class="current-image">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile"
                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #667eea; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    @else
                        <div style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 60px; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    @if($user->profile_image)
                        <form action="{{ route('profile.delete-image') }}" method="POST" style="margin-top: 15px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger" onclick="return confirm('Remove profile picture?')">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </form>
                    @endif
                </div>
                
                <div class="upload-form">
                    <form action="{{ route('profile.upload-image') }}" method="POST" enctype="multipart/form-data" id="uploadImageForm">
                        @csrf
                        <div class="form-group">
                            <label>Upload New Photo</label>
                            <input type="file" name="profile_image" id="profileImageInput" accept="image/*" required onchange="previewImage(event)">
                            <p class="help-text">
                                <i class="fas fa-info-circle"></i> Accepted: JPG, PNG, GIF (Max: 2MB)
                            </p>
                        </div>

                        <div id="imagePreviewContainer" style="display: none; margin-top: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Preview:</label>
                            <div style="position: relative; display: inline-block;">
                                <img id="imagePreview" src="" alt="Preview"
                                    style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                                <button type="button" onclick="cancelPreview()"
                                    style="position: absolute; top: -5px; right: -5px; width: 30px; height: 30px; border-radius: 50%; background: #e74c3c; color: white; border: none; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div style="margin-top: 20px;">
                            <button type="submit" class="btn-success">
                                <i class="fas fa-upload"></i> Upload Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password Tab -->
        <div id="password-tab" class="tab-content">
            <h3 style="margin: 0 0 25px 0; color: #2c3e50; font-size: 20px;">
                <i class="fas fa-lock"></i> Change Password
            </h3>
            
            <form action="{{ route('profile.change-password') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group form-group-full">
                        <label>Current Password *</label>
                        <input type="password" name="current_password" required>
                        @error('current_password')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="new_password" required>
                        <p class="help-text">Minimum 8 characters</p>
                        @error('new_password')<p class="error-text">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password *</label>
                        <input type="password" name="new_password_confirmation" required>
                        @error('new_password_confirmation')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <button type="submit" class="btn-secondary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Addresses Tab -->
        <div id="addresses-tab" class="tab-content">
            <h3 style="margin: 0 0 25px 0; color: #2c3e50; font-size: 20px;">
                <i class="fas fa-map-marker-alt"></i> Saved Addresses
            </h3>
            
            <button onclick="document.getElementById('addAddressModal').style.display='block'" class="btn-primary" style="margin-bottom: 25px;">
                <i class="fas fa-plus"></i> Add New Address
            </button>

            @if($addresses->count() > 0)
                <div class="addresses-grid">
                    @foreach($addresses as $address)
                        <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                            @if($address->is_default)
                                <span class="default-badge">DEFAULT</span>
                            @endif
                            
                            @if($address->label)
                                <h4 style="margin: 0 0 12px 0; color: #2c3e50; font-size: 16px; font-weight: 700;">
                                    <i class="fas fa-tag"></i> {{ $address->label }}
                                </h4>
                            @endif
                            
                            <p style="margin: 6px 0; color: #2c3e50; font-weight: 600;">
                                {{ $address->first_name }} {{ $address->last_name }}
                            </p>
                            <p style="margin: 6px 0; color: #666; font-size: 14px;">
                                <i class="fas fa-map-marker-alt" style="width: 16px;"></i> {{ $address->address }}
                            </p>
                            <p style="margin: 6px 0; color: #666; font-size: 14px;">
                                <i class="fas fa-city" style="width: 16px;"></i> {{ $address->district }}, {{ $address->state }}
                            </p>
                            <p style="margin: 6px 0; color: #666; font-size: 14px;">
                                <i class="fas fa-globe" style="width: 16px;"></i> {{ $address->country }}
                            </p>
                            <p style="margin: 6px 0; color: #666; font-size: 14px;">
                                <i class="fas fa-phone" style="width: 16px;"></i> {{ $address->phone }}
                            </p>
                            
                            <div class="address-actions">
                                @if(!$address->is_default)
                                    <form action="{{ route('addresses.set-default', $address) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-success">
                                            <i class="fas fa-check"></i> Set Default
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('addresses.destroy', $address) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this address?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-map-marked-alt" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                    <p style="color: #7f8c8d; font-size: 16px; margin: 0;">No saved addresses yet. Add your first address!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div id="addAddressModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
    <div style="background: white; margin: 50px auto; padding: 0; width: 90%; max-width: 650px; border-radius: 16px; max-height: 90vh; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
        <div style="padding: 25px 30px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 style="margin: 0; color: white; font-size: 22px; font-weight: 700;">
                <i class="fas fa-plus-circle"></i> Add New Address
            </h3>
            <button onclick="document.getElementById('addAddressModal').style.display='none'" style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; cursor: pointer; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
        </div>
        
        <div style="padding: 30px; max-height: calc(90vh - 100px); overflow-y: auto;">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Label (Optional)</label>
                    <input type="text" name="label" placeholder="e.g., Home, Office, Work" style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">First Name *</label>
                        <input type="text" name="first_name" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Last Name *</label>
                        <input type="text" name="last_name" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Address *</label>
                    <textarea name="address" required rows="2" style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Division *</label>
                        <select name="state" id="modal_division" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                            <option value="">Select Division</option>
                            <option value="Dhaka">Dhaka</option>
                            <option value="Chattogram">Chattogram</option>
                            <option value="Khulna">Khulna</option>
                            <option value="Rajshahi">Rajshahi</option>
                            <option value="Barisal">Barisal</option>
                            <option value="Sylhet">Sylhet</option>
                            <option value="Rangpur">Rangpur</option>
                            <option value="Mymensingh">Mymensingh</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">District *</label>
                        <select name="district" id="modal_district" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                            <option value="">Select Division First</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">City *</label>
                        <input type="text" name="city" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">Phone *</label>
                        <input type="tel" name="phone" required style="width: 100%; padding: 12px 16px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
                
                <input type="hidden" name="country" value="Bangladesh">
                
                <div style="margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="is_default" value="1" style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                        <span style="font-weight: 600; color: #2c3e50;">Set as default address</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 14px;">
                    <i class="fas fa-save"></i> Save Address
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Tab Switching
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');
    event.target.classList.add('active');
}

// Image Preview
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2097152) {
            if (typeof showToast === 'function') {
                showToast('File size must be less than 2MB', 'error');
            } else {
                alert('File size must be less than 2MB');
            }
            event.target.value = '';
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            if (typeof showToast === 'function') {
                showToast('Please select a valid image file (JPG, PNG, or GIF)', 'error');
            } else {
                alert('Please select a valid image file (JPG, PNG, or GIF)');
            }
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function cancelPreview() {
    document.getElementById('profileImageInput').value = '';
    document.getElementById('imagePreview').src = '';
    document.getElementById('imagePreviewContainer').style.display = 'none';
}

// Division-District mapping
const divisionDistricts = {
    'Dhaka': ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail'],
    'Chattogram': ['Bandarban', 'Brahmanbaria', 'Chandpur', 'Chattogram', 'Comilla', "Cox's Bazar", 'Feni', 'Khagrachari', 'Lakshmipur', 'Noakhali', 'Rangamati'],
    'Khulna': ['Bagerhat', 'Chuadanga', 'Jessore', 'Jhenaidah', 'Khulna', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira'],
    'Rajshahi': ['Bogra', 'Chapainawabganj', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Rajshahi', 'Sirajganj'],
    'Barisal': ['Barguna', 'Barisal', 'Bhola', 'Jhalokathi', 'Patuakhali', 'Pirojpur'],
    'Sylhet': ['Habiganj', 'Moulvibazar', 'Sunamganj', 'Sylhet'],
    'Rangpur': ['Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Rangpur', 'Thakurgaon'],
    'Mymensingh': ['Jamalpur', 'Mymensingh', 'Netrokona', 'Sherpur']
};

document.getElementById('modal_division').addEventListener('change', function() {
    const district = document.getElementById('modal_district');
    const districts = divisionDistricts[this.value] || [];
    
    district.innerHTML = '<option value="">Select District</option>';
    districts.forEach(d => {
        district.innerHTML += `<option value="${d}">${d}</option>`;
    });
});
</script>
@endsection