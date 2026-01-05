@extends('layouts.dashboard')

@section('title', 'Apply for Vendor Role')
@section('page-title', 'Apply for Vendor Role')

@section('sidebar-menu')
    <div class="menu-section">
        <div class="menu-section-title">Main</div>
        <a href="{{ route('user.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </div>
    
    <div class="menu-section">
        <div class="menu-section-title">Become a Vendor</div>
        <a href="{{ route('role.apply') }}" class="menu-item active">
            <i class="fas fa-rocket"></i>
            <span>Apply for Role</span>
        </a>
    </div>
    
    <div class="menu-section">
        <div class="menu-section-title">Account</div>
        <a href="{{ route('profile.show') }}" class="menu-item">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('content')
<div class="form-container">
    <div class="form-box">
        <h2>Apply for Vendor Role</h2>
        <p class="subtitle">Choose a vendor role and tell us why you want to join our platform</p>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('role.apply.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="requested_role">Select Vendor Role <span class="required">*</span></label>
                <select id="requested_role" name="requested_role" required>
                    <option value="">-- Choose a role --</option>
                    <option value="retailer" {{ old('requested_role') == 'retailer' ? 'selected' : '' }}>
                        🏪 Retailer - Sell individual products to consumers
                    </option>
                    <option value="wholesaler" {{ old('requested_role') == 'wholesaler' ? 'selected' : '' }}>
                        📦 Wholesaler - Sell products in bulk to businesses
                    </option>
                    <option value="exporter" {{ old('requested_role') == 'exporter' ? 'selected' : '' }}>
                        🌍 Exporter - Sell products internationally
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="reason">Why do you want this role? <span class="required">*</span></label>
                <textarea id="reason" name="reason" rows="6" required placeholder="Please explain your business background, experience, and why you want to become a vendor on our platform (minimum 20 characters)">{{ old('reason') }}</textarea>
                <small>Minimum 20 characters, maximum 1000 characters</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <div class="info-box">
            <h3>📋 Application Process</h3>
            <ol>
                <li>Submit your application with the required information</li>
                <li>Our admin team will review your application</li>
                <li>You'll be notified once your application is approved or rejected</li>
                <li>Once approved, you can start selling on our platform</li>
            </ol>
        </div>
    </div>
</div>

<style>
.form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-box {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-box h2 {
    margin-bottom: 10px;
    color: #333;
}

.subtitle {
    color: #666;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.required {
    color: #dc3545;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    font-family: inherit;
}

.form-group textarea {
    resize: vertical;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.info-box {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.info-box h3 {
    margin-bottom: 15px;
    color: #333;
}

.info-box ol {
    margin: 0;
    padding-left: 20px;
}

.info-box li {
    margin-bottom: 10px;
    color: #666;
}

.alert {
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 5px;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>
@endsection
