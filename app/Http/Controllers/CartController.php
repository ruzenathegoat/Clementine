<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $cartItems = CartItem::with(['product', 'strapOption'])
            ->where('user_id', $userId)
            ->get();
            
        $isVip = auth()->user()?->is_vip ?? false;
        
        $subtotal = 0;
        $totalDiscount = 0;
        
        foreach ($cartItems as $item) {
            $productPrice = $item->product->price;
            $qty = $item->quantity;
            $lineTotal = $productPrice * $qty;
            
            $subtotal += $lineTotal;
            
            $discountPct = 0;
            if ($item->product->status === 'new') { // The Drop
                $discountPct = $isVip ? 0.07 : 0.05;
            } else {
                // Non-drop products
                if ($isVip) {
                    $discountPct = 0.03;
                }
            }
            $totalDiscount += ($lineTotal * $discountPct);
        }
        
        $shipping = $cartItems->isEmpty() ? 0 : 15;
        $total = $subtotal - $totalDiscount + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'totalDiscount', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'strap_option_id' => 'nullable|exists:product_strap_options,id',
        ]);

        $userId = auth()->id();
        $product = Product::findOrFail($request->product_id);

        if ($product->status === 'new' && $product->scheduled_publish_at) {
            $now = now();
            $t = $product->scheduled_publish_at;
            $user = auth()->user();
            $isVip = $user ? $user->is_vip : false;
            
            if ($now->lt($t)) {
                return back()->withErrors(['error' => 'Product is not yet available for purchase.']);
            }
            
            $t10 = (clone $t)->addMinutes(10);
            $t40 = (clone $t)->addMinutes(40);
            
            if ($now->between($t, $t10)) {
                if (!$isVip) {
                    return back()->withErrors(['error' => 'Exclusive VIP access. Regular sales open at ' . $t10->format('H:i')]);
                }
            }
            
            if ($now->lt($t40)) {
                $maxQty = $isVip ? 3 : 1;
                
                $existingQty = CartItem::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->sum('quantity');
                    
                if (($existingQty + $request->quantity) > $maxQty) {
                    return back()->withErrors(['error' => 'You can only purchase a maximum of ' . $maxQty . ' item(s) for New Arrivals.']);
                }
            }
        }

        $cartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('strap_option_id', $request->strap_option_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'strap_option_id' => $request->strap_option_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Track Add to Cart
        AnalyticsEvent::create([
            'user_id' => Auth::id(),
            'session_id' => Session::getId(),
            'event_type' => 'add_to_cart',
            'product_id' => $request->product_id,
            'payload' => [
                'quantity' => $request->quantity,
                'strap_option_id' => $request->strap_option_id,
            ]
        ]);

        return redirect()->route('cart.index')->with('success', 'ITEM ADDED TO CART.');
    }

    public function update(Request $request, CartItem $cart)
    {
        $userId = auth()->id();
        if ((int)$cart->user_id !== (int)$userId) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = $cart->product;
        if ($product->status === 'new' && $product->scheduled_publish_at) {
             $t40 = (clone $product->scheduled_publish_at)->addMinutes(40);
             
             if (now()->lt($t40)) {
                 $maxQty = (auth()->user()?->is_vip) ? 3 : 1;
                 
                 $otherQty = CartItem::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->where('id', '!=', $cart->id)
                    ->sum('quantity');
                    
                 if (($otherQty + $request->quantity) > $maxQty) {
                      return back()->withErrors(['error' => 'You can only purchase a maximum of ' . $maxQty . ' item(s) for New Arrivals.']);
                 }
             }
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        if ($request->wantsJson()) {
            return $this->getCartTotalsResponse();
        }

        return back()->with('success', 'CART UPDATED.');
    }

    public function destroy(CartItem $cart, Request $request)
    {
        $userId = auth()->id();
        if ((int)$cart->user_id !== (int)$userId) {
            abort(403);
        }

        $cart->delete();

        if ($request->wantsJson()) {
            return $this->getCartTotalsResponse();
        }

        return back()->with('success', 'ITEM REMOVED.');
    }

    private function getCartTotalsResponse()
    {
        $userId = auth()->id();
        $cartItems = CartItem::with(['product'])
            ->where('user_id', $userId)
            ->get();
            
        $isVip = auth()->user()?->is_vip ?? false;
        
        $subtotal = 0;
        $totalDiscount = 0;
        
        foreach ($cartItems as $item) {
            $productPrice = $item->product->price;
            $qty = $item->quantity;
            $lineTotal = $productPrice * $qty;
            
            $subtotal += $lineTotal;
            
            $discountPct = 0;
            if ($item->product->status === 'new') {
                $discountPct = $isVip ? 0.07 : 0.05;
            } else {
                if ($isVip) {
                    $discountPct = 0.03;
                }
            }
            $totalDiscount += ($lineTotal * $discountPct);
        }
        
        $shipping = $cartItems->isEmpty() ? 0 : 15;
        $total = $subtotal - $totalDiscount + $shipping;
        
        return response()->json([
            'subtotal' => $subtotal,
            'totalDiscount' => $totalDiscount,
            'shipping' => $shipping,
            'total' => $total,
            'isEmpty' => $cartItems->isEmpty()
        ]);
    }
}
