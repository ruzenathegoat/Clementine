<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClementpayTransaction;

class DummyPaymentGatewayController extends Controller
{
    public function show($type, $reference_id, $amount)
    {
        return view('payment.dummy_qris', compact('type', 'reference_id', 'amount'));
    }

    public function simulateSuccess(Request $request, $type, $reference_id)
    {
        $amount = $request->amount;

        if ($type === 'topup') {
            $user = auth()->user();
            $user->increment('clementpay_balance', $amount);

            ClementpayTransaction::create([
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => $amount,
                'description' => 'Topup via QRIS',
                'status' => 'success',
            ]);

            return redirect()->route('clementpay.index')->with('success', 'Topup successful!');
        }

        if ($type === 'order') {
            $order = \App\Models\Order::findOrFail($reference_id);
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);
            
            // Send email
            $order->load('items.product.collection');
            $recipient = $order->contact_email ?? auth()->user()?->email;
            if ($recipient) {
                try {
                    $html = view('emails.orders.paid', ['order' => $order])->render();
                    $orderId = strtoupper(substr(str_replace('-', '', $order->id), -8));
                    
                    $resend = \Resend::client(config('resend.api_key'));
                    $resend->emails->send([
                        'from' => 'Clementine <' . config('mail.from.address') . '>',
                        'to' => [$recipient],
                        'subject' => 'Acquisition Confirmed - #' . $orderId,
                        'html' => $html,
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('OrderPaid: email FAILED via SDK', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
            }

            return redirect()->route('orders.show', $order->id)->with('success', 'QRIS Payment simulation successful.');
        }

        return redirect()->route('home')->with('success', 'Payment successful!');
    }
}
