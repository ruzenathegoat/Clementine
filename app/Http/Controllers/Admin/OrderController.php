<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Order::with('user')->withSum('items', 'quantity');
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('id', 'ilike', '%' . $search . '%')
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'ilike', '%' . $search . '%')
                         ->orWhere('email', 'ilike', '%' . $search . '%');
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.orders.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = \App\Models\Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,verified,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $statusChangedToShipped = false;
        if ($validated['status'] === 'shipped' && $order->status !== 'shipped' && empty($order->shipped_at)) {
            $validated['shipped_at'] = now();
            $statusChangedToShipped = true;
        }

        $order->update($validated);

        if ($statusChangedToShipped && $order->contact_email) {
            \Illuminate\Support\Facades\Mail::to($order->contact_email)->send(new \App\Mail\OrderShippedMail($order));
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function refundToClementpay(string $id)
    {
        $order = \App\Models\Order::findOrFail($id);

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be refunded.');
        }

        // Check if already refunded
        if ($order->payment_status === 'refunded') {
            return back()->with('error', 'Order is already refunded.');
        }

        $user = $order->user;
        if (!$user) {
            return back()->with('error', 'No user attached to this order.');
        }

        // Update user balance
        $user->increment('clementpay_balance', $order->total);

        // Record transaction
        \App\Models\ClementpayTransaction::create([
            'user_id' => $user->id,
            'type' => 'refund',
            'amount' => $order->total,
            'description' => 'Refund for Order #' . $order->id,
            'status' => 'success',
        ]);

        // Update order status
        $order->update(['payment_status' => 'refunded']);

        return back()->with('success', 'Refund processed successfully to Clementpay.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
