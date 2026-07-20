@extends('admin.layout')

@section('title', 'Customers')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#EAEAEA] text-[#111111]">CRM</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Customers</h1>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search Form -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full md:w-64">
                <i class="ph-light ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#787774]"></i>
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search name or email..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#EAEAEA] rounded-full text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow"
                >
                @if(request('is_vip'))
                    <input type="hidden" name="is_vip" value="{{ request('is_vip') }}">
                @endif
            </form>
            
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-full border {{ request()->has('is_vip') && request('is_vip') !== '' ? 'border-[#EAEAEA] text-[#787774] hover:border-black hover:text-black' : 'border-black text-black bg-white' }} text-sm transition-colors hidden sm:block">All</a>
            <a href="{{ route('admin.users.index', ['is_vip' => '1', 'q' => request('q')]) }}" class="px-4 py-2 rounded-full border {{ request('is_vip') == '1' ? 'border-[#111111] text-white bg-[#111111]' : 'border-[#EAEAEA] text-[#787774] hover:border-black hover:text-black' }} text-sm transition-colors hidden sm:block flex items-center gap-1">
                <i class="ph-fill ph-star"></i> VIP Only
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- BI Dashboard Section for Customers -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6 auto-rows-[auto]">
        <!-- Customer Retention -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-retention" style="width: 100%; height: 300px;"></div>
            </div>
        </div>
        
        <!-- Customer Status -->
        <div class="scroll-reveal md:col-span-6 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-customer-status" style="width: 100%; height: 300px;"></div>
            </div>
        </div>

        <!-- RFM Analysis -->
        <div class="scroll-reveal md:col-span-12 admin-outer-shell group">
            <div class="admin-inner-core h-full p-6 relative">
                <div id="chart-rfm" style="width: 100%; height: 450px;"></div>
                <p class="text-xs text-[#787774] mt-4 text-center">X: Recency (Days), Y: Frequency (Orders), Bubble Size: Monetary (Revenue)</p>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <div class="scroll-reveal admin-outer-shell">
        <div class="admin-inner-core">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAEAEA] text-xs font-mono uppercase tracking-widest text-[#787774] bg-[#F9F9F8]">
                            <th class="px-6 py-4 font-normal">Customer</th>
                            <th class="px-6 py-4 font-normal hidden sm:table-cell">Registered</th>
                            <th class="px-6 py-4 font-normal">Orders</th>
                            <th class="px-6 py-4 font-normal text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAEAEA]">
                        @forelse($users as $user)
                            <tr class="group hover:bg-[#F9F9F8]/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.users.show', $user->id) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="relative inline-block w-10 h-10">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-[#EAEAEA] text-[#787774] flex items-center justify-center font-serif text-lg shrink-0">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            @if($user->is_vip)
                                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-[#111111] text-white rounded-full flex items-center justify-center border-2 border-white z-10">
                                                    <i class="ph-fill ph-star text-[8px]"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-[#111111] leading-tight">{{ $user->name }}</p>
                                                @if($user->is_vip)
                                                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#111111] bg-[#EAEAEA] px-1.5 py-0.5 rounded">VIP</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-[#787774] mt-0.5">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <p class="text-sm text-[#787774]">{{ $user->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-[#111111]">{{ $user->orders_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <i class="ph-light ph-caret-right text-lg text-[#787774] group-hover:text-[#111111] transition-colors"></i>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <i class="ph-light ph-users text-4xl text-[#EAEAEA] mb-2"></i>
                                    <p class="text-sm text-[#787774]">No customers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
            <div class="border-t border-[#EAEAEA] p-4 bg-white">
                {{ $users->links('pagination::tailwind') }}
            </div>
            @endif
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

    // Customer Retention
    if (biData.customer_retention) {
        Highcharts.chart('chart-retention', {
            chart: { type: 'pie' },
            title: { text: 'Customer Retention Rate' },
            tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y} users)' },
            plotOptions: {
                pie: { innerSize: '60%', dataLabels: { enabled: true, format: '<b>{point.name}</b>' } }
            },
            series: [{
                name: 'Customers',
                data: [
                    { name: 'Repeat', y: biData.customer_retention.repeat_count, color: '#346538' },
                    { name: 'First-time', y: biData.customer_retention.first_time_count, color: '#EAEAEA' }
                ]
            }]
        });
    }

    // Customer Status
    if (biData.customer_status) {
        Highcharts.chart('chart-customer-status', {
            chart: { type: 'pie' },
            title: { text: 'Active vs Inactive (< 90 Days)' },
            tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y} users)' },
            plotOptions: {
                pie: { dataLabels: { enabled: true, format: '<b>{point.name}</b>' } }
            },
            series: [{
                name: 'Status',
                data: [
                    { name: 'Active', y: biData.customer_status.active, color: '#1F6C9F' },
                    { name: 'Inactive', y: biData.customer_status.inactive, color: '#9F2F2D' }
                ]
            }]
        });
    }

    // RFM Analysis
    if (biData.rfm && biData.rfm.length > 0) {
        Highcharts.chart('chart-rfm', {
            chart: { type: 'bubble', zoomType: 'xy' },
            title: { text: 'RFM Analysis (Recency, Frequency, Monetary)' },
            xAxis: { title: { text: 'Recency (Days since last order)' }, reversed: true },
            yAxis: { title: { text: 'Frequency (Number of orders)' } },
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
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}',
                        style: { fontSize: '10px', textOutline: 'none', fontWeight: 'normal' }
                    }
                }
            },
            series: [{
                name: 'Customers',
                data: biData.rfm,
                color: 'rgba(17, 17, 17, 0.5)',
                marker: {
                    fillColor: {
                        radialGradient: { cx: 0.4, cy: 0.3, r: 0.7 },
                        stops: [
                            [0, 'rgba(255,255,255,0.5)'],
                            [1, 'rgba(17,17,17,0.5)']
                        ]
                    }
                }
            }]
        });
    }
});
</script>
@endsection
