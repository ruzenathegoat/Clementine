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

        // Send Invoice Email via Mailpit
        Mail::to($order->contact_email)->send(new OrderPaid($order));

        return back()->with('success', 'PAYMENT SIMULATION SUCCESSFUL.');
    }
}
