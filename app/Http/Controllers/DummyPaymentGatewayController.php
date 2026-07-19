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

        // Future types can be handled here (like direct QRIS for order)

        return redirect()->route('home')->with('success', 'Payment successful!');
    }
}
