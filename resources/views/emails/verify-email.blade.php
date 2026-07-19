<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Clementine</title>
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
        .btn { display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; text-transform: uppercase; padding: 20px 40px; font-family: 'Anton', Impact, sans-serif; font-size: 22px; letter-spacing: 1px; border: 1px solid #000000; text-align: center; }
        .btn:hover { background-color: #ffffff; color: #000000; }
        .footer { padding: 40px; background-color: #F3F4F6; border-top: 1px solid #000000; font-size: 12px; color: #666666; text-transform: uppercase; }
        @media only screen and (max-width: 600px) {
            .h1 { font-size: 42px; }
            .content, .header, .footer { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div style="margin-bottom: 32px;">
                    <x-logo style="width: 48px; height: 48px; color: #ffffff;" />
                </div>
                <h1 class="h1">VERIFY<br>EMAIL</h1>
                <p style="margin: 32px 0 0 0; font-size: 12px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.1em; border-top: 1px solid rgba(255,255,255,0.2); pt-4;">Onboarding Protocol</p>
            </div>
            
            <div class="content">
                <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.6;">
                    Hello {{ $notifiable->name ?? 'Client' }},<br><br>
                    Thank you for registering with Clementine Horology. To finalize your account setup and access our collections, please verify your email address by clicking the secure link below.
                </p>
                
                <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 32px;">
                    <tr>
                        <td>
                            <a href="{{ $url }}" class="btn">Verify Email</a>
                        </td>
                    </tr>
                </table>

                <p style="margin: 0 0 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
                    If you did not create an account, no further action is required.
                </p>
            </div>
            
            <div class="footer">
                <p style="margin: 0; color: #999999;">&copy; {{ date('Y') }} CLEMENTINE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </div>
</body>
</html>
