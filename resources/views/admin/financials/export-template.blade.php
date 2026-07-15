<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
</head>
<body>

    <!-- Header Row -->
    <table>
        <tr>
            <td colspan="5" style="font-size: 24px; font-weight: bold; font-style: italic; font-family: 'Instrument Serif', serif; text-align: left; padding: 20px 0;">
                Clementine
            </td>
        </tr>
        <tr>
            <td colspan="5" style="font-size: 16px; font-weight: bold; color: #111111;">
                FINANCIAL REPORT ({{ strtoupper($period) }})
            </td>
        </tr>
        <tr>
            <td colspan="5" style="font-size: 12px; color: #787774; margin-bottom: 20px;">
                Generated on: {{ now()->format('F j, Y H:i:s') }}
            </td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </table>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th style="background-color: #111111; color: #ffffff; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #111111; width: 15px;">Date</th>
                <th style="background-color: #111111; color: #ffffff; font-weight: bold; text-align: right; padding: 10px; border: 1px solid #111111; width: 25px;">Gross Revenue ({{ \App\Services\CurrencyService::getCode() }})</th>
                <th style="background-color: #111111; color: #ffffff; font-weight: bold; text-align: right; padding: 10px; border: 1px solid #111111; width: 25px;">Total COGS ({{ \App\Services\CurrencyService::getCode() }})</th>
                <th style="background-color: #111111; color: #ffffff; font-weight: bold; text-align: right; padding: 10px; border: 1px solid #111111; width: 25px;">Gross Margin ({{ \App\Services\CurrencyService::getCode() }})</th>
                <th style="background-color: #111111; color: #ffffff; font-weight: bold; text-align: right; padding: 10px; border: 1px solid #111111; width: 15px;">Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumRevenue = 0;
                $sumCogs = 0;
                $sumMargin = 0;
            @endphp
            @foreach($dailyData as $date => $data)
                @php
                    $marginPercent = $data['revenue'] > 0 ? round(($data['margin'] / $data['revenue']) * 100, 2) : 0;
                    $sumRevenue += $data['revenue'];
                    $sumCogs += $data['cogs'];
                    $sumMargin += $data['margin'];
                @endphp
                <tr>
                    <td style="border: 1px solid #EAEAEA; padding: 8px; text-align: left;">{{ $date }}</td>
                    <td style="border: 1px solid #EAEAEA; padding: 8px; text-align: right;">{{ \App\Services\CurrencyService::format($data['revenue'], true) }}</td>
                    <td style="border: 1px solid #EAEAEA; padding: 8px; text-align: right;">{{ \App\Services\CurrencyService::format($data['cogs'], true) }}</td>
                    <td style="border: 1px solid #EAEAEA; padding: 8px; text-align: right;">{{ \App\Services\CurrencyService::format($data['margin'], true) }}</td>
                    <td style="border: 1px solid #EAEAEA; padding: 8px; text-align: right;">{{ $marginPercent }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="background-color: #FBF3DB; font-weight: bold; border: 1px solid #111111; padding: 10px;">TOTAL</td>
                <td style="background-color: #FBF3DB; font-weight: bold; text-align: right; border: 1px solid #111111; padding: 10px;">{{ \App\Services\CurrencyService::format($sumRevenue, true) }}</td>
                <td style="background-color: #FBF3DB; font-weight: bold; text-align: right; border: 1px solid #111111; padding: 10px;">{{ \App\Services\CurrencyService::format($sumCogs, true) }}</td>
                <td style="background-color: #FBF3DB; font-weight: bold; text-align: right; border: 1px solid #111111; padding: 10px;">{{ \App\Services\CurrencyService::format($sumMargin, true) }}</td>
                <td style="background-color: #FBF3DB; font-weight: bold; text-align: right; border: 1px solid #111111; padding: 10px;">
                    {{ $sumRevenue > 0 ? round(($sumMargin / $sumRevenue) * 100, 2) : 0 }}%
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
