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
        .tracking-box {
            background-color: #F9F9F8;
            border: 1px solid #EAEAEA;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }
        .tracking-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #787774;
            margin-bottom: 10px;
        }
        .tracking-number {
            font-size: 20px;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 2px;
            color: #111111;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .items-table th {
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 2px;
            color: #787774;
            border-bottom: 1px solid #EAEAEA;
            padding-bottom: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 15px 0;
            border-bottom: 1px solid #EAEAEA;
            font-size: 14px;
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
            <h1 class="title">Your Order is on its way</h1>
            <p>Dear {{ $order->shipping_full_name ?? ($order->user->name ?? 'Customer') }},</p>
            <p>Great news! Your order <strong>#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</strong> has been shipped and is currently on its way to you.</p>
            
            <div class="tracking-box">
                <div class="tracking-label">Tracking Number</div>
                <div class="tracking-number">{{ $order->tracking_number ?? 'Awaiting Courier' }}</div>
            </div>

            <p><strong>Shipping Address:</strong><br>
                {{ $order->shipping_full_name }}<br>
                {{ $order->shipping_address1 }}<br>
                @if($order->shipping_address2) {{ $order->shipping_address2 }}<br> @endif
                {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}
            </p>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: right;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td><strong>{{ $item->product->name ?? 'Product' }}</strong></td>
                        <td style="text-align: right;">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p>If you have any questions about your order, please do not hesitate to contact our support team.</p>
            <p>Thank you for shopping with Clementine Horology.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
        </div>
    </div>
</body>
</html>
