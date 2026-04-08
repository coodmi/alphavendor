@extends('layouts.dashboard')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Manage Users</h1>
        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ Add New User</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <div class="users-table">
        @if($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->mobile_number ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="status status-{{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info">Edit</a>
                                <button onclick="showResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="btn btn-sm btn-warning">Reset Password</button>
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $users->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>No users found.</p>
            </div>
        @endif
    </div>
</div>

<style>
.admin-container {
    max-width: 1400px;
    margin: 40px auto;
    padding: 0 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.admin-header h1 {
    color: #333;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.users-table {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f8f9fa;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

th {
    font-weight: 600;
    color: #333;
}

.actions {
    display: flex;
    gap: 5px;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.badge-admin {
    background: #dc3545;
    color: white;
}

.badge-retailer {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-wholesaler {
    background: #f3e5f5;
    color: #7b1fa2;
}

.badge-exporter {
    background: #e8f5e9;
    color: #388e3c;
}

.badge-user {
    background: #e9ecef;
    color: #495057;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
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

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
}

.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
@endsection


<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reset Password</h3>
            <button class="close-btn" onclick="closeResetPasswordModal()">&times;</button>
        </div>
        <form id="resetPasswordForm" onsubmit="resetPassword(event)">
            <div class="modal-body">
                <p>Reset password for: <strong id="userName"></strong></p>
                
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="password" required minlength="8" class="form-control">
                    <small>Minimum 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" class="form-control">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeResetPasswordModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 12px;
}

.btn-warning {
    background: #FFA500;
    color: white;
}

.btn-warning:hover {
    background: #FFB833;
}
</style>

<script>
let currentUserId = null;

function showResetPasswordModal(userId, userName) {
    currentUserId = userId;
    document.getElementById('userName').textContent = userName;
    document.getElementById('resetPasswordModal').style.display = 'flex';
    document.getElementById('resetPasswordForm').reset();
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').style.display = 'none';
    currentUserId = null;
}

async function resetPassword(event) {
    event.preventDefault();
    
    const password = document.getElementById('new_password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        alert('Passwords do not match!');
        return;
    }
    
    try {
        const response = await fetch(`/admin/users/${currentUserId}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                password: password,
                password_confirmation: confirmation
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Password reset successfully!');
            closeResetPasswordModal();
        } else {
            alert('Error: ' + (data.message || 'Failed to reset password'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while resetting the password');
    }
}

// Close modal when clicking outside
document.getElementById('resetPasswordModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetPasswordModal();
    }
});
</script>
