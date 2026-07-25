public function verify(Request $request)
{
    $sn = strtoupper(trim($request->input('certificate_sn')));

    $item = null;

    if ($sn) {
        $item = OrderItem::with(['product.collection', 'order', 'strapOption'])
            ->where('certificate_sn', $sn)
            ->first();
    }

    if (!$request->ajax()) {
        return view('docs.index', [
            'searched_sn' => $sn,
            'verified_item' => $item,
        ]);
    }

    if (!$item || !$item->product || !$item->order) {
        return response()->json([
            'success' => false,
            'message' => 'Certificate not found.',
        ]);
    }

    return response()->json([
        'success' => true,
        'certificate' => [
            'sn' => $item->certificate_sn,
            'product_name' => $item->product->name,
            'order_id' => strtoupper(substr(str_replace('-', '', $item->order_id), -8)),
            'date' => $item->order->created_at->format('d M Y'),
            'strap' => $item->strapOption?->name ?? 'N/A',
        ],
    ]);
}