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
            -webkit-font-smoothing: antialiased;
        }
        .outer-wrapper {
            padding: 60px 20px;
            background-color: #FBFBFA;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 50px 60px;
            border: 1px solid #EAEAEA;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .logo {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #111111;
        }
        .eyebrow {
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #787774;
            margin-bottom: 15px;
            border: 1px solid #EAEAEA;
            padding: 4px 12px;
            border-radius: 9999px;
            background-color: #F9F9F8;
        }
        .title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 400;
            margin: 0 0 25px 0;
            letter-spacing: -0.02em;
            color: #111111;
            line-height: 1.2;
        }
        .content {
            line-height: 1.7;
            color: #2F3437;
            font-size: 15px;
        }
        .btn-wrapper {
            margin: 40px 0;
        }
        .btn {
            display: inline-block;
            background-color: #111111;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border-radius: 4px;
        }
        .meta-text {
            font-size: 13px;
            color: #787774;
            line-height: 1.6;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #EAEAEA;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #A0A09F;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        @media only screen and (max-width: 600px) {
            .outer-wrapper { padding: 30px 15px; }
            .container { padding: 40px 30px; }
            .title { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="outer-wrapper">
        <div class="container">
            <div class="header">
                <div class="logo">Clementine</div>
            </div>
            
            <div class="content">
                <span class="eyebrow">Onboarding</span>
                <h1 class="title">Verify Email Address</h1>
                <p>Dear {{ $notifiable->name ?? 'Client' }},</p>
                <p>Thank you for registering with Clementine Horology. To finalize your account setup and access our collections, please verify your email address by clicking the secure link below.</p>
                
                <div class="btn-wrapper">
                    <a href="{{ $url }}" class="btn">Verify Email</a>
                </div>

                <div class="meta-text">
                    If you did not create an account, no further action is required.
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
