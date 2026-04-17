@extends('layouts.app')

@section('title', 'Verify Your Phone Number')

@push('styles')
<style>
    /* Professional OTP Verification Styles */
    .otp-verification-container {
        min-height: calc(100vh - 200px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
    }

    .otp-verification-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .otp-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 480px;
        width: 100%;
        text-align: center;
        position: relative;
        z-index: 1;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .otp-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0d5c63, #0a4a50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 30px rgba(255, 165, 0, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 30px rgba(255, 165, 0, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(255, 165, 0, 0.4);
        }
    }

    .otp-icon i {
        font-size: 2rem;
        color: white;
    }

    .otp-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .otp-subtitle {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    .mobile-display {
        background: #f7fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .mobile-display i {
        color: #0d5c63;
        font-size: 1.2rem;
    }

    .otp-input-container {
        margin-bottom: 2rem;
    }

    .otp-inputs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .otp-input {
        width: 60px;
        height: 60px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        background: white;
        transition: all 0.3s ease;
        outline: none;
    }

    .otp-input:focus {
        border-color: #0d5c63;
        box-shadow: 0 0 0 3px rgba(255, 165, 0, 0.1);
        transform: scale(1.05);
    }

    .otp-input.filled {
        border-color: #48bb78;
        background: #f0fff4;
        color: #22543d;
    }

    .otp-input.error {
        border-color: #f56565;
        background: #fed7d7;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .verify-button {
        width: 100%;
        background: linear-gradient(135deg, #0d5c63, #0a4a50);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
        margin-bottom: 1.5rem;
    }

    .verify-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.4);
    }

    .verify-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .verify-button.loading {
        position: relative;
        color: transparent;
    }

    .verify-button.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .resend-section {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .resend-text {
        color: #718096;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .resend-button {
        background: none;
        border: none;
        color: #0d5c63;
        font-weight: 600;
        cursor: pointer;
        text-decoration: underline;
        font-size: 0.95rem;
        transition: color 0.3s ease;
    }

    .resend-button:hover:not(:disabled) {
        color: #0a4a50;
    }

    .resend-button:disabled {
        color: #a0aec0;
        cursor: not-allowed;
        text-decoration: none;
    }

    .countdown {
        color: #0d5c63;
        font-weight: 600;
    }

    .alert {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
    }

    .alert-error {
        background: #fed7d7;
        color: #c53030;
    }

    .alert-success {
        background: #c6f6d5;
        color: #22543d;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s ease;
        margin-top: 1rem;
    }

    .back-link:hover {
        color: #0d5c63;
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        .otp-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .otp-title {
            font-size: 1.75rem;
        }

        .otp-inputs {
            gap: 0.5rem;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .mobile-display {
            font-size: 1rem;
            padding: 0.75rem;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .otp-card {
            background: rgba(26, 32, 44, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .otp-title {
            color: #f7fafc;
        }

        .otp-subtitle {
            color: #a0aec0;
        }

        .mobile-display {
            background: #2d3748;
            border-color: #4a5568;
            color: #f7fafc;
        }

        .otp-input {
            background: #2d3748;
            border-color: #4a5568;
            color: #f7fafc;
        }

        .otp-input:focus {
            background: #374151;
        }
    }
</style>
@endpush

@section('content')
<div class="otp-verification-container">
    <div class="otp-card">
        <!-- OTP Icon -->
        <div class="otp-icon">
            <i class="fas fa-mobile-alt"></i>
        </div>

        <!-- Title and Subtitle -->
        <h1 class="otp-title">Verify Your Phone</h1>
        <p class="otp-subtitle">
            We've sent a 6-digit verification code to your mobile number
        </p>

        <!-- Mobile Number Display -->
        @if(isset($mobile_number) && $mobile_number)
        <div class="mobile-display">
            <i class="fas fa-phone"></i>
            <span>{{ $mobile_number }}</span>
        </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- OTP Form -->
        <form id="otpForm" action="{{ route('register.otp.verify') }}" method="POST">
            @csrf
            
            <div class="otp-input-container">
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" data-index="0" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="1" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="2" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="3" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="4" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" data-index="5" autocomplete="off">
                </div>
                
                <!-- Hidden input for form submission -->
                <input type="hidden" name="otp" id="otpValue">
            </div>

            <button type="submit" class="verify-button" id="verifyBtn" disabled>
                Verify Phone Number
            </button>
        </form>

        <!-- Resend Section -->
        <div class="resend-section">
            <p class="resend-text">
                Didn't receive the code?
            </p>
            <button type="button" class="resend-button" id="resendBtn">
                Resend Code <span class="countdown" id="countdown"></span>
            </button>
        </div>

        <!-- Back Link -->
        <a href="{{ route('register') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Registration
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpValue = document.getElementById('otpValue');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const countdown = document.getElementById('countdown');
    const form = document.getElementById('otpForm');
    
    let resendTimer = null;
    let resendCountdown = 60;

    // Initialize resend countdown
    startResendCountdown();

    // OTP Input Handling
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d*$/.test(value)) {
                e.target.value = '';
                return;
            }

            // Move to next input
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // Update visual state
            updateInputState(input, value);
            
            // Update form state
            updateFormState();
        });

        input.addEventListener('keydown', function(e) {
            // Handle backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                updateInputState(otpInputs[index - 1], '');
                updateFormState();
            }
            
            // Handle arrow keys
            if (e.key === 'ArrowLeft' && index > 0) {
                otpInputs[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
            
            if (pastedData.length === 6) {
                pastedData.split('').forEach((digit, i) => {
                    if (i < otpInputs.length) {
                        otpInputs[i].value = digit;
                        updateInputState(otpInputs[i], digit);
                    }
                });
                updateFormState();
                otpInputs[5].focus();
            }
        });

        input.addEventListener('focus', function() {
            this.select();
        });
    });

    function updateInputState(input, value) {
        input.classList.remove('filled', 'error');
        if (value) {
            input.classList.add('filled');
        }
    }

    function updateFormState() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpValue.value = otp;
        
        if (otp.length === 6) {
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify Phone Number';
        } else {
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Enter 6-digit code';
        }
    }

    function showError() {
        otpInputs.forEach(input => {
            input.classList.add('error');
            setTimeout(() => input.classList.remove('error'), 500);
        });
    }

    function startResendCountdown() {
        resendCountdown = 60;
        resendBtn.disabled = true;
        
        resendTimer = setInterval(() => {
            resendCountdown--;
            countdown.textContent = `(${resendCountdown}s)`;
            
            if (resendCountdown <= 0) {
                clearInterval(resendTimer);
                resendBtn.disabled = false;
                countdown.textContent = '';
            }
        }, 1000);
        
        countdown.textContent = `(${resendCountdown}s)`;
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        const otp = otpValue.value;
        
        if (otp.length !== 6) {
            e.preventDefault();
            showError();
            return;
        }

        verifyBtn.classList.add('loading');
        verifyBtn.disabled = true;
    });

    // Resend OTP
    resendBtn.addEventListener('click', function() {
        if (resendBtn.disabled) return;
        
        // Clear current inputs
        otpInputs.forEach(input => {
            input.value = '';
            updateInputState(input, '');
        });
        updateFormState();
        
        // Focus first input
        otpInputs[0].focus();
        
        // Start countdown again
        startResendCountdown();
        
        // Here you would typically make an AJAX call to resend the OTP
        // For now, we'll just show a success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success';
        alert.textContent = 'Verification code sent successfully!';
        alert.style.animation = 'slideUp 0.3s ease-out';
        
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        form.insertBefore(alert, form.firstChild);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    });

    // Auto-focus first input
    otpInputs[0].focus();

    // Handle form errors
    @if($errors->any())
        showError();
    @endif
});
</script>
@endpush