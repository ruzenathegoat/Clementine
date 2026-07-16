<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #FBFBFA;
            color: #111111;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border: 1px solid #EAEAEA;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .header {
            border-bottom: 2px solid #111111;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 4px;
            text-transform: uppercase;
        }
        .title {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .content {
            line-height: 1.6;
            color: #2F3437;
        }
        .btn {
            display: inline-block;
            background-color: #111111;
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 30px;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #111111;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #787774;
            border-top: 1px solid #EAEAEA;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLEMENTINE</div>
        </div>
        
        <div class="content">
            <h1 class="title">Password Reset Request</h1>
            <p>Dear {{ $notifiable->name ?? 'Customer' }},</p>
            <p>We received a request to reset the password for your Clementine account. You can set a new password by clicking the button below.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="btn">Reset Password</a>
            </div>

            <p style="font-size: 12px; color: #787774; margin-top: 30px;">
                This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.<br>
                If you did not request a password reset, you can safely ignore this email.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
        </div>
    </div>
</body>
</html>
