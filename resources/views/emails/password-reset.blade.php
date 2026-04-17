<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0d5c63 0%, #0a4a50 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px;">Reset Your Password</h1>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #333; font-size: 16px; line-height: 1.6;">
                                Hello {{ $user->name }},
                            </p>
                            
                            <p style="margin: 0 0 20px; color: #666; font-size: 14px; line-height: 1.6;">
                                You are receiving this email because we received a password reset request for your account.
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetLink }}" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #0d5c63 0%, #0a4a50 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 20px; color: #666; font-size: 14px; line-height: 1.6;">
                                This password reset link will expire in 60 minutes.
                            </p>
                            
                            <p style="margin: 0 0 20px; color: #666; font-size: 14px; line-height: 1.6;">
                                If you did not request a password reset, no further action is required.
                            </p>
                            
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                                <p style="margin: 0 0 10px; color: #999; font-size: 12px;">
                                    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                                </p>
                                <p style="margin: 0; color: #0d5c63; font-size: 12px; word-break: break-all;">
                                    {{ $resetLink }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f8f8; padding: 20px 30px; text-align: center; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
