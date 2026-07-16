<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVipStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_vip) {
            $ltv = \App\Models\Order::where('user_id', $request->user()->id)
                ->where('payment_status', 'paid')
                ->sum('total');

            if ($ltv >= 10000) {
                $request->user()->update(['is_vip' => true]);
                // Flash session so the frontend knows to show the modal
                session()->flash('just_upgraded_to_vip', true);
            }
        }

        return $next($request);
    }
}
