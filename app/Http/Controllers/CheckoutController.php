<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        $cartItems = CartItem::with(['product'])->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $isVip = auth()->user()?->is_vip ?? false;
        $subtotal = 0;
        $totalDiscount = 0;
        $totalTax = 0;
        $hasTheDrop = false;

        foreach ($cartItems as $item) {
            $productPrice = $item->product->price;
            $qty = $item->quantity;
            $lineTotal = $productPrice * $qty;
            $subtotal += $lineTotal;

            $discountPct = 0;
            $taxPct = 0.11; // Active (Regular)

            if ($item->product->status === 'new') { // The Drop
                $hasTheDrop = true;
                $discountPct = $isVip ? 0.07 : 0.05;
                $taxPct = $isVip ? 0.08 : 0.10;
            }

            $itemDiscount = $lineTotal * $discountPct;
            $itemTax = ($lineTotal - $itemDiscount) * $taxPct;

            $totalDiscount += $itemDiscount;
            $totalTax += $itemTax;
        }

        $shippingFee = 15.00;
        $defaultShippingTaxPct = ($isVip && $hasTheDrop) ? 0.03 : 0.05;
        $shippingTax = $shippingFee * $defaultShippingTaxPct;
        
        $total = $subtotal - $totalDiscount + $totalTax + $shippingFee + $shippingTax;

        return view('checkout.index', compact('cartItems', 'subtotal', 'totalDiscount', 'totalTax', 'shippingFee', 'shippingTax', 'total', 'defaultShippingTaxPct'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        
        $throttleKey = 'checkout|' . $userId . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['error' => "Too many checkout attempts. Please try again in {$seconds} seconds."]);
        }
        RateLimiter::hit($throttleKey, 60);

        $cartItems = CartItem::with(['product', 'strapOption'])->where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $request->validate([
            'contact_email' => 'required|email',
            'shipping_full_name' => 'required',
            'shipping_address1' => 'required',
            'shipping_city' => 'required',
            'shipping_postal_code' => 'required',
            'shipping_country' => 'required',
            'payment_method' => 'required|in:card,virtual_account',
            'bank' => 'required_if:payment_method,virtual_account',
        ]);

        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->stock < $item->quantity) {
                return back()->withErrors(['error' => 'Insufficient stock for ' . $product->name]);
            }
            
            // CRM VIP Logic Validation
            if ($product->status === 'new' && $product->scheduled_publish_at) {
                $now = now();
                $t = $product->scheduled_publish_at;
                $isVip = auth()->user()?->is_vip ?? false;
                
                if ($now->lt($t)) {
                    return back()->withErrors(['error' => $product->name . ' is not yet available for purchase.']);
                }
                
                $t10 = (clone $t)->addMinutes(10);
                $t40 = (clone $t)->addMinutes(40);
                
                if ($now->between($t, $t10) && !$isVip) {
                    return back()->withErrors(['error' => 'Exclusive VIP access for ' . $product->name . '. Regular sales open at ' . $t10->format('H:i')]);
                }
                
                if ($now->lt($t40)) {
                    $pastPurchases = \App\Models\OrderItem::whereHas('order', function($q) use ($userId) {
                        $q->where('user_id', $userId)->where('status', '!=', 'cancelled');
                    })->where('product_id', $product->id)->sum('quantity');

                    $maxQty = $isVip ? 3 : 1;
                    if (($item->quantity + $pastPurchases) > $maxQty) {
                        return back()->withErrors(['error' => 'You have reached your purchase limit for ' . $product->name . '. (Max: ' . $maxQty . ', Purchased: ' . $pastPurchases . ')']);
                    }
                }
            }
        }

        $isVip = auth()->user()?->is_vip ?? false;
        $subtotal = 0;
        $totalDiscount = 0;
        $totalTax = 0;
        $hasTheDrop = false;
        $orderItemsData = [];

        foreach ($cartItems as $item) {
            $productPrice = $item->product->price;
            $qty = $item->quantity;
            $lineTotal = $productPrice * $qty;
            $subtotal += $lineTotal;

            $discountPct = 0;
            $taxPct = 0.11; // Active (Regular)

            if ($item->product->status === 'new') { // The Drop
                $hasTheDrop = true;
                $discountPct = $isVip ? 0.07 : 0.05;
                $taxPct = $isVip ? 0.08 : 0.10;
            }

            $itemDiscount = $lineTotal * $discountPct;
            $itemTax = ($lineTotal - $itemDiscount) * $taxPct;

            $totalDiscount += $itemDiscount;
            $totalTax += $itemTax;
            
            $orderItemsData[] = [
                'cartItem' => $item,
                'tax_amount' => $itemTax,
                'discount_amount' => $itemDiscount
            ];
        }
        
        $shippingFee = 15.00;
        $isDomestic = ($request->shipping_country === 'ID' || strtolower($request->shipping_country) === 'indonesia');
        $shippingTaxPct = 0.05;
        if ($isDomestic) {
            $shippingTaxPct = 0;
        } else if ($isVip && $hasTheDrop) {
            $shippingTaxPct = 0.03;
        }
        $shippingTax = $shippingFee * $shippingTaxPct;

        $tax = $totalTax;
        $total = $subtotal - $totalDiscount + $tax + $shippingFee + $shippingTax;

        try {
            $order = DB::transaction(function () use ($request, $userId, $orderItemsData, $subtotal, $totalDiscount, $shippingFee, $shippingTax, $tax, $total) {
                
                // 1. Acquire pessimistic lock on products
                $productIds = collect($orderItemsData)->pluck('cartItem.product_id')->unique()->toArray();
                $lockedProducts = \App\Models\Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');
                
                // 2. Validate stock again strictly under lock
                foreach ($orderItemsData as $data) {
                    $item = $data['cartItem'];
                    $lockedProduct = $lockedProducts[$item->product_id];
                    if ($lockedProduct->stock < $item->quantity) {
                        throw new \Exception('Insufficient stock for ' . $lockedProduct->name);
                    }
                }

                $status = $request->payment_method === 'virtual_account' ? 'pending' : 'processing';
                $paymentStatus = $request->payment_method === 'virtual_account' ? 'pending' : 'paid';
                $paymentDetails = null;

                if ($request->payment_method === 'virtual_account') {
                    $bankCodes = [
                        'BCA' => '014',
                        'Mandiri' => '008',
                        'BNI' => '009',
                        'BRI' => '002',
                        'Permata' => '013'
                    ];
                    $bankCode = $bankCodes[$request->bank] ?? '999';
                    $vaNumber = $bankCode . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
                    
                    $paymentDetails = [
                        'bank' => $request->bank,
                        'va_number' => $vaNumber
                    ];
                }

                $order = Order::create([
                    'user_id' => $userId,
                    'status' => $status,
                    'contact_email' => $request->contact_email,
                    'shipping_full_name' => $request->shipping_full_name,
                    'shipping_address1' => $request->shipping_address1,
                    'shipping_address2' => $request->shipping_address2,
                    'shipping_city' => $request->shipping_city,
                    'shipping_postal_code' => $request->shipping_postal_code,
                    'shipping_country' => $request->shipping_country,
                    'billing_same_as_shipping' => $request->boolean('billing_same_as_shipping'),
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'payment_details' => $paymentDetails,
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'shipping_tax' => $shippingTax,
                    'tax' => $tax,
                    'discount_amount' => $totalDiscount,
                    'promo_code_id' => null,
                    'total' => $total,
                ]);

                foreach ($orderItemsData as $data) {
                    $item = $data['cartItem'];
                    $lockedProduct = $lockedProducts[$item->product_id];
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'strap_option_id' => $item->strap_option_id,
                        'quantity' => $item->quantity,
                        'price_at_purchase' => $lockedProduct->price,
                        'tax_amount' => $data['tax_amount'],
                        'discount_amount' => $data['discount_amount'],
                    ]);

                    // Reduce stock using the locked product
                    $lockedProduct->stock -= $item->quantity;
                    if ($lockedProduct->stock <= 0) {
                        $lockedProduct->stock = 0;
                        if ($lockedProduct->status === 'new') {
                            $lockedProduct->status = 'active';
                        }
                    }
                    $lockedProduct->save();
                }

                CartItem::where('user_id', $userId)->delete();

                return $order;
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        if ($order->payment_method === 'virtual_account') {
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully. Please complete your payment.');
        }

        if ($order->payment_status === 'paid') {
            try {
                Mail::to($order->contact_email ?? $request->user()->email)->send(new OrderPaid($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send OrderPaid email: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Checkout successful!');
    }
}
