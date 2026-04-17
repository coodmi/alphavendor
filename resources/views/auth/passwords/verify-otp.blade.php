@extends('layouts.app')

@section('title', 'Verify OTP')

@section('content')
<div class="auth-container">
    <div class="auth-box">
        <h2>Verify OTP</h2>
        <p class="subtitle">Enter the 6-digit code sent to {{ $mobile_number }}</p>

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

        <form action="{{ route('password.verify.otp') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="otp">OTP Code</label>
                <input type="text" id="otp" name="otp" maxlength="6" required autofocus placeholder="Enter 6-digit OTP" pattern="[0-9]{6}">
            </div>

            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>

        <div class="resend-section">
            <p>Didn't receive the code?</p>
            <button type="button" id="resendBtn" class="btn-link">Resend OTP</button>
            <span id="timer" style="display: none; color: #666; font-size: 14px;"></span>
        </div>

        <p class="auth-link">
            <a href="{{ route('password.request') }}">Back to Reset Password</a>
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
    font-size: 18px;
    text-align: center;
    letter-spacing: 5px;
    font-weight: 600;
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

.resend-section {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.resend-section p {
    margin-bottom: 10px;
    color: #666;
    font-size: 14px;
}

.btn-link {
    background: none;
    border: none;
    color: #0d5c63;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    text-decoration: underline;
}

.btn-link:hover {
    color: #FFB833;
}

.btn-link:disabled {
    color: #999;
    cursor: not-allowed;
    text-decoration: none;
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

<script>
let countdown = 60;
let timerInterval;

document.getElementById('resendBtn').addEventListener('click', function() {
    const btn = this;
    btn.disabled = true;
    
    fetch('{{ route("password.resend.otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            startTimer();
        } else {
            alert(data.message || 'Failed to resend OTP');
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to resend OTP. Please try again.');
        btn.disabled = false;
    });
});

function startTimer() {
    countdown = 60;
    const btn = document.getElementById('resendBtn');
    const timer = document.getElementById('timer');
    
    btn.style.display = 'none';
    timer.style.display = 'inline';
    
    timerInterval = setInterval(function() {
        countdown--;
        timer.textContent = `Resend available in ${countdown}s`;
        
        if (countdown <= 0) {
            clearInterval(timerInterval);
            btn.style.display = 'inline';
            btn.disabled = false;
            timer.style.display = 'none';
        }
    }, 1000);
}

// Auto-focus and format OTP input
document.getElementById('otp').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
@endsection
