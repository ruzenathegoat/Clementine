<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $recipient = $order->contact_email ?? auth()->user()->email;
        Log::info('OrderPaid: attempting email', ['order_id' => $order->id, 'to' => $recipient, 'mailer' => config('mail.default')]);

        try {
            // Bypass Laravel Symfony Mailer and use Resend API directly to prevent Message-ID spam drops
            $html = view('emails.orders.paid', ['order' => $order])->render();
            $orderId = strtoupper(substr(str_replace('-', '', $order->id), -8));
            
            $resend = \Resend::client(config('resend.api_key'));
            $resend->emails->send([
                'from' => 'Clementine <' . config('mail.from.address') . '>',
                'to' => [$recipient],
                'subject' => 'Acquisition Confirmed - #' . $orderId,
                'html' => $html,
            ]);
            Log::info('OrderPaid: email sent successfully via SDK', ['order_id' => $order->id, 'to' => $recipient]);
        } catch (\Exception $e) {
            Log::error('OrderPaid: email FAILED via SDK', ['order_id' => $order->id, 'to' => $recipient, 'error' => $e->getMessage()]);
        }

        return back()->with('success', 'PAYMENT SIMULATION SUCCESSFUL.');
    }

    public function cancelForm(Order $order)
    {
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        // Check if within 15 minutes grace period
        if (now()->diffInMinutes($order->created_at) > 15) {
            return redirect()->route('profile.index')->with('error', 'CANCELLATION PERIOD (15 MINUTES) HAS EXPIRED.');
        }

        // Check if order status allows cancellation
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('profile.index')->with('error', 'ORDER CANNOT BE CANCELLED IN ITS CURRENT STATE.');
        }

        return view('orders.cancel', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        if ((int)$order->user_id !== (int)auth()->id()) {
            abort(403);
        }

        // Check if within 15 minutes grace period
        if (now()->diffInMinutes($order->created_at) > 15) {
            return back()->with('error', 'CANCELLATION PERIOD (15 MINUTES) HAS EXPIRED.');
        }

        // Check if order status allows cancellation
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'ORDER CANNOT BE CANCELLED IN ITS CURRENT STATE.');
        }

        $validated = $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_description' => 'required|string|max:1000',
        ]);

        // Change status
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $validated['cancel_reason'],
            'cancel_description' => $validated['cancel_description'],
        ]);

        // Auto restock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return back()->with('success', 'ORDER CANCELLED. STOCK RETURNED.');
    }
}
