<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonthRevenue = \App\Models\Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $lastMonthRevenue = \App\Models\Order::whereIn('status', ['processing', 'shipped', 'completed'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');

        $revenueGrowth = 0;
        if ($lastMonthRevenue > 0) {
            $revenueGrowth = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } elseif ($currentMonthRevenue > 0) {
            $revenueGrowth = 100; 
        }

        $currentMonthOrders = \App\Models\Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $lastMonthOrders = \App\Models\Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        $ordersGrowth = 0;
        if ($lastMonthOrders > 0) {
            $ordersGrowth = (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100;
        } elseif ($currentMonthOrders > 0) {
            $ordersGrowth = 100;
        }

        $metrics = [
            'total_orders' => \App\Models\Order::count(),
            'orders_growth' => $ordersGrowth,
            'revenue' => \App\Models\Order::whereIn('status', ['processing', 'shipped', 'completed'])->sum('total'),
            'revenue_growth' => $revenueGrowth,
            'vip_customers' => \App\Models\User::where('is_vip', true)->count(),
            'total_products' => \App\Models\Product::count(),
        ];
        
        return view('admin.dashboard', compact('metrics'));
    }
}
