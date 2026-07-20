<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::where('role', 'customer');
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('email', 'ilike', '%' . $search . '%');
            });
        }
        
        if ($request->has('is_vip') && $request->is_vip !== '') {
            $query->where('is_vip', $request->is_vip == '1');
        }
        
        // Include orders count and sum for quick LTV in the table if we want, but for now just paginate
        $users = $query->withCount('orders')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15)
                       ->withQueryString();

        // ---------------------------------------------------------
        // Business Intelligence (BI) Data for Users
        // ---------------------------------------------------------
        $validStatuses = ['processing', 'shipped', 'completed'];

        // Repeat Customer Rate
        $usersWithOrders = \App\Models\Order::whereIn('status', $validStatuses)
            ->whereNotNull('user_id')
            ->select('user_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->get();
            
        $totalCustomers = $usersWithOrders->count();
        $repeatCustomers = $usersWithOrders->where('order_count', '>', 1)->count();
        $repeatRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;

        // RFM Analysis & Active/Inactive Customers
        $rfmDataRaw = \App\Models\Order::whereIn('status', $validStatuses)
            ->whereNotNull('user_id')
            ->select('user_id', \Illuminate\Support\Facades\DB::raw('MAX(created_at) as last_order_date'), \Illuminate\Support\Facades\DB::raw('COUNT(id) as frequency'), \Illuminate\Support\Facades\DB::raw('SUM(total) as monetary'))
            ->groupBy('user_id')
            ->get();

        $activeCount = 0;
        $inactiveCount = 0;
        $rfmVisualData = [];

        foreach ($rfmDataRaw as $data) {
            $recency = \Carbon\Carbon::parse($data->last_order_date)->diffInDays(now());
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

        $biData = [
            'customer_retention' => [
                'repeat_rate' => round($repeatRate, 1),
                'repeat_count' => $repeatCustomers,
                'first_time_count' => $totalCustomers - $repeatCustomers
            ],
            'rfm' => $rfmVisualData,
            'customer_status' => [
                'active' => $activeCount,
                'inactive' => $inactiveCount
            ]
        ];
        
        return view('admin.users.index', compact('users', 'biData'));
    }

    public function show(string $id)
    {
        $user = \App\Models\User::with(['orders' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        $ltv = $user->orders->whereIn('status', ['processing', 'shipped', 'completed'])->sum('total');
        
        return view('admin.users.show', compact('user', 'ltv'));
    }

    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $validated = $request->validate([
            'is_vip' => 'required|boolean',
        ]);

        $user->update(['is_vip' => $validated['is_vip']]);

        $status = $validated['is_vip'] ? 'VIP granted' : 'VIP revoked';
        return redirect()->route('admin.users.show', $id)->with('success', "Customer status updated: {$status}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
