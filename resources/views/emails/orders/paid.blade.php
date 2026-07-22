<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acquisition Confirmed - Clementine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; background-color: #ffffff; color: #000000; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .h1 { font-family: 'Satoshi', sans-serif; font-size: 56px; line-height: 0.9; text-transform: uppercase; margin: 0; letter-spacing: -0.02em; }
        .wrapper { width: 100%; background-color: #ffffff; padding: 40px 10px; box-sizing: border-box; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #000000; }
        .header { background-color: #000000; color: #ffffff; padding: 50px 40px; }
        .content { padding: 40px; }
        .grid { display: table; width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 32px; background-color: #F3F4F6; }
        .grid-row { display: table-row; }
        .grid-cell { display: table-cell; border: 1px solid #000000; padding: 20px; vertical-align: top; }
        .label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: #666666; margin-bottom: 8px; display: block; }
        .value { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; display: block; }
        
        .items-table { width: 100%; border-collapse: collapse; border: 1px solid #000000; margin-bottom: 32px; }
        .items-table th { text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; color: #000000; border-bottom: 1px solid #000000; background-color: #F3F4F6; padding: 12px; text-align: left; }
        .items-table td { padding: 16px 12px; border-bottom: 1px solid #000000; font-size: 14px; }
        
        .totals-table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
        .totals-table td { padding: 12px 0; font-size: 13px; color: #666666; border-bottom: 1px dotted #cccccc; text-transform: uppercase; }
        .totals-table tr:last-child td { border-bottom: none; padding-top: 24px; color: #000000; font-weight: bold; }
        .total-value { font-family: 'IBM Plex Sans', sans-serif; font-size: 14px; font-weight: bold; }
        
        .btn { display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; text-transform: uppercase; padding: 20px 40px; font-family: 'Satoshi', sans-serif; font-size: 22px; letter-spacing: 1px; border: 1px solid #000000; text-align: center; }
        .btn:hover { background-color: #ffffff; color: #000000; }
        
        .footer { padding: 40px; background-color: #F3F4F6; border-top: 1px solid #000000; font-size: 12px; color: #666666; text-transform: uppercase; }
        
        @media only screen and (max-width: 600px) {
            .grid-cell { display: block; width: auto; border: none; border-bottom: 1px solid #000000; }
            .grid-cell:last-child { border-bottom: none; }
            .h1 { font-size: 42px; }
            .content, .header, .footer { padding: 24px; }
            .btn { width: 100%; box-sizing: border-box; }
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
                <h1 class="h1">ACQUISITION<br>CONFIRMED</h1>
                <p style="margin: 32px 0 0 0; font-size: 12px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.1em; border-top: 1px solid rgba(255,255,255,0.2); pt-4;">Folio of Acquisition</p>
            </div>
            
            <div class="content">
                <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.6;">
                    Hello {{ $order->user->name ?? 'Client' }},<br><br>
                    Your allocation has been secured. The official folio and provenance documents have been generated. Below is the summary of your acquisition.
                </p>
                
                <table class="grid" cellpadding="0" cellspacing="0">
                    <tr class="grid-row">
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">Reference</span>
                            <span class="value" style="font-size: 16px;">#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</span>
                        </td>
                        <td class="grid-cell" style="width: 50%;">
                            <span class="label">Date</span>
                            <span class="value" style="font-size: 16px;">{{ $order->created_at->format('d M Y') }}</span>
                        </td>
                    </tr>
                </table>

                <table class="items-table" cellpadding="0" cellspacing="0">
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
                                <span style="color: #666666; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span>
                            </td>
                            <td style="text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-family: 'IBM Plex Sans', sans-serif; font-weight: bold;">${{ number_format($item->price_at_purchase, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="totals-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>Subtotal (Excl. Tax)</td>
                        <td style="text-align: right;" class="total-value">${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td>{{ $order->user && $order->user->is_vip ? 'VIP Discount' : 'Discount' }}</td>
                        <td style="text-align: right;" class="total-value">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax > 0)
                    <tr>
                        <td>Product Tax</td>
                        <td style="text-align: right;" class="total-value">${{ number_format($order->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Shipping Fee</td>
                        <td style="text-align: right;" class="total-value">${{ number_format($order->shipping_fee, 2) }}</td>
                    </tr>
                    @if($order->shipping_tax > 0)
                    <tr>
                        <td>Shipping Tax</td>
                        <td style="text-align: right;" class="total-value">${{ number_format($order->shipping_tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: 16px;">TOTAL SETTLED</td>
                        <td style="text-align: right; font-size: 20px;" class="total-value">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>

                <table cellpadding="0" cellspacing="0" border="0" style="margin-top: 32px; margin-bottom: 32px; width: 100%;">
                    <tr>
                        <td align="center">
                            <a href="https://clementine.my.id/profile" class="btn">Access Collection</a>
                        </td>
                    </tr>
                </table>

                <p style="margin: 0; font-size: 14px; line-height: 1.6; text-transform: uppercase;">
                    Sincerely,<br>
                    <strong>CLEMENTINE HOROLOGY</strong>
                </p>
            </div>
            
            <div class="footer">
                <p style="margin: 0; color: #999999;">&copy; {{ date('Y') }} CLEMENTINE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </div>
</body>
</html>
