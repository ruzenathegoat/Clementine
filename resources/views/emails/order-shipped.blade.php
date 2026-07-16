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
            max-width: 600px;
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
        .tracking-box {
            background-color: #FBFBFA;
            border: 1px solid #EAEAEA;
            padding: 25px;
            margin: 35px 0;
            text-align: center;
            border-radius: 4px;
        }
        .tracking-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #787774;
            margin-bottom: 8px;
        }
        .tracking-number {
            font-size: 20px;
            font-family: 'Geist Mono', 'SF Mono', 'JetBrains Mono', monospace;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: #111111;
        }
        .address-box {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #EAEAEA;
        }
        .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: #787774;
            margin-bottom: 15px;
        }
        .address-text {
            font-size: 14px;
            line-height: 1.6;
            color: #111111;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .items-table th {
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.2em;
            color: #787774;
            border-bottom: 1px solid #EAEAEA;
            padding-bottom: 12px;
            text-align: left;
            font-weight: 500;
        }
        .items-table td {
            padding: 20px 0;
            border-bottom: 1px solid #EAEAEA;
            font-size: 14px;
        }
        .meta-text {
            font-size: 13px;
            color: #787774;
            line-height: 1.6;
            margin-top: 40px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 11px;
            color: #A0A09F;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border-top: 1px solid #EAEAEA;
            padding-top: 30px;
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
                <span class="eyebrow">Transit Document</span>
                <h1 class="title">Your Allocation is in Transit</h1>
                <p>Dear {{ $order->shipping_full_name ?? ($order->user->name ?? 'Client') }},</p>
                <p>The provenance and logistics for order <strong>#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</strong> have been finalized. Your allocation is now in transit.</p>
                
                <div class="tracking-box">
                    <div class="tracking-label">Tracking Number</div>
                    <div class="tracking-number">{{ $order->tracking_number ?? 'Awaiting Courier' }}</div>
                </div>

                <div class="address-box">
                    <div class="section-title">Logistics Destination</div>
                    <div class="address-text">
                        <strong>{{ $order->shipping_full_name }}</strong><br>
                        {{ $order->shipping_address1 }}<br>
                        @if($order->shipping_address2) {{ $order->shipping_address2 }}<br> @endif
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th style="text-align: right;">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product->name ?? 'Product' }}</strong><br>
                                <span style="color: #787774; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span>
                            </td>
                            <td style="text-align: right; font-weight: 500;">{{ $item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="meta-text">
                    Should you require assistance during the transit period, our concierges are available.<br>
                    Thank you for selecting Clementine Horology.
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
