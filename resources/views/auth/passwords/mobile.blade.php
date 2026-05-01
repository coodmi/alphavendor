@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <h2>Reset Password</h2>
        <p class="subtitle">Enter your mobile number and we'll send you an OTP to reset your password.</p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.send.otp') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="mobile_number">Mobile Number</label>
                <div style="display:flex; align-items:center; border:1px solid #ddd; border-radius:6px; overflow:hidden; background:white;">
                    <span style="padding:10px 12px; background:#f3f4f6; color:#374151; font-weight:600; font-size:14px; border-right:1px solid #ddd; white-space:nowrap;">+880</span>
                    <input type="text" id="mobile_number" name="mobile_number"
                        value="{{ old('mobile_number') ? ltrim(str_replace('+880','',old('mobile_number')),'0') : '' }}"
                        placeholder="1XXXXXXXXX"
                        maxlength="10"
                        required autofocus
                        style="border:none; outline:none; padding:10px 12px; flex:1; font-size:14px;"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                </div>
                <small style="color:#7f8c8d; font-size:12px; margin-top:4px; display:block;">Enter your number after +880 (e.g. 1712345678)</small>
            </div>

            <button type="submit" class="btn btn-primary">Send OTP</button>
        </form>

        <p class="auth-link">
            <a href="{{ route('login') }}">Back to Login</a>
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
    max-width: 450px;
}

.auth-box h2 {
    margin-bottom: 10px;
    text-align: center;
    color: #333;
}

.subtitle {
    text-align: center;
    color: #666;
    font-size: 14px;
    margin-bottom: 30px;
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

.form-group input[type="text"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input[type="text"]:focus {
    outline: none;
    border-color: #0d5c63;
}

.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    font-weight: 600;
}

.btn-primary {
    background: #0d5c63;
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
    color: #0d5c63;
    text-decoration: none;
    font-weight: 500;
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

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>
@endsection
