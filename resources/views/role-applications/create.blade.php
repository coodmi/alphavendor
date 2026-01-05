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

            <!-- Role Selection -->
            <div class="form-section">
                <h3>📋 Role Selection</h3>
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
            </div>

            <!-- Business Information -->
            <div class="form-section">
                <h3>🏢 Business Information</h3>

                <div class="form-group">
                    <label for="business_name">Business Name <span class="required">*</span></label>
                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" required placeholder="Enter your registered business name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="business_registration_number">Business Registration Number</label>
                        <input type="text" id="business_registration_number" name="business_registration_number" value="{{ old('business_registration_number') }}" placeholder="e.g., 123456789">
                    </div>

                    <div class="form-group">
                        <label for="tax_id">Tax ID / VAT Number</label>
                        <input type="text" id="tax_id" name="tax_id" value="{{ old('tax_id') }}" placeholder="e.g., 12-3456789">
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_type">Business Type <span class="required">*</span></label>
                    <select id="business_type" name="business_type" required>
                        <option value="">-- Select business type --</option>
                        <option value="sole_proprietorship" {{ old('business_type') == 'sole_proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                        <option value="partnership" {{ old('business_type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                        <option value="llc" {{ old('business_type') == 'llc' ? 'selected' : '' }}>Limited Liability Company (LLC)</option>
                        <option value="corporation" {{ old('business_type') == 'corporation' ? 'selected' : '' }}>Corporation</option>
                        <option value="other" {{ old('business_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="years_in_business">Years in Business <span class="required">*</span></label>
                    <input type="number" id="years_in_business" name="years_in_business" value="{{ old('years_in_business') }}" required min="0" max="100" placeholder="e.g., 5">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3>📞 Contact Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="business_phone">Business Phone <span class="required">*</span></label>
                        <input type="tel" id="business_phone" name="business_phone" value="{{ old('business_phone') }}" required placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="form-group">
                        <label for="business_email">Business Email <span class="required">*</span></label>
                        <input type="email" id="business_email" name="business_email" value="{{ old('business_email') }}" required placeholder="contact@yourbusiness.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="website">Website (Optional)</label>
                    <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://www.yourbusiness.com">
                </div>
            </div>

            <!-- Business Address -->
            <div class="form-section">
                <h3>📍 Business Address</h3>

                <div class="form-group">
                    <label for="business_address">Street Address <span class="required">*</span></label>
                    <textarea id="business_address" name="business_address" rows="2" required placeholder="Enter your complete business address">{{ old('business_address') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required placeholder="City">
                    </div>

                    <div class="form-group">
                        <label for="state">State/Province <span class="required">*</span></label>
                        <input type="text" id="state" name="state" value="{{ old('state') }}" required placeholder="State">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="postal_code">Postal Code <span class="required">*</span></label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required placeholder="12345">
                    </div>

                    <div class="form-group">
                        <label for="country">Country <span class="required">*</span></label>
                        <input type="text" id="country" name="country" value="{{ old('country') }}" required placeholder="Country">
                    </div>
                </div>
            </div>

            <!-- Business Description -->
            <div class="form-section">
                <h3>📝 Business Details</h3>

                <div class="form-group">
                    <label for="business_description">Business Description <span class="required">*</span></label>
                    <textarea id="business_description" name="business_description" rows="4" required placeholder="Describe your business, products, and services (minimum 50 characters)">{{ old('business_description') }}</textarea>
                    <small>Minimum 50 characters, maximum 2000 characters</small>
                </div>

                <div class="form-group">
                    <label for="product_categories">Product Categories</label>
                    <input type="text" id="product_categories" name="product_categories" value="{{ old('product_categories') }}" placeholder="e.g., Electronics, Clothing, Home & Garden">
                    <small>Comma-separated list of product categories you deal with</small>
                </div>

                <div class="form-group">
                    <label for="estimated_monthly_sales">Estimated Monthly Sales (USD)</label>
                    <input type="number" id="estimated_monthly_sales" name="estimated_monthly_sales" value="{{ old('estimated_monthly_sales') }}" min="0" step="0.01" placeholder="e.g., 50000">
                    <small>Approximate monthly sales volume</small>
                </div>

                <div class="form-group">
                    <label for="reason">Why do you want to join as a vendor? <span class="required">*</span></label>
                    <textarea id="reason" name="reason" rows="5" required placeholder="Tell us about your experience, goals, and why you want to join our platform (minimum 20 characters)">{{ old('reason') }}</textarea>
                    <small>Minimum 20 characters, maximum 1000 characters</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
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
    max-width: 900px;
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
    color: #2c3e50;
    font-size: 28px;
}

.subtitle {
    color: #7f8c8d;
    margin-bottom: 30px;
    font-size: 15px;
}

/* Form Sections */
.form-section {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 2px solid #ecf0f1;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    margin-bottom: 20px;
    color: #2c3e50;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Form Groups */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 500;
    font-size: 14px;
}

.required {
    color: #e74c3c;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.form-group textarea {
    resize: vertical;
}

.form-group small {
    display: block;
    margin-top: 6px;
    color: #7f8c8d;
    font-size: 12px;
}

/* Form Row - Two columns */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn {
    padding: 14px 32px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.info-box {
    margin-top: 30px;
    padding: 25px;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.info-box h3 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 16px;
}

.info-box ol {
    margin: 0;
    padding-left: 20px;
}

.info-box li {
    margin-bottom: 10px;
    color: #34495e;
    line-height: 1.6;
}

.alert {
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 6px;
}

.alert-error {
    background: #fee;
    color: #c0392b;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>
@endsection
