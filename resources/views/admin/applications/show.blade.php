@extends('layouts.dashboard')

@section('title', 'View Application')
@section('page-title', 'View Application')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-section-title">Management</div>
        <a href="{{ route('admin.users') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('admin.applications') }}" class="menu-item active">
            <i class="fas fa-file-alt"></i>
            <span>Applications</span>
        </a>
        <a href="{{ route('admin.products') }}" class="menu-item">
            <i class="fas fa-box"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="menu-item">
            <i class="fas fa-tags"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('admin.brands') }}" class="menu-item">
            <i class="fas fa-copyright"></i>
            <span>Brands</span>
        </a>
    </div>

    <div class="menu-section">        <div class="menu-section-title">Pages</div>
        <a href="{{ route('admin.retail-page') }}" class="menu-item">
            <i class="fas fa-store"></i>
            <span>Retail Page</span>
        </a>
    </div>

    <div class="menu-section">        <div class="menu-section-title">Settings</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1>Application Details</h1>
        <a href="{{ route('admin.applications') }}" class="btn btn-secondary">Back to Applications</a>
    </div>

    <div class="application-details">
        <div class="detail-section">
            <h2>Applicant Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Name:</strong>
                    <span>{{ $application->user->name }}</span>
                </div>
                <div class="detail-item">
                    <strong>Email:</strong>
                    <span>{{ $application->user->email }}</span>
                </div>
                <div class="detail-item">
                    <strong>Current Role:</strong>
                    <span>{{ ucfirst($application->user->role) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Requested Role:</strong>
                    <span class="badge badge-{{ $application->requested_role }}">
                        {{ ucfirst($application->requested_role) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <h2>📋 Application Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Status:</strong>
                    <span class="status status-{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
                <div class="detail-item">
                    <strong>Applied Date:</strong>
                    <span>{{ $application->created_at->format('M d, Y h:i A') }}</span>
                </div>
                @if($application->reviewed_at)
                    <div class="detail-item">
                        <strong>Reviewed Date:</strong>
                        <span>{{ $application->reviewed_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Reviewed By:</strong>
                        <span>{{ $application->reviewer->name ?? 'N/A' }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="detail-section">
            <h2>🏢 Business Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Business Name:</strong>
                    <span>{{ $application->business_name }}</span>
                </div>
                <div class="detail-item">
                    <strong>Business Type:</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $application->business_type)) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Years in Business:</strong>
                    <span>{{ $application->years_in_business }} years</span>
                </div>
                @if($application->business_registration_number)
                <div class="detail-item">
                    <strong>Registration Number:</strong>
                    <span>{{ $application->business_registration_number }}</span>
                </div>
                @endif
                @if($application->tax_id)
                <div class="detail-item">
                    <strong>Tax ID:</strong>
                    <span>{{ $application->tax_id }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="detail-section">
            <h2>📞 Contact Information</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <strong>Business Phone:</strong>
                    <span><a href="tel:{{ $application->business_phone }}">{{ $application->business_phone }}</a></span>
                </div>
                <div class="detail-item">
                    <strong>Business Email:</strong>
                    <span><a href="mailto:{{ $application->business_email }}">{{ $application->business_email }}</a></span>
                </div>
                @if($application->website)
                <div class="detail-item">
                    <strong>Website:</strong>
                    <span><a href="{{ $application->website }}" target="_blank">{{ $application->website }}</a></span>
                </div>
                @endif
            </div>
        </div>

        <div class="detail-section">
            <h2>📍 Business Address</h2>
            <div class="address-box">
                <p>{{ $application->business_address }}</p>
                <p>{{ $application->city }}, {{ $application->state }} {{ $application->postal_code }}</p>
                <p>{{ $application->country }}</p>
            </div>
        </div>

        <div class="detail-section">
            <h2>📝 Business Details</h2>
            <div class="detail-item" style="margin-bottom: 15px;">
                <strong>Business Description:</strong>
                <div class="reason-box">
                    {{ $application->business_description }}
                </div>
            </div>
            @if($application->product_categories)
            <div class="detail-item" style="margin-bottom: 15px;">
                <strong>Product Categories:</strong>
                <div class="categories-list">
                    @foreach(explode(',', $application->product_categories) as $category)
                        <span class="category-badge">{{ trim($category) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if($application->estimated_monthly_sales)
            <div class="detail-item">
                <strong>Estimated Monthly Sales:</strong>
                <span>${{ number_format($application->estimated_monthly_sales, 2) }}</span>
            </div>
            @endif
        </div>

        <div class="detail-section">
            <h2>💭 Why They Want to Join</h2>
            <div class="reason-box">
                {{ $application->reason }}
            </div>
        </div>

        @if($application->admin_notes)
            <div class="detail-section">
                <h2>Admin Notes</h2>
                <div class="reason-box">
                    {{ $application->admin_notes }}
                </div>
            </div>
        @endif

        @if($application->status === 'pending')
            <div class="detail-section">
                <h2>Review Application</h2>

                <form action="{{ route('admin.applications.approve', $application) }}" method="POST" class="review-form">
                    @csrf
                    <div class="form-group">
                        <label for="admin_notes">Admin Notes (Optional)</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Add any notes about this approval..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">✓ Approve Application</button>
                </form>

                <form action="{{ route('admin.applications.reject', $application) }}" method="POST" class="review-form" style="margin-top: 20px;">
                    @csrf
                    <div class="form-group">
                        <label for="admin_notes_reject">Reason for Rejection <span class="required">*</span></label>
                        <textarea id="admin_notes_reject" name="admin_notes" rows="3" required placeholder="Explain why this application is being rejected..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">✗ Reject Application</button>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
.admin-container {
    max-width: 1000px;
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

.application-details {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.detail-section {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.detail-section:last-child {
    border-bottom: none;
}

.detail-section h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 20px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item strong {
    color: #666;
    font-size: 14px;
}

.detail-item span {
    color: #333;
    font-size: 16px;
}

.reason-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    line-height: 1.6;
    color: #333;
}

.address-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    line-height: 1.8;
    color: #333;
}

.address-box p {
    margin: 0;
}

.categories-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.category-badge {
    display: inline-block;
    padding: 6px 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.detail-item a {
    color: #3498db;
    text-decoration: none;
}

.detail-item a:hover {
    text-decoration: underline;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
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

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.review-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: inherit;
}

.required {
    color: #dc3545;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    display: inline-block;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}
</style>
@endsection
