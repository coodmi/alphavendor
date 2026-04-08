@extends('layouts.dashboard')

@section('title', 'User Activity Details')
@section('page-title', 'User Activity Details')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div class="content-area">
    <div class="page-header">
        <h2>Activity Details for {{ $user->name }}</h2>
        <p>Detailed activity log for this user</p>
    </div>

    <div class="user-info-card">
        <div class="user-profile">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="profile-image">
            @else
                <div class="profile-placeholder">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="user-details">
                <h3>{{ $user->name }}</h3>
                <p>{{ $user->email }}</p>
                <span class="role-badge">{{ ucfirst($user->role) }}</span>
            </div>
        </div>
        
        <div class="user-stats">
            <div class="stat-item">
                <span class="stat-value">{{ $activities->count() }}</span>
                <span class="stat-label">Total Activities</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $user->created_at->format('M d, Y') }}</span>
                <span class="stat-label">Member Since</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">{{ $user->updated_at->diffForHumans() }}</span>
                <span class="stat-label">Last Updated</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Activity Timeline</h3>
            <a href="{{ route('admin.user-activity') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to All Activities
            </a>
        </div>
        <div class="card-body">
            @if($activities->count() > 0)
                <div class="timeline">
                    @foreach($activities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker">
                            @switch($activity['action'])
                                @case('Login')
                                    <i class="fas fa-sign-in-alt text-success"></i>
                                    @break
                                @case('Profile Updated')
                                    <i class="fas fa-user-edit text-primary"></i>
                                    @break
                                @default
                                    <i class="fas fa-info-circle text-info"></i>
                            @endswitch
                        </div>
                        
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h4>{{ $activity['action'] }}</h4>
                                <span class="timeline-time">{{ $activity['created_at']->format('M d, Y H:i:s') }}</span>
                            </div>
                            
                            <div class="timeline-body">
                                <p>{{ $activity['description'] }}</p>
                                <div class="activity-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-globe"></i>
                                        IP Address: {{ $activity['ip_address'] }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $activity['created_at']->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h3>No Activity Found</h3>
                    <p>This user hasn't performed any tracked activities yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.user-info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

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

.user-details h3 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 24px;
}

.user-details p {
    margin: 0 0 10px 0;
    color: #7f8c8d;
}

.role-badge {
    padding: 6px 16px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.user-stats {
    display: flex;
    gap: 30px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    font-weight: 600;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    color: #2c3e50;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 2px solid #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-left: 20px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.timeline-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 16px;
}

.timeline-time {
    color: #7f8c8d;
    font-size: 12px;
    font-weight: 500;
}

.timeline-body p {
    margin: 0 0 15px 0;
    color: #2c3e50;
}

.activity-meta {
    display: flex;
    gap: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #7f8c8d;
    font-size: 12px;
}

.meta-item i {
    font-size: 10px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #7f8c8d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.empty-state p {
    margin: 0;
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

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

.text-success { color: #28a745 !important; }
.text-primary { color: #007bff !important; }
.text-info { color: #17a2b8 !important; }
</style>
@endsection