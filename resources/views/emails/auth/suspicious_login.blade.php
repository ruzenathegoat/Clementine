<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Suspicious Login Attempt</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 40px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border: 1px solid #000000;">
        <h1 style="text-transform: uppercase; margin-top: 0;">Login Attempt Blocked</h1>
        
        <p>Hi {{ $user->name }},</p>
        
        <p>We detected a login attempt to your Clementine account from an unrecognized device or location. For your security, we have temporarily blocked this login.</p>
        
        <div style="background-color: #f9f9f9; padding: 20px; border-left: 4px solid #000000; margin-bottom: 30px;">
            <p style="margin: 0 0 10px 0;"><strong>IP Address:</strong> {{ $history->ip_address }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Location:</strong> {{ $history->city }}, {{ $history->country }}</p>
            <p style="margin: 0 0 10px 0;"><strong>Device/Browser:</strong> {{ $history->device }} - {{ $history->browser }} on {{ $history->platform }}</p>
            <p style="margin: 0;"><strong>Time:</strong> {{ $history->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
        
        <p>If this was you, please click the button below to verify your login. You will be automatically logged in.</p>
        
        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 15px 30px; background-color: #000000; color: #ffffff; text-decoration: none; text-transform: uppercase; font-weight: bold; margin-bottom: 30px; letter-spacing: 1px;">
            Yes, This Was Me
        </a>
        
        <p>If you did not attempt to log in, please reset your password immediately to secure your account.</p>
        
        <p style="color: #666; font-size: 12px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
            This link will expire in 15 minutes.
        </p>
    </div>
</body>
</html>
