@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <h2>Register</h2>

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" id="registrationForm" style="margin-top: 24px;">
            @csrf
            <div class="form-group" style="text-align:center;">
                <label for="account_type" style="font-size: 18px; font-weight: 600; color: #444; margin-bottom: 10px; display:block;">Select Account Type</label>
                <div class="custom-select-wrapper">
                    <select id="account_type" name="account_type" class="custom-select" required>
                        <option value="">-- Select Account Type --</option>
                        <option value="user">User</option>
                        <option value="retailer">Retailer</option>
                        <option value="wholesaler">Wholesaler</option>
                        <option value="importer">Importer</option>
                    </select>
                </div>
            </div>

            <div id="user-fields" class="animated-fields" style="display: none;">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                </div>
            </div>

            <div id="professional-fields" class="animated-fields" style="display: none;">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}">
                </div>
                <div class="form-group">
                    <label for="business_type">Business Type</label>
                    <input type="text" id="business_type" name="business_type" value="{{ old('business_type') }}">
                </div>
                <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="pro_email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="pro_password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="pro_password_confirmation" name="password_confirmation">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accountType = document.getElementById('account_type');
            const userFields = document.getElementById('user-fields');
            const proFields = document.getElementById('professional-fields');

            // Helper to enable/disable all inputs in a container
            function setFieldsEnabled(container, enabled) {
                const inputs = container.querySelectorAll('input,select,textarea');
                inputs.forEach(input => {
                    input.disabled = !enabled;
                    if (enabled) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }

            function animateShow(el) {
                el.style.opacity = 0;
                el.style.display = '';
                setTimeout(() => { el.style.opacity = 1; }, 10);
            }
            function animateHide(el) {
                el.style.opacity = 0;
                setTimeout(() => { el.style.display = 'none'; }, 200);
            }
            function toggleFields() {
                if (accountType.value === 'user') {
                    animateShow(userFields);
                    animateHide(proFields);
                    setFieldsEnabled(userFields, true);
                    setFieldsEnabled(proFields, false);
                } else if (accountType.value === 'retailer' || accountType.value === 'wholesaler' || accountType.value === 'importer') {
                    animateHide(userFields);
                    animateShow(proFields);
                    setFieldsEnabled(userFields, false);
                    setFieldsEnabled(proFields, true);
                } else {
                    animateHide(userFields);
                    animateHide(proFields);
                    setFieldsEnabled(userFields, false);
                    setFieldsEnabled(proFields, false);
                }
            }
            accountType.addEventListener('change', toggleFields);
            toggleFields();
        });
        </script>



        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accountType = document.getElementById('account_type');
            const userFields = document.getElementById('user-fields');
            const proFields = document.getElementById('professional-fields');

            function toggleFields() {
                if (accountType.value === 'user') {
                    userFields.style.display = '';
                    proFields.style.display = 'none';
                } else if (accountType.value === 'retailer' || accountType.value === 'wholesaler' || accountType.value === 'importer') {
                    userFields.style.display = 'none';
                    proFields.style.display = '';
                } else {
                    userFields.style.display = 'none';
                    proFields.style.display = 'none';
                }
            }
            accountType.addEventListener('change', toggleFields);
            toggleFields();
        });
        </script>

        <p class="auth-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </p>
    </div>
</div>

<style>
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 70vh;
    padding: 20px;
}

.auth-box {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.auth-box h2 {
    margin-bottom: 30px;
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-primary {
    background: #FFA500;
    color: white;
}

.btn-primary:hover {
    background: #FFB833;
}

.auth-link {
    text-align: center;
    margin-top: 20px;
    color: #666;
}

.auth-link a {
    color: #FFA500;
    text-decoration: none;
}

.auth-link a:hover {
    text-decoration: underline;
    color: #FFB833;
}

.alert {
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 4px;
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
/* Custom Select Styling */
.custom-select-wrapper {
    position: relative;
    width: 100%;
    margin: 0 auto 20px auto;
}
.custom-select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #FFA500;
    border-radius: 6px;
    background: #fff;
    font-size: 16px;
    color: #333;
    appearance: none;
    outline: none;
    transition: border 0.2s;
    box-shadow: 0 2px 8px rgba(255,165,0,0.05);
}
.custom-select:focus {
    border-color: #FFB833;
}

/* Animated fields */
.animated-fields {
    transition: opacity 0.2s;
}

</style>
@endsection
