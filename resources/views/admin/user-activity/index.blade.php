@extends('layouts.dashboard')

@section('title', 'User Activity Logs')
@section('page-title', 'User Activity Logs')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="content-area">
    <div class="page-header">
        <h2>User Activity Logs</h2>
        <p>Monitor user activities and system events</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="activity-controls">
        <div class="filters">
            <select class="form-control" id="actionFilter">
                <option value="">All Actions</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="product_created">Product Created</option>
                <option value="order_placed">Order Placed</option>
                <option value="profile_updated">Profile Updated</option>
            </select>
            
            <input type="date" class="form-control" id="dateFilter">
            
            <button class="btn btn-primary" onclick="applyFilters()">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
        </div>
        
        <div class="actions">
            <button class="btn btn-warning" onclick="exportLogs()">
                <i class="fas fa-download"></i> Export Logs
            </button>
            
            <form method="POST" action="{{ route('admin.user-activity.clear') }}" style="display: inline;" 
                  onsubmit="return confirm('Are you sure you want to clear all activity logs?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Clear Logs
                </button>
            </form>
        </div>
    </div>

    <div class="activity-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $activities->count() }}</h3>
                <p>Total Activities</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $activities->where('created_at', '>=', now()->subDay())->count() }}</h3>
                <p>Last 24 Hours</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $activities->unique('user_id')->count() }}</h3>
                <p>Active Users</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Recent Activity</h3>
        </div>
        <div class="card-body">
            <div class="activity-timeline">
                @foreach($activities as $activity)
                <div class="activity-item">
                    <div class="activity-icon">
                        @switch($activity['action'])
                            @case('Login')
                                <i class="fas fa-sign-in-alt text-success"></i>
                                @break
                            @case('Product Created')
                                <i class="fas fa-plus text-primary"></i>
                                @break
                            @case('Order Placed')
                                <i class="fas fa-shopping-cart text-warning"></i>
                                @break
                            @default
                                <i class="fas fa-info-circle text-info"></i>
                        @endswitch
                    </div>
                    
                    <div class="activity-content">
                        <div class="activity-header">
                            <h4>{{ $activity['user_name'] }}</h4>
                            <span class="activity-time">{{ $activity['created_at']->diffForHumans() }}</span>
                        </div>
                        
                        <div class="activity-details">
                            <p><strong>{{ $activity['action'] }}</strong></p>
                            <p>{{ $activity['description'] }}</p>
                            <small class="text-muted">IP: {{ $activity['ip_address'] }}</small>
                        </div>
                    </div>
                    
                    <div class="activity-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails({{ $activity['user_id'] }})">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.activity-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.filters {
    display: flex;
    gap: 15px;
    align-items: center;
}

.actions {
    display: flex;
    gap: 10px;
}

.activity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 5px 0;
}

.stat-info p {
    color: #7f8c8d;
    margin: 0;
    font-size: 14px;
}

.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.activity-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 16px;
}

.activity-time {
    color: #7f8c8d;
    font-size: 12px;
}

.activity-details p {
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.activity-details small {
    color: #95a5a6;
}

.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
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
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; color: white; }
.btn-outline-primary { 
    background: transparent; 
    color: #667eea; 
    border: 1px solid #667eea; 
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.text-success { color: #28a745 !important; }
.text-primary { color: #007bff !important; }
.text-warning { color: #ffc107 !important; }
.text-info { color: #17a2b8 !important; }
.text-muted { color: #6c757d !important; }
</style>

<script>
function applyFilters() {
    const action = document.getElementById('actionFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    // In a real application, you would reload the page with filter parameters
    console.log('Applying filters:', { action, date });
    alert('Filter functionality would be implemented here');
}

function exportLogs() {
    // In a real application, you would generate and download a CSV/Excel file
    alert('Export functionality would be implemented here');
}

function viewDetails(userId) {
    // In a real application, you would navigate to user activity details
    window.location.href = `/admin/user-activity/${userId}`;
}
</script>
@endsection