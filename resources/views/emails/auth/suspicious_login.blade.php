<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Check - Clementine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'JetBrains Mono', monospace; background-color: #ffffff; color: #000000; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .h1 { font-family: 'Anton', Impact, sans-serif; font-size: 56px; line-height: 0.9; text-transform: uppercase; margin: 0; letter-spacing: -0.02em; }
        .wrapper { width: 100%; background-color: #ffffff; padding: 40px 10px; box-sizing: border-box; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #000000; }
        .header { background-color: #000000; color: #ffffff; padding: 50px 40px; }
        .content { padding: 40px; }
        .grid { display: table; width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 32px; background-color: #F3F4F6; }
        .grid-row { display: table-row; }
        .grid-cell { display: table-cell; border: 1px solid #000000; padding: 20px; vertical-align: top; }
        .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: #666666; margin-bottom: 8px; display: block; }
        .value { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; display: block; }
        .btn { display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; text-transform: uppercase; padding: 20px 40px; font-family: 'Anton', Impact, sans-serif; font-size: 22px; letter-spacing: 1px; border: 1px solid #000000; text-align: center; }
        .btn:hover { background-color: #ffffff; color: #000000; }
        .footer { padding: 40px; background-color: #F3F4F6; border-top: 1px solid #000000; font-size: 12px; color: #666666; text-transform: uppercase; }
        @media only screen and (max-width: 600px) {
            .grid-cell { display: block; width: auto; border: none; border-bottom: 1px solid #000000; }
            .grid-cell:last-child { border-bottom: none; }
            .h1 { font-size: 42px; }
            .content, .header, .footer { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header section: Black bg, white text -->
            <div class="header">
                <h1 class="h1">ACCESS<br>HALTED</h1>
                <p style="margin: 32px 0 0 0; font-size: 12px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.1em; border-top: 1px solid rgba(255,255,255,0.2); pt-4;">Security Matrix Intercept</p>
            </div>
            
            <div class="content">
                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; text-transform: uppercase; font-weight: bold;">
                    Error Code: 403-Anomaly
                </p>
                <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.6;">
                    Hello {{ $user->name }},<br><br>
                    Our system has intercepted a login attempt from an unrecognized location or hardware signature. To preserve the integrity of your collection, this session has been quarantined.
                </p>
                
                <!-- Details Grid using tables for email client support -->
                <table class="grid" cellpadding="0" cellspacing="0">
                    <tr class="grid-row">
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">IP Address</span>
                            <span class="value">{{ $history->ip_address }}</span>
                        </td>
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">Location</span>
                            <span class="value">{{ $history->city ?: 'Unknown' }}, {{ $history->country ?: 'Unknown' }}</span>
                        </td>
                    </tr>
                    <tr class="grid-row">
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">Hardware & Browser</span>
                            <span class="value">{{ $history->device }} - {{ $history->browser }}</span>
                        </td>
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">System OS</span>
                            <span class="value">{{ $history->platform }}</span>
                        </td>
                    </tr>
                </table>
                
                <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.6;">
                    If this attempt was authorized by you, click the secure link below to verify your identity.
                </p>
                
                <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
                    <tr>
                        <td>
                            <a href="{{ $verificationUrl }}" class="btn">Verify & Proceed</a>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="footer">
                <p style="margin: 0 0 8px 0; font-weight: bold;">Time Limit: 15 Minutes</p>
                <p style="margin: 0; color: #999999;">If you did not initiate this login, reset your password immediately to secure your account.</p>
            </div>
        </div>
    </div>
</body>
</html>
