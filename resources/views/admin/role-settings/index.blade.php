@extends('layouts.dashboard')

@section('title', 'Role Settings Management')
@section('page-title', 'Role Settings Management')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="content-area">
    <div class="page-header">
        <h2>Role Settings Management</h2>
        <p>Configure roles and their default permissions</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- System Roles Section -->
    <div class="section-header">
        <h3>System Roles</h3>
        <p>Default roles for vendors and customers (cannot be deleted)</p>
    </div>

    <div class="roles-grid">
        @foreach($systemRoles as $roleKey => $role)
        <div class="role-card">
            <div class="role-header">
                <div class="role-icon">
                    @switch($roleKey)
                        @case('admin')
                            <i class="fas fa-crown"></i>
                            @break
                        @case('exporter')
                            <i class="fas fa-plane-departure"></i>
                            @break
                        @case('importer')
                            <i class="fas fa-plane-arrival"></i>
                            @break
                        @case('wholesaler')
                            <i class="fas fa-warehouse"></i>
                            @break
                        @case('retailer')
                            <i class="fas fa-store"></i>
                            @break
                        @default
                            <i class="fas fa-user"></i>
                    @endswitch
                </div>
                <div class="role-info">
                    <h3>{{ $role['name'] }}</h3>
                    <p>{{ $role['description'] }}</p>
                </div>
            </div>
            
            <div class="role-permissions">
                <h4>Permissions</h4>
                <ul class="permissions-list">
                    @foreach($role['permissions'] as $permission)
                        <li>
                            <i class="fas fa-check"></i>
                            {{ ucwords(str_replace('_', ' ', $permission)) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Employee Roles Section -->
    <div class="section-header" style="margin-top: 50px;">
        <h3>Employee Roles</h3>
        <p>Custom roles for employees (can be created, edited, and deleted)</p>
    </div>

    <div class="roles-grid">
        @foreach($employeeRoles as $employeeRole)
        <div class="role-card {{ !$employeeRole->is_active ? 'inactive-role' : '' }}">
            <div class="role-header" style="background: linear-gradient(135deg, {{ $employeeRole->access_level === 'basic' ? '#3b82f6 0%, #2563eb 100%' : ($employeeRole->access_level === 'extended' ? '#8b5cf6 0%, #7c3aed 100%' : '#10b981 0%, #059669 100%') }});">
                <div class="role-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="role-info">
                    <h3>{{ $employeeRole->name }}</h3>
                    <p>{{ $employeeRole->description }}</p>
                    <span class="access-badge">{{ $employeeRole->access_level_label }}</span>
                </div>
            </div>
            
            <div class="role-permissions">
                <h4>Permissions</h4>
                <ul class="permissions-list">
                    @if($employeeRole->permissions && count($employeeRole->permissions) > 0)
                        @foreach($employeeRole->permissions as $permission)
                            <li>
                                <i class="fas fa-check"></i>
                                {{ ucwords(str_replace('_', ' ', $permission)) }}
                            </li>
                        @endforeach
                    @else
                        <li class="text-muted">No specific permissions defined</li>
                    @endif
                </ul>
            </div>
            
            <div class="role-actions">
                <button class="btn btn-primary" onclick="editEmployeeRole({{ $employeeRole->id }}, '{{ $employeeRole->name }}', '{{ $employeeRole->description }}', '{{ $employeeRole->access_level }}', {{ json_encode($employeeRole->permissions) }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form action="{{ route('admin.employee-roles.toggle', $employeeRole) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn {{ $employeeRole->is_active ? 'btn-warning' : 'btn-success' }}">
                        <i class="fas fa-{{ $employeeRole->is_active ? 'eye-slash' : 'eye' }}"></i>
                        {{ $employeeRole->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form action="{{ route('admin.employee-roles.delete', $employeeRole) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add New Employee Role Button -->
    <div class="add-role-section">
        <button class="btn btn-success btn-lg" onclick="showAddEmployeeRoleModal()">
            <i class="fas fa-plus"></i> Add New Employee Role
        </button>
    </div>
</div>

<!-- Add/Edit Employee Role Modal -->
<div id="employeeRoleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Employee Role</h3>
            <button class="close-btn" onclick="closeEmployeeRoleModal()">&times;</button>
        </div>
        <form id="employeeRoleForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="roleName">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="roleName" class="form-control" required placeholder="e.g., Sales Manager, Inventory Supervisor">
                </div>
                
                <div class="form-group">
                    <label for="roleDescription">Description</label>
                    <textarea name="description" id="roleDescription" class="form-control" rows="3" placeholder="Brief description of this role..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="accessLevel">Access Level <span class="text-danger">*</span></label>
                    <select name="access_level" id="accessLevel" class="form-control" required>
                        <option value="">Select access level</option>
                        <option value="basic">Basic Access - Standard employee permissions</option>
                        <option value="extended">Extended Access - Manager level permissions</option>
                        <option value="full">Full Access - Supervisor level permissions</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Permissions (Optional)</label>
                    <div class="permissions-checkboxes">
                        <label><input type="checkbox" name="permissions[]" value="view_dashboard"> View Dashboard</label>
                        <label><input type="checkbox" name="permissions[]" value="view_orders"> View Orders</label>
                        <label><input type="checkbox" name="permissions[]" value="manage_orders"> Manage Orders</label>
                        <label><input type="checkbox" name="permissions[]" value="view_products"> View Products</label>
                        <label><input type="checkbox" name="permissions[]" value="manage_products"> Manage Products</label>
                        <label><input type="checkbox" name="permissions[]" value="view_reports"> View Reports</label>
                        <label><input type="checkbox" name="permissions[]" value="view_analytics"> View Analytics</label>
                        <label><input type="checkbox" name="permissions[]" value="manage_team"> Manage Team</label>
                        <label><input type="checkbox" name="permissions[]" value="manage_inventory"> Manage Inventory</label>
                        <label><input type="checkbox" name="permissions[]" value="manage_customers"> Manage Customers</label>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <span id="submitButtonText">Create Role</span>
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeEmployeeRoleModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
.section-header {
    margin-bottom: 25px;
}

.section-header h3 {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 5px;
}

.section-header p {
    color: #6c757d;
    font-size: 14px;
}

.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.role-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s;
}

.role-card:hover {
    transform: translateY(-2px);
}

.role-card.inactive-role {
    opacity: 0.6;
}

.role-header {
    padding: 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
}

.role-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.role-info h3 {
    margin: 0 0 5px 0;
    font-size: 20px;
}

.role-info p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.access-badge {
    display: inline-block;
    margin-top: 5px;
    padding: 3px 10px;
    background: rgba(255,255,255,0.3);
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.role-permissions {
    padding: 25px;
}

.role-permissions h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 16px;
}

.permissions-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.permissions-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    color: #2c3e50;
    font-size: 14px;
}

.permissions-list i {
    color: #28a745;
    font-size: 12px;
}

.permissions-list .text-muted {
    color: #6c757d;
    font-style: italic;
}

.role-actions {
    padding: 20px 25px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.add-role-section {
    text-align: center;
    padding: 30px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary { background: #667eea; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: #000; }
.btn-secondary { background: #6c757d; color: white; }

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 12px 24px;
    font-size: 16px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

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
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
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

.permissions-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.permissions-checkboxes label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: normal;
    cursor: pointer;
}

.text-danger {
    color: #dc3545;
}
</style>

<script>
function showAddEmployeeRoleModal() {
    document.getElementById('modalTitle').textContent = 'Add Employee Role';
    document.getElementById('submitButtonText').textContent = 'Create Role';
    document.getElementById('employeeRoleForm').action = '{{ route("admin.employee-roles.store") }}';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('employeeRoleForm').reset();
    document.getElementById('employeeRoleModal').style.display = 'flex';
}

function editEmployeeRole(id, name, description, accessLevel, permissions) {
    document.getElementById('modalTitle').textContent = 'Edit Employee Role';
    document.getElementById('submitButtonText').textContent = 'Update Role';
    document.getElementById('employeeRoleForm').action = `/admin/employee-roles/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('roleName').value = name;
    document.getElementById('roleDescription').value = description || '';
    document.getElementById('accessLevel').value = accessLevel;
    
    // Clear all checkboxes first
    document.querySelectorAll('#employeeRoleModal input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Check the permissions for this role
    if (permissions && Array.isArray(permissions)) {
        permissions.forEach(permission => {
            const checkbox = document.querySelector(`input[value="${permission}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    document.getElementById('employeeRoleModal').style.display = 'flex';
}

function closeEmployeeRoleModal() {
    document.getElementById('employeeRoleModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('employeeRoleModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmployeeRoleModal();
    }
});
</script>
@endsection