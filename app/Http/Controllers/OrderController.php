<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function simulatePayment(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        // Simulate payment success
        $order->update([
            'status' => 'processing',
            'payment_status' => 'paid'
        ]);

        // Eager-load relationships needed by the OrderPaid email template
        $order->load('items.product.collection');

        try {
            Mail::to($order->contact_email ?? auth()->user()->email)
                ->send(new OrderPaid($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OrderPaid email: ' . $e->getMessage());
        }

        return back()->with('success', 'PAYMENT SIMULATION SUCCESSFUL.');
    }
}
