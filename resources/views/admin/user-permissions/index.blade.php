@extends('layouts.dashboard')

@section('title', 'User Permissions Management')
@section('page-title', 'User Permissions Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="content-area">
    <div class="page-header">
        <h2>User Permissions Management</h2>
        <p>Manage user roles and permissions</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>All Users</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="user-avatar">
                                    @else
                                        <div class="user-avatar-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-active">Active</span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.user-permissions.edit', $user) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit Permissions
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 30px;
}

.page-header h2 {
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.page-header p {
    color: #7f8c8d;
    margin: 0;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
}

.card-body {
    padding: 25px;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
}

.user-avatar-placeholder {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #667eea;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.role-user { background: #e3f2fd; color: #1976d2; }
.role-retailer { background: #f3e5f5; color: #7b1fa2; }
.role-wholesaler { background: #e8f5e8; color: #388e3c; }
.role-exporter { background: #fff3e0; color: #f57c00; }
.role-importer { background: #fce4ec; color: #c2185b; }
.role-admin { background: #ffebee; color: #d32f2f; }

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a6fd8;
    color: white;
    text-decoration: none;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}
</style>
@endsection