<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Digital Certificates</title>
    <style>
        body {
            background-color: #000000;
            color: #FFFFFF;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            padding-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .title {
            font-size: 14px;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        .intro {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        .certificate {
            border: 1px solid rgba(255,255,255,0.2);
            padding: 30px;
            margin-bottom: 30px;
            background-color: #0a0a0a;
            position: relative;
        }
        .cert-header {
            font-size: 11px;
            letter-spacing: 0.25em;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 10px;
        }
        .product-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .cert-details {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .cert-row {
            display: table-row;
        }
        .cert-label {
            display: table-cell;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            padding: 5px 0;
            width: 40%;
        }
        .cert-value {
            display: table-cell;
            font-size: 13px;
            font-family: monospace;
            padding: 5px 0;
        }
        .stamp-container {
            text-align: right;
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
        }
        .stamp-text {
            font-size: 10px;
            letter-spacing: 0.1em;
            color: #FFFFFF;
            text-transform: uppercase;
            display: inline-block;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 12px;
            border-radius: 4px;
        }
        .footer {
            margin-top: 60px;
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            text-align: center;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Clementine</div>
            <div class="title">Provenance & Verification</div>
        </div>

        <div class="intro">
            Dear Collector,<br><br>
            Thank you for your acquisition. Enclosed are the digital certificates of authenticity for your timepieces. Each certificate is permanently recorded and guarantees the provenance of your piece.
        </div>

        @foreach($order->items as $item)
        <div class="certificate">
            <div class="cert-header">Official Documentation</div>
            <div class="product-name">{{ $item->product->name }}</div>
            
            <div class="cert-details">
                <div class="cert-row">
                    <div class="cert-label">Certificate No.</div>
                    <div class="cert-value">{{ $item->certificate_sn }}</div>
                </div>
                <div class="cert-row">
                    <div class="cert-label">Date of Issue</div>
                    <div class="cert-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</div>
                </div>
                <div class="cert-row">
                    <div class="cert-label">Order Reference</div>
                    <div class="cert-value">{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</div>
                </div>
                @if($item->strapOption)
                <div class="cert-row">
                    <div class="cert-label">Strap Specification</div>
                    <div class="cert-value">{{ $item->strapOption->name }}</div>
                </div>
                @endif
            </div>

            <div class="stamp-container">
                <div class="stamp-text">✓ VERIFIED AUTHENTIC</div>
            </div>
        </div>
        @endforeach

        <div class="footer">
            Clementine Horology<br>
            This is an automated provenance record. Please retain this email for your records.
        </div>
    </div>
</body>
</html>
