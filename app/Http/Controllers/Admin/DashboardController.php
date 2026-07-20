<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Collection;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ---------------------------------------------------------
        // 1. Core Metrics (Existing)
        // ---------------------------------------------------------
        $currentMonthRevenue = Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($currentMonthRevenue > 0) {
            $revenueGrowth = 100; 
        }

        $currentMonthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $lastMonthOrders = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $ordersGrowth = 0;
        if ($lastMonthOrders > 0) {
            $ordersGrowth = (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
        } elseif ($currentMonthOrders > 0) {
            $ordersGrowth = 100;
        }

        $metrics = [
            'total_orders' => Order::count(),
            'orders_growth' => $ordersGrowth,
            'revenue' => Order::whereIn('status', ['processing', 'shipped', 'completed'])->sum('total'),
            'revenue_growth' => $revenueGrowth,
            'vip_customers' => User::where('is_vip', true)->count(),
            'total_products' => Product::count(),
        ];

        // ---------------------------------------------------------
        // 2. Business Intelligence (BI) Data
        // ---------------------------------------------------------
        
        $validStatuses = ['processing', 'shipped', 'completed'];

        // A. Sales Trends (Daily, Weekly, Monthly)
        $lastYearOrders = Order::whereIn('status', $validStatuses)
            ->where('created_at', '>=', now()->subMonths(12)->startOfMonth())
            ->get(['total', 'created_at']);

        $dailySales = [];
        $last30Days = $lastYearOrders->filter(fn($o) => $o->created_at >= now()->subDays(30));
        foreach ($last30Days as $o) {
            $date = $o->created_at->format('Y-m-d');
            $dailySales[$date] = ($dailySales[$date] ?? 0) + $o->total;
        }
        ksort($dailySales);
        
        $weeklySales = [];
        $last12Weeks = $lastYearOrders->filter(fn($o) => $o->created_at >= now()->subWeeks(12));
        foreach ($last12Weeks as $o) {
            $week = $o->created_at->format('Y-W');
            $weeklySales[$week] = ($weeklySales[$week] ?? 0) + $o->total;
        }
        ksort($weeklySales);

        $monthlySales = [];
        foreach ($lastYearOrders as $o) {
            $month = $o->created_at->format('Y-m');
            $monthlySales[$month] = ($monthlySales[$month] ?? 0) + $o->total;
        }
        ksort($monthlySales);

        // B. Top Products
        $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $validStatuses)
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // C. Top Collections
        $topCollections = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('collections', 'products.collection_id', '=', 'collections.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $validStatuses)
            ->select('collections.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('collections.id', 'collections.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // D. Repeat Customer Rate
        $usersWithOrders = Order::whereIn('status', $validStatuses)
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->get();
            
        $totalCustomers = $usersWithOrders->count();
        $repeatCustomers = $usersWithOrders->where('order_count', '>', 1)->count();
        $repeatRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;

        // E. RFM Analysis & Active/Inactive Customers
        $rfmDataRaw = Order::whereIn('status', $validStatuses)
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('MAX(created_at) as last_order_date'), DB::raw('COUNT(id) as frequency'), DB::raw('SUM(total) as monetary'))
            ->groupBy('user_id')
            ->get();

        $activeCount = 0;
        $inactiveCount = 0;
        $rfmVisualData = [];

        foreach ($rfmDataRaw as $data) {
            $recency = Carbon::parse($data->last_order_date)->diffInDays(now());
            if ($recency <= 90) {
                $activeCount++;
            } else {
                $inactiveCount++;
            }
            
            $rfmVisualData[] = [
                'x' => $recency,
                'y' => (int) $data->frequency,
                'z' => (float) $data->monetary,
                'name' => 'User ID: ' . $data->user_id
            ];
        }
        
        // F. Sales by Region
        $regions = Order::whereIn('status', $validStatuses)
            ->whereNotNull('shipping_city')
            ->select('shipping_city as name', DB::raw('COUNT(*) as total_orders'))
            ->groupBy('shipping_city')
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        // G. Simple Stock Prediction
        $stockPrediction = Product::select('products.name', 'products.stock')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->whereIn('orders.status', $validStatuses)
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as predicted_demand')
            ->groupBy('products.id', 'products.name', 'products.stock')
            ->orderByDesc('predicted_demand')
            ->limit(10)
            ->get();

        $biData = [
            'sales' => [
                'daily' => ['categories' => array_keys($dailySales), 'data' => array_values($dailySales)],
                'weekly' => ['categories' => array_keys($weeklySales), 'data' => array_values($weeklySales)],
                'monthly' => ['categories' => array_keys($monthlySales), 'data' => array_values($monthlySales)],
            ],
            'top_products' => [
                'categories' => $topProducts->pluck('name')->toArray(),
                'data' => $topProducts->pluck('total_sold')->map(fn($v) => (int) $v)->toArray()
            ],
            'top_collections' => [
                'data' => $topCollections->map(fn($c) => ['name' => $c->name, 'y' => (int)$c->total_sold])->toArray()
            ],
            'customer_retention' => [
                'repeat_rate' => round($repeatRate, 1),
                'repeat_count' => $repeatCustomers,
                'first_time_count' => $totalCustomers - $repeatCustomers
            ],
            'rfm' => $rfmVisualData,
            'customer_status' => [
                'active' => $activeCount,
                'inactive' => $inactiveCount
            ],
            'regions' => [
                'categories' => $regions->pluck('name')->toArray(),
                'data' => $regions->pluck('total_orders')->map(fn($v) => (int) $v)->toArray()
            ],
            'stock_prediction' => [
                'categories' => $stockPrediction->pluck('name')->toArray(),
                'stock' => $stockPrediction->pluck('stock')->map(fn($v) => (int) $v)->toArray(),
                'predicted' => $stockPrediction->pluck('predicted_demand')->map(fn($v) => (int) $v)->toArray()
            ]
        ];
        
        return view('admin.dashboard', compact('metrics', 'biData'));
    }
}
