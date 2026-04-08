<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS OTP Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, button { padding: 10px; width: 100%; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; margin-top: 10px; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .step { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .step h3 { margin-top: 0; }
    </style>
</head>
<body>
    <h1>SMS OTP Testing Interface</h1>
    
    <div class="step">
        <h3>Step 1: Send OTP</h3>
        <form id="sendOtpForm">
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="+8801234567890" required>
            </div>
            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <select id="purpose" name="purpose">
                    <option value="registration">Registration</option>
                    <option value="login">Login</option>
                    <option value="password_reset">Password Reset</option>
                    <option value="phone_verification">Phone Verification</option>
                    <option value="transaction">Transaction</option>
                </select>
            </div>
            <button type="submit">Send OTP</button>
        </form>
        <div id="sendResult"></div>
    </div>

    <div class="step">
        <h3>Step 2: Verify OTP</h3>
        <form id="verifyOtpForm">
            <div class="form-group">
                <label for="verify_phone_number">Phone Number:</label>
                <input type="text" id="verify_phone_number" name="phone_number" placeholder="+8801234567890" required>
            </div>
            <div class="form-group">
                <label for="otp_code">OTP Code:</label>
                <input type="text" id="otp_code" name="otp_code" placeholder="123456" maxlength="6" required>
            </div>
            <div class="form-group">
                <label for="verify_purpose">Purpose:</label>
                <select id="verify_purpose" name="purpose">
                    <option value="registration">Registration</option>
                    <option value="login">Login</option>
                    <option value="password_reset">Password Reset</option>
                    <option value="phone_verification">Phone Verification</option>
                    <option value="transaction">Transaction</option>
                </select>
            </div>
            <button type="submit">Verify OTP</button>
        </form>
        <div id="verifyResult"></div>
    </div>

    <div class="step">
        <h3>Step 3: Check OTP Status</h3>
        <form id="statusForm">
            <div class="form-group">
                <label for="status_phone_number">Phone Number:</label>
                <input type="text" id="status_phone_number" name="phone_number" placeholder="+8801234567890" required>
            </div>
            <div class="form-group">
                <label for="status_purpose">Purpose:</label>
                <select id="status_purpose" name="purpose">
                    <option value="registration">Registration</option>
                    <option value="login">Login</option>
                    <option value="password_reset">Password Reset</option>
                    <option value="phone_verification">Phone Verification</option>
                    <option value="transaction">Transaction</option>
                </select>
            </div>
            <button type="submit">Check Status</button>
        </form>
        <div id="statusResult"></div>
    </div>

    <script>
        // Set CSRF token for all AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Send OTP
        document.getElementById('sendOtpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/otp/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                const resultDiv = document.getElementById('sendResult');
                
                if (result.success) {
                    resultDiv.innerHTML = `<div class="message success">${result.message}</div>`;
                    // Auto-fill verify form
                    document.getElementById('verify_phone_number').value = data.phone_number;
                    document.getElementById('verify_purpose').value = data.purpose;
                } else {
                    resultDiv.innerHTML = `<div class="message error">${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('sendResult').innerHTML = `<div class="message error">Error: ${error.message}</div>`;
            }
        });
        
        // Verify OTP
        document.getElementById('verifyOtpForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/otp/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                const resultDiv = document.getElementById('verifyResult');
                
                if (result.success) {
                    resultDiv.innerHTML = `<div class="message success">${result.message}</div>`;
                } else {
                    resultDiv.innerHTML = `<div class="message error">${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('verifyResult').innerHTML = `<div class="message error">Error: ${error.message}</div>`;
            }
        });
        
        // Check Status
        document.getElementById('statusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/otp/status?' + new URLSearchParams(data), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                
                const result = await response.json();
                const resultDiv = document.getElementById('statusResult');
                
                if (result.success) {
                    const status = result.data;
                    resultDiv.innerHTML = `
                        <div class="message info">
                            <strong>OTP Status:</strong><br>
                            Expires: ${new Date(status.expires_at).toLocaleString()}<br>
                            Attempts: ${status.attempts}/${status.max_attempts}<br>
                            Expired: ${status.is_expired ? 'Yes' : 'No'}<br>
                            Can Attempt: ${status.can_attempt ? 'Yes' : 'No'}
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `<div class="message error">${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('statusResult').innerHTML = `<div class="message error">Error: ${error.message}</div>`;
            }
        });
    </script>
</body>
</html>