@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('profile.show') }}" class="menu-item active">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div style="max-width: 800px;">
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">Profile Information</h2>
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Role</label>
                <input type="text" value="{{ ucfirst($user->role) }}" disabled 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; background: #f8f9fa;">
            </div>

            <button type="submit" style="padding: 12px 30px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                Update Profile
            </button>
        </form>
    </div>

    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; color: #2c3e50;">Profile Picture</h2>
        
        <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px;">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" 
                    style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #3498db;">
            @else
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: 600;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            
            <div>
                <h3 style="margin-bottom: 10px; color: #2c3e50;">{{ $user->name }}</h3>
                <p style="color: #7f8c8d; margin-bottom: 15px;">{{ $user->email }}</p>
                @if($user->profile_image)
                    <form action="{{ route('profile.delete-image') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 8px 16px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                            Remove Photo
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <form action="{{ route('profile.upload-image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Upload New Photo</label>
                <input type="file" name="profile_image" accept="image/*" required 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px;">
                <small style="display: block; margin-top: 5px; color: #7f8c8d;">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
            </div>

            <button type="submit" style="padding: 12px 30px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                Upload Photo
            </button>
        </form>
    </div>
</div>
@endsection
