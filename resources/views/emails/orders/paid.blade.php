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
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .totals-table td {
            padding: 5px 0;
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
        .btn {
            display: inline-block;
            background-color: #111111;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 20px;
            border: 1px solid #111111;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CLEMENTINE</div>
        </div>
        
        <div class="content">
            <h1 class="title">Acquisition Confirmed</h1>
            
            <p><strong>REFERENCE:</strong> #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}<br>
            <strong>DATE:</strong> {{ $order->created_at->format('d M Y') }}</p>
            
            <p>Your allocation has been secured. The official folio and provenance documents have been generated. Below is the summary of your acquisition.</p>
            
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name }}</strong><br>
                            <span style="color: #666; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span>
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">${{ number_format($item->price_at_purchase, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals-table">
                <tr>
                    <td><strong>Subtotal (Excl. Tax)</strong></td>
                    <td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td><strong>Discount</strong></td>
                    <td style="text-align: right;">-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->tax > 0)
                <tr>
                    <td><strong>Product Tax</strong></td>
                    <td style="text-align: right;">${{ number_format($order->tax, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Shipping Fee</strong></td>
                    <td style="text-align: right;">${{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
                @if($order->shipping_tax > 0)
                <tr>
                    <td><strong>Shipping Tax</strong></td>
                    <td style="text-align: right;">${{ number_format($order->shipping_tax, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding-top: 15px;"><strong>TOTAL SETTLED</strong></td>
                    <td style="padding-top: 15px; text-align: right;"><strong>${{ number_format($order->total, 2) }}</strong></td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ config('app.url') }}/profile" class="btn">Access Collection</a>
            </div>

            <p style="margin-top: 40px;">Sincerely,<br>
            <strong>CLEMENTINE HOROLOGY</strong></p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} CLEMENTINE. All rights reserved.
        </div>
    </div>
</body>
</html>
