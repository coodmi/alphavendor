@extends('layouts.dashboard')

@section('title', 'Edit User Permissions')
@section('page-title', 'Edit User Permissions')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="content-area">
    <div class="page-header">
        <h2>Edit User Permissions</h2>
        <p>Manage permissions for {{ $user->name }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>User Information</h3>
        </div>
        <div class="card-body">
            <div class="user-profile">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="profile-image">
                @else
                    <div class="profile-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="user-details">
                    <h4>{{ $user->name }}</h4>
                    <p>{{ $user->email }}</p>
                    <span class="current-role">Current Role: {{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.user-permissions.update', $user) }}" method="POST">
        @csrf
        
        <div class="card">
            <div class="card-header">
                <h3>Role Assignment</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select name="role" id="role" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Specific Permissions</h3>
            </div>
            <div class="card-body">
                <div class="permissions-grid">
                    @foreach($permissions as $key => $label)
                        <div class="permission-item">
                            <label class="checkbox-label">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                                    {{ in_array($key, $user->permissions ?? []) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Permissions
            </button>
            <a href="{{ route('admin.user-permissions') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </form>
</div>

<style>
.user-profile {
    display: flex;
    align-items: center;
    gap: 20px;
}

.profile-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-placeholder {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 32px;
}

.user-details h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.user-details p {
    margin: 0 0 10px 0;
    color: #7f8c8d;
}

.current-role {
    padding: 4px 12px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.permission-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #eee;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
    color: #2c3e50;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: #667eea;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}
</style>
@endsection