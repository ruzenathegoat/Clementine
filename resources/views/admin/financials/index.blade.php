@extends('admin.layout')

@section('title', 'Financial Analytics')

@section('content')
<div class="space-y-10 pb-12 max-w-7xl mx-auto">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#E1F3FE] text-[#1F6C9F]">Finance</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Analytics</h1>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Period Filter -->
            <form action="{{ route('admin.financials.index') }}" method="GET" class="relative">
                <select name="period" onchange="this.form.submit()" class="pl-4 pr-10 py-2 bg-[#F9F9F8] border border-[#EAEAEA] rounded-full text-sm font-medium text-[#111111] focus:outline-none appearance-none cursor-pointer">
                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                </select>
                <i class="ph-light ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-[#787774] pointer-events-none"></i>
            </form>

            <a href="{{ route('admin.financials.export', ['period' => $period]) }}" class="admin-button-island group hover:bg-[#333333] transition-haptic active:scale-95">
                <span>Export CSV</span>
                <div class="admin-button-island-icon group-hover:bg-white/20 transition-colors">
                    <i class="ph-light ph-download-simple text-white"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Asymmetrical Bento Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 auto-rows-[auto]">
        
        <!-- Gross Revenue (col-span-4) -->
        <div class="scroll-reveal md:col-span-4 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 flex flex-col justify-between min-h-[240px]">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-mono uppercase tracking-widest text-[#787774]">Gross Revenue</p>
                        <i class="ph-light ph-trend-up text-xl text-[#EAEAEA]"></i>
                    </div>
                    <h2 class="font-serif text-4xl tracking-tighter text-[#111111]">{{ \App\Services\CurrencyService::format($grossRevenue) }}</h2>
                </div>
                
                <div class="mt-8 text-xs text-[#787774]">
                    Total sales from all successful orders.
                </div>
            </div>
        </div>

        <!-- COGS (col-span-4) -->
        <div class="scroll-reveal md:col-span-4 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 flex flex-col justify-between min-h-[240px]">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-mono uppercase tracking-widest text-[#787774]">Total COGS</p>
                        <i class="ph-light ph-package text-xl text-[#EAEAEA]"></i>
                    </div>
                    <h2 class="font-serif text-4xl tracking-tighter text-[#111111]">{{ \App\Services\CurrencyService::format($totalCogs) }}</h2>
                </div>
                
                <div class="mt-8 text-xs text-[#787774]">
                    Total cost of goods sold.
                </div>
            </div>
        </div>

        <!-- Gross Margin -->
        <div class="scroll-reveal md:col-span-4 admin-outer-shell group">
            <div class="admin-inner-core h-full p-8 flex flex-col justify-between min-h-[240px] relative overflow-hidden">
                <!-- Ambient glow -->
                <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-[#EDF3EC]/50 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-mono uppercase tracking-widest text-[#787774]">Gross Margin</p>
                        <span class="admin-badge bg-[#EDF3EC] text-[#346538] font-mono border-0">
                            {{ number_format($marginPercentage, 1) }}%
                        </span>
                    </div>
                    <h2 class="font-serif text-4xl tracking-tighter text-[#111111]">{{ \App\Services\CurrencyService::format($grossMargin) }}</h2>
                </div>
                
                <div class="relative z-10 mt-8 text-xs text-[#787774] border-t border-[#EAEAEA] pt-4">
                    Net profit before operating expenses.
                </div>
            </div>
        </div>

    </div>

    <!-- Daily Breakdown (Simulated Chart/Table) -->
    <div class="scroll-reveal admin-outer-shell mb-6">
        <div class="admin-inner-core p-8">
            <div id="chart-sales-trend" style="width: 100%; height: 400px;"></div>
        </div>
    </div>

    <div class="scroll-reveal admin-outer-shell">
        <div class="admin-inner-core p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-sm font-mono uppercase tracking-widest text-[#787774]">Daily Aggregation</h2>
                <span class="text-xs text-[#787774]">Sorted chronologically</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAEAEA] text-xs font-mono uppercase tracking-widest text-[#787774]">
                            <th class="py-4 font-normal">Date</th>
                            <th class="py-4 font-normal text-right">Revenue</th>
                            <th class="py-4 font-normal text-right hidden sm:table-cell">COGS</th>
                            <th class="py-4 font-normal text-right">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAEAEA]">
                        @forelse($dailyData as $date => $data)
                            <tr class="hover:bg-[#F9F9F8]/50 transition-colors">
                                <td class="py-4">
                                    <p class="font-medium text-sm text-[#111111]">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                                </td>
                                <td class="py-4 text-right">
                                    <p class="font-mono text-sm text-[#111111]">{{ \App\Services\CurrencyService::format($data['revenue']) }}</p>
                                </td>
                                <td class="py-4 text-right hidden sm:table-cell">
                                    <p class="font-mono text-sm text-[#787774]">{{ \App\Services\CurrencyService::format($data['cogs']) }}</p>
                                </td>
                                <td class="py-4 text-right">
                                    <p class="font-mono text-sm {{ $data['margin'] > 0 ? 'text-[#346538]' : 'text-[#9F2F2D]' }} font-medium">
                                        {{ \App\Services\CurrencyService::format($data['margin']) }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center">
                                    <i class="ph-light ph-chart-line-down text-4xl text-[#EAEAEA] mb-2"></i>
                                    <p class="text-sm text-[#787774]">No financial data available for this period.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@php
    // Prepare data for Highcharts
    $chartCategories = array_keys($dailyData);
    $chartRevenue = array_map(function($item) { return (float)$item['revenue']; }, array_values($dailyData));
@endphp

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categories = @json($chartCategories);
    const revenueData = @json($chartRevenue);

    if (categories.length === 0) return;

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

    Highcharts.chart('chart-sales-trend', {
        chart: { type: 'spline' },
        title: { text: 'Revenue Trend' },
        xAxis: { categories: categories },
        yAxis: { title: { text: 'Revenue ($)' } },
        series: [{ name: 'Revenue', data: revenueData, color: '#111111' }]
    });
});
</script>
@endsection
