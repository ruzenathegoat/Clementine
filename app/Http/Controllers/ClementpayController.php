<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClementpayTransaction;
use Illuminate\Support\Str;

class ClementpayController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->clementpayTransactions()->latest()->paginate(10);
        return view('user.clementpay.index', compact('transactions'));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100', // Minimum 100
        ]);

        $referenceId = 'CP-' . strtoupper(Str::random(10));

        // Redirect to dummy QRIS gateway
        return redirect()->route('dummy.qris', [
            'type' => 'topup',
            'reference_id' => $referenceId,
            'amount' => $request->amount,
        ]);
    }
}
