<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = Order::with('items')->whereIn('status', ['processing', 'shipped', 'completed']);

        if ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $orders = $query->get();

        $grossRevenue = 0;
        $totalCogs = 0;
        $dailyData = [];

        foreach ($orders as $order) {
            $grossRevenue += $order->total;
            
            $orderCogs = 0;
            foreach ($order->items as $item) {
                // If cogs_at_purchase is stored, use it. Otherwise, use price_at_purchase as fallback for dummy data if cogs is 0.
                $itemCogs = $item->cogs_at_purchase > 0 ? $item->cogs_at_purchase : ($item->price_at_purchase * 0.4); // fallback 40% margin for missing cogs
                $orderCogs += ($itemCogs * $item->quantity);
            }
            $totalCogs += $orderCogs;

            // Group for chart/export
            $date = $order->created_at->format('Y-m-d');
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = [
                    'revenue' => 0,
                    'cogs' => 0,
                    'margin' => 0
                ];
            }
            $dailyData[$date]['revenue'] += $order->total;
            $dailyData[$date]['cogs'] += $orderCogs;
            $dailyData[$date]['margin'] += ($order->total - $orderCogs);
        }

        ksort($dailyData); // sort by date ascending

        $grossMargin = $grossRevenue - $totalCogs;
        $marginPercentage = $grossRevenue > 0 ? ($grossMargin / $grossRevenue) * 100 : 0;

        return view('admin.financials.index', compact(
            'period', 
            'grossRevenue', 
            'totalCogs', 
            'grossMargin', 
            'marginPercentage',
            'dailyData'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = Order::with('items')->whereIn('status', ['processing', 'shipped', 'completed']);

        if ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $orders = $query->get();
        $dailyData = [];

        foreach ($orders as $order) {
            $orderCogs = 0;
            foreach ($order->items as $item) {
                $itemCogs = $item->cogs_at_purchase > 0 ? $item->cogs_at_purchase : ($item->price_at_purchase * 0.4);
                $orderCogs += ($itemCogs * $item->quantity);
            }

            $date = $order->created_at->format('Y-m-d');
            if (!isset($dailyData[$date])) {
                $dailyData[$date] = ['revenue' => 0, 'cogs' => 0, 'margin' => 0];
            }
            $dailyData[$date]['revenue'] += $order->total;
            $dailyData[$date]['cogs'] += $orderCogs;
            $dailyData[$date]['margin'] += ($order->total - $orderCogs);
        }
        
        ksort($dailyData);

        $fileName = 'financial_report_' . $period . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new FinancialReportExport($dailyData, $period), $fileName);
    }
}
