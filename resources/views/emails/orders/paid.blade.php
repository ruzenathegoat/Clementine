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
        .meta-grid {
            margin: 35px 0;
            padding: 25px 0;
            border-top: 1px solid #EAEAEA;
            border-bottom: 1px solid #EAEAEA;
        }
        .meta-row {
            margin-bottom: 10px;
            font-size: 13px;
        }
        .meta-row:last-child {
            margin-bottom: 0;
        }
        .meta-label {
            color: #787774;
            display: inline-block;
            width: 120px;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.2em;
        }
        .meta-value {
            font-weight: 500;
            font-family: 'Geist Mono', 'SF Mono', 'JetBrains Mono', monospace;
            letter-spacing: 0.05em;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
            margin-bottom: 20px;
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
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            margin-bottom: 40px;
        }
        .totals-table td {
            padding: 12px 0;
            font-size: 13px;
            color: #787774;
            border-bottom: 1px solid #F9F9F8;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
            padding-top: 25px;
        }
        .totals-table .total-label {
            font-weight: 500;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 12px;
        }
        .totals-table .total-value {
            font-family: 'Geist Mono', 'SF Mono', 'JetBrains Mono', monospace;
            font-weight: 600;
            color: #111111;
            font-size: 15px;
        }
        .btn-wrapper {
            margin: 50px 0 40px 0;
            text-align: center;
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
            .meta-label { width: 100px; }
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
                <span class="eyebrow">Folio of Acquisition</span>
                <h1 class="title">Acquisition Confirmed</h1>
                
                <p>Your allocation has been secured. The official folio and provenance documents have been generated. Below is the summary of your acquisition.</p>
                
                <div class="meta-grid">
                    <div class="meta-row">
                        <span class="meta-label">Reference</span>
                        <span class="meta-value">#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Date</span>
                        <span class="meta-value">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product->name }}</strong><br>
                                <span style="color: #787774; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span>
                            </td>
                            <td style="text-align: center; font-weight: 500;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-family: 'Geist Mono', monospace;">${{ number_format($item->price_at_purchase, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="totals-table">
                    <tr>
                        <td>Subtotal (Excl. Tax)</td>
                        <td style="text-align: right; font-family: 'Geist Mono', monospace;">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td>{{ $order->user && $order->user->is_vip ? 'VIP Discount' : 'Discount' }}</td>
                        <td style="text-align: right; font-family: 'Geist Mono', monospace;">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax > 0)
                    <tr>
                        <td>Product Tax</td>
                        <td style="text-align: right; font-family: 'Geist Mono', monospace;">${{ number_format($order->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Shipping Fee</td>
                        <td style="text-align: right; font-family: 'Geist Mono', monospace;">${{ number_format($order->shipping_fee, 2) }}</td>
                    </tr>
                    @if($order->shipping_tax > 0)
                    <tr>
                        <td>Shipping Tax</td>
                        <td style="text-align: right; font-family: 'Geist Mono', monospace;">${{ number_format($order->shipping_tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="total-label">TOTAL SETTLED</td>
                        <td class="total-value" style="text-align: right;">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>

                <div class="btn-wrapper">
                    <a href="https://clementine.my.id/profile" class="btn">Access Collection</a>
                </div>

                <div class="meta-text">
                    Sincerely,<br>
                    <strong>CLEMENTINE HOROLOGY</strong>
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
