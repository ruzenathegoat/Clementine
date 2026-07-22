<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocation in Transit - Clementine</title>
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
            <div class="header">
                <div style="margin-bottom: 32px;">
                    <x-logo style="width: 48px; height: 48px; color: #ffffff;" />
                </div>
                <h1 class="h1">ALLOCATION<br>TRANSIT</h1>
                <p style="margin: 32px 0 0 0; font-size: 12px; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.1em; border-top: 1px solid rgba(255,255,255,0.2); pt-4;">Transit Document</p>
            </div>
            
            <div class="content">
                <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 1.6;">
                    Hello {{ $order->shipping_full_name ?? ($order->user->name ?? 'Client') }},<br><br>
                    The provenance and logistics for order <strong>#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</strong> have been finalized. Your allocation is now in transit.
                </p>
                
                <table class="grid" cellpadding="0" cellspacing="0">
                    <tr class="grid-row">
                        <td class="grid-cell" style="width: 100%;" colspan="2">
                            <span class="label">Tracking Number</span>
                            <span class="value" style="font-size: 20px;">{{ $order->tracking_number ?? 'Awaiting Courier' }}</span>
                        </td>
                    </tr>
                    <tr class="grid-row">
                        <td class="grid-cell" style="width: 100%;" colspan="2">
                            <span class="label">Logistics Destination</span>
                            <span class="value" style="text-transform: none; font-weight: normal; font-size: 14px; line-height: 1.6;">
                                <strong>{{ $order->shipping_full_name }}</strong><br>
                                {{ $order->shipping_address1 }}<br>
                                @if($order->shipping_address2) {{ $order->shipping_address2 }}<br> @endif
                                {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </span>
                        </td>
                    </tr>
                </table>

                <table class="items-table" cellpadding="0" cellspacing="0">
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
                                <span style="color: #666666; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span>
                            </td>
                            <td style="text-align: right; font-weight: bold;">{{ $item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <p style="margin: 0; font-size: 14px; line-height: 1.6;">
                    Should you require assistance during the transit period, our concierges are available.<br>
                    Thank you for selecting Clementine Horology.
                </p>
            </div>
            
            <div class="footer">
                <p style="margin: 0; color: #999999;">&copy; {{ date('Y') }} CLEMENTINE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </div>
</body>
</html>
