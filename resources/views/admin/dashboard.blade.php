@extends('admin.layout')

@section('title', 'Overview')

@section('content')
<div class="space-y-12 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col sm:flex-row sm:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Overview</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Dashboard</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-xs font-mono uppercase tracking-widest text-[#787774]">Last synced</p>
                <p class="text-sm font-medium">{{ now()->format('M d, Y h:i A') }}</p>
            </div>
            <a href="{{ route('admin.financials.export') }}" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95">
                <span>Generate Report</span>
                <div class="admin-button-island-icon group-hover:translate-x-1 group-hover:-translate-y-[1px] group-hover:scale-105 transition-haptic">
                    <i class="ph-light ph-arrow-up-right text-white"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Asymmetrical Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[auto]">
        
        <!-- Main Revenue Card (col-span-8) -->
        <div class="scroll-reveal md:col-span-8 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 md:p-10 flex flex-col justify-between min-h-[300px] relative">
                <!-- Ambient blur in background -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#FBF3DB]/40 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <div>
                    <p class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Total Gross Revenue</p>
                    <h2 class="font-serif text-6xl md:text-7xl tracking-tighter text-[#111111]">{{ \App\Services\CurrencyService::format($metrics['revenue']) }}</h2>
                </div>
                
                <div class="flex items-center justify-between mt-12">
                    <div class="flex items-center gap-2">
                        @if($metrics['revenue_growth'] >= 0)
                        <span class="admin-badge bg-[#EDF3EC] text-[#346538] flex items-center gap-1">
                            <i class="ph-light ph-trend-up"></i> +{{ number_format($metrics['revenue_growth'], 1) }}%
                        </span>
                        @else
                        <span class="admin-badge bg-[#FDEBEC] text-[#C62828] flex items-center gap-1">
                            <i class="ph-light ph-trend-down"></i> {{ number_format($metrics['revenue_growth'], 1) }}%
                        </span>
                        @endif
                        <span class="text-sm text-[#787774]">vs last month</span>
                    </div>
                    <i class="ph-light ph-chart-line-up text-4xl text-[#EAEAEA]"></i>
                </div>
            </div>
        </div>

        <!-- Orders Card (col-span-4) -->
        <div class="scroll-reveal md:col-span-4 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 flex flex-col justify-between min-h-[300px] relative">
                <div class="relative z-10">
                    <p class="text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Total Orders</p>
                    <h2 class="font-serif text-6xl tracking-tighter text-[#111111]">{{ number_format($metrics['total_orders']) }}</h2>
                    <div class="flex items-center gap-2 mt-4">
                        @if($metrics['orders_growth'] >= 0)
                        <span class="admin-badge bg-[#EDF3EC] text-[#346538] flex items-center gap-1">
                            <i class="ph-light ph-trend-up"></i> +{{ number_format($metrics['orders_growth'], 1) }}%
                        </span>
                        @else
                        <span class="admin-badge bg-[#FDEBEC] text-[#C62828] flex items-center gap-1">
                            <i class="ph-light ph-trend-down"></i> {{ number_format($metrics['orders_growth'], 1) }}%
                        </span>
                        @endif
                    </div>
                </div>
                <div class="relative z-10 flex items-center justify-between mt-12">
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-[#111111] border-b border-[#111111]/30 pb-0.5 hover:border-[#111111] transition-colors">View all orders</a>
                    <div class="w-10 h-10 rounded-full bg-[#F9F9F8] flex items-center justify-center border border-[#EAEAEA]">
                        <i class="ph-light ph-receipt text-xl text-[#111111]"></i>
                    </div>
                </div>
                <!-- Subtle noise overlay -->
                <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E');"></div>
            </div>
        </div>

        <!-- Inventory Status (col-span-6) -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 relative">
                <div class="flex items-center justify-between mb-8">
                    <p class="text-sm font-mono uppercase tracking-widest text-[#787774]">Inventory Status</p>
                    <i class="ph-light ph-package text-2xl text-[#EAEAEA]"></i>
                </div>
                
                <div class="flex items-end gap-4 mb-4">
                    <h2 class="font-serif text-5xl tracking-tight text-[#111111]">{{ number_format($metrics['total_products']) }}</h2>
                    <span class="text-lg text-[#787774] mb-1">total products</span>
                </div>
                
                <div class="w-full bg-[#F9F9F8] h-2 mt-8 overflow-hidden rounded-full">
                    <div class="bg-[#111111] h-full w-[85%] rounded-full"></div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-[#787774]">Stock level healthy</span>
                    <span class="text-xs font-medium">85%</span>
                </div>
            </div>
        </div>

        <!-- VIP Customers (col-span-6) -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 relative">
                 <div class="absolute top-0 left-0 w-64 h-64 bg-[#FDEBEC]/50 rounded-full blur-3xl -ml-16 -mt-16 pointer-events-none"></div>
                
                <div class="relative z-10 flex items-center justify-between mb-8">
                    <p class="text-sm font-mono uppercase tracking-widest text-[#787774]">VIP Customers</p>
                    <i class="ph-light ph-star text-2xl text-[#EAEAEA]"></i>
                </div>
                
                <div class="relative z-10 flex items-end gap-4 mb-6">
                    <h2 class="font-serif text-5xl tracking-tight text-[#111111]">{{ number_format($metrics['vip_customers']) }}</h2>
                    <span class="text-lg text-[#787774] mb-1">active VIPs</span>
                </div>

                <div class="relative z-10 flex items-center gap-[-8px]">
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 z-[3]"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-300 z-[2] -ml-3"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-400 z-[1] -ml-3"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-[#F9F9F8] z-[0] -ml-3 flex items-center justify-center text-xs font-medium text-[#787774]">
                        +{{ max(0, $metrics['vip_customers'] - 3) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- BI Dashboard Section -->
    <div class="mt-12 mb-6">
        <h2 class="font-serif text-4xl tracking-tight text-[#111111]">Business Intelligence</h2>
        <p class="text-[#787774] mt-2">Comprehensive data analytics and performance visualization.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[auto]">
        <!-- Sales Trend (col-span-12) -->
        <div class="scroll-reveal md:col-span-12 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-sales-trend" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <!-- Top Products & Collections (col-span-6 each) -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-top-products" style="width: 100%; height: 350px;"></div>
            </div>
        </div>
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-top-collections" style="width: 100%; height: 350px;"></div>
            </div>
        </div>

        <!-- Customer Retention & Status (col-span-6 & col-span-6) -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-retention" style="width: 100%; height: 300px;"></div>
            </div>
        </div>
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-customer-status" style="width: 100%; height: 300px;"></div>
            </div>
        </div>

        <!-- RFM Analysis (col-span-12) -->
        <div class="scroll-reveal md:col-span-12 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-rfm" style="width: 100%; height: 450px;"></div>
                <p class="text-xs text-[#787774] mt-4 text-center">X: Recency (Days), Y: Frequency (Orders), Bubble Size: Monetary (Revenue)</p>
            </div>
        </div>

        <!-- Regions & Stock (col-span-6 each) -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-regions" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-stock-prediction" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const biData = @json($biData ?? []);

    if (!biData || Object.keys(biData).length === 0) return;

    Highcharts.setOptions({
        chart: {
            style: { fontFamily: '"Plus Jakarta Sans", sans-serif' },
            backgroundColor: 'transparent'
        },
        title: {
            style: { color: '#111111', fontWeight: 'bold', fontSize: '16px' }
        },
        credits: { enabled: false }
    });

    // 1. Sales Trend
    Highcharts.chart('chart-sales-trend', {
        chart: { type: 'spline' },
        title: { text: 'Daily Revenue Trend (Last 30 Days)' },
        xAxis: { categories: biData.sales.daily.categories },
        yAxis: { title: { text: 'Revenue ($)' } },
        series: [{ name: 'Daily Revenue', data: biData.sales.daily.data, color: '#111111' }]
    });

    // 2. Top Products
    Highcharts.chart('chart-top-products', {
        chart: { type: 'bar' },
        title: { text: 'Top Selling Products' },
        xAxis: { categories: biData.top_products.categories },
        yAxis: { title: { text: 'Units Sold' } },
        series: [{ name: 'Units', data: biData.top_products.data, color: '#1F6C9F' }]
    });

    // 3. Top Collections
    Highcharts.chart('chart-top-collections', {
        chart: { type: 'pie' },
        title: { text: 'Sales by Collection' },
        tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: { innerSize: '50%', dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.percentage:.1f} %' } }
        },
        series: [{ name: 'Share', data: biData.top_collections.data }]
    });

    // 4. Customer Retention
    Highcharts.chart('chart-retention', {
        chart: { type: 'pie' },
        title: { text: 'Customer Retention Rate' },
        series: [{
            name: 'Customers',
            data: [
                { name: 'Repeat', y: biData.customer_retention.repeat_count, color: '#346538' },
                { name: 'First-time', y: biData.customer_retention.first_time_count, color: '#C62828' }
            ]
        }]
    });

    // 5. Customer Status
    Highcharts.chart('chart-customer-status', {
        chart: { type: 'pie' },
        title: { text: 'Active vs Inactive Customers (90 Days)' },
        series: [{
            name: 'Users',
            data: [
                { name: 'Active', y: biData.customer_status.active, color: '#111111' },
                { name: 'Inactive', y: biData.customer_status.inactive, color: '#EAEAEA' }
            ]
        }]
    });

    // 6. RFM Analysis
    Highcharts.chart('chart-rfm', {
        chart: { type: 'bubble', zoomType: 'xy' },
        title: { text: 'RFM Customer Segmentation' },
        xAxis: { title: { text: 'Recency (Days since last purchase)' } },
        yAxis: { title: { text: 'Frequency (Total purchases)' } },
        tooltip: {
            useHTML: true,
            headerFormat: '<table>',
            pointFormat: '<tr><th colspan="2"><h3>{point.name}</h3></th></tr>' +
                '<tr><th>Recency:</th><td>{point.x} days</td></tr>' +
                '<tr><th>Frequency:</th><td>{point.y} orders</td></tr>' +
                '<tr><th>Monetary:</th><td>${point.z}</td></tr>',
            footerFormat: '</table>',
            followPointer: true
        },
        series: [{ name: 'Customers', data: biData.rfm, color: 'rgba(159, 47, 45, 0.5)' }]
    });

    // 7. Purchase Regions
    Highcharts.chart('chart-regions', {
        chart: { type: 'column' },
        title: { text: 'Top Purchase Regions (Cities)' },
        xAxis: { categories: biData.regions.categories },
        yAxis: { title: { text: 'Orders' } },
        series: [{ name: 'Orders', data: biData.regions.data, color: '#111111' }]
    });

    // 8. Stock Prediction
    Highcharts.chart('chart-stock-prediction', {
        chart: { type: 'column' },
        title: { text: 'Stock vs 30-Day Predicted Demand' },
        xAxis: { categories: biData.stock_prediction.categories },
        yAxis: [{ title: { text: 'Units' } }],
        series: [
            { name: 'Current Stock', data: biData.stock_prediction.stock, color: '#111111' },
            { name: 'Predicted 30-Day Demand', data: biData.stock_prediction.predicted, color: '#C62828' }
        ]
    });
});
</script>
@endsection
