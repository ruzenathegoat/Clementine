<x-mail::message>
# ACQUISITION CONFIRMED

**REFERENCE:** #{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}  
**DATE:** {{ $order->created_at->format('d M Y') }}

Your allocation has been secured. The official folio and provenance documents have been generated. Below is the summary of your acquisition.

---

### INVOICE DETAILS

<x-mail::table>
| Item       | Qty         | Price  |
| :--------- | :--------- | :----- |
@foreach($order->items as $item)
| **{{ $item->product->name }}**<br><span style="color: #666; font-size: 12px;">{{ $item->product->collection->name ?? 'Clementine' }}</span> | {{ $item->quantity }} | ${{ number_format($item->price_at_purchase, 2) }} |
@endforeach
</x-mail::table>

---

<x-mail::table>
| | |
| :--- | ---: |
| **Subtotal (Excl. Tax)** | ${{ number_format($order->subtotal, 2) }} |
@if($order->discount_amount > 0)
| **Discount** | -${{ number_format($order->discount_amount, 2) }} |
@endif
@if($order->tax > 0)
| **Product Tax** | ${{ number_format($order->tax, 2) }} |
@endif
| **Shipping Fee** | ${{ number_format($order->shipping_fee, 2) }} |
@if($order->shipping_tax > 0)
| **Shipping Tax** | ${{ number_format($order->shipping_tax, 2) }} |
@endif
| **TOTAL SETTLED** | **${{ number_format($order->total, 2) }}** |
</x-mail::table>

<x-mail::button :url="route('profile.index')">
ACCESS COLLECTION
</x-mail::button>

Sincerely,<br>
**CLEMENTINE HOROLOGY**
</x-mail::message>
