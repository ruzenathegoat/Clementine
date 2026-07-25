<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;

class DocsController extends Controller
{
    public function index(Request $request)
    {
        return view('docs.index');
    }

    public function verify(Request $request)
    {
        $sn = $request->input('certificate_sn');
        
        $item = null;
        if ($sn) {
            $item = OrderItem::with(['product.collection', 'order', 'strapOption'])
                ->where('certificate_sn', strtoupper(trim($sn)))
                ->first();
        }

        if ($request->ajax()) {
            if ($item && $item->product && $item->order) {
                return response()->json([
                    'success' => true,
                    'certificate' => [
                        'sn' => $item->certificate_sn,
                        'product_name' => $item->product->name,
                        'order_id' => strtoupper(substr(str_replace('-', '', $item->order_id), -8)),
                        'date' => $item->order->created_at->format('d M Y'),
                        'strap' => $item->strapOption ? $item->strapOption->name : 'N/A',
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Certificate not found.'
            ]);
        }

        return view('docs.index', [
            'searched_sn' => $sn,
            'verified_item' => $item
        ]);
    }
}
