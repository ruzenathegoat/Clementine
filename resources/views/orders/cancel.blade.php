@extends('layouts.app')

@section('title', 'Cancel Order - Clementine')

@section('content')
<div class="w-full max-w-[800px] mx-auto px-6 py-12 lg:py-24">
    <div class="mb-12">
        <a href="{{ route('profile.index') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors mb-6">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Back to Profile
        </a>
        <h1 class="font-headline-lg text-4xl md:text-5xl uppercase tracking-tighter mb-4">
            Cancel Order
        </h1>
        <p class="text-on-surface-variant font-body-md">
            Please let us know why you are cancelling order <strong>#{{ strtoupper(substr(str_replace('-', '', $order->id), -8)) }}</strong>.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-600 text-red-600 font-body-md text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="border border-outline-variant bg-surface-container-lowest p-8 md:p-12">
        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="flex flex-col gap-8">
            @csrf

            <div class="flex flex-col gap-3">
                <label for="cancel_reason" class="font-label-caps text-xs uppercase tracking-widest font-bold">Reason for Cancellation <span class="text-red-600">*</span></label>
                <div class="relative">
                    <select id="cancel_reason" name="cancel_reason" class="w-full p-4 border border-outline-variant bg-transparent font-body-md text-sm appearance-none focus:border-primary focus:ring-0 rounded-none cursor-pointer" required>
                        <option value="" disabled selected>Select a reason</option>
                        <option value="Changed my mind" {{ old('cancel_reason') == 'Changed my mind' ? 'selected' : '' }}>Changed my mind</option>
                        <option value="Found a cheaper alternative" {{ old('cancel_reason') == 'Found a cheaper alternative' ? 'selected' : '' }}>Found a cheaper alternative</option>
                        <option value="Shipping time is too long" {{ old('cancel_reason') == 'Shipping time is too long' ? 'selected' : '' }}>Shipping time is too long</option>
                        <option value="Payment issues" {{ old('cancel_reason') == 'Payment issues' ? 'selected' : '' }}>Payment issues</option>
                        <option value="Placed order by mistake" {{ old('cancel_reason') == 'Placed order by mistake' ? 'selected' : '' }}>Placed order by mistake</option>
                        <option value="Other" {{ old('cancel_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <label for="cancel_description" class="font-label-caps text-xs uppercase tracking-widest font-bold">Additional Details <span class="text-red-600">*</span></label>
                <p class="text-xs text-on-surface-variant font-body-md mb-1">Please provide more information to help us improve.</p>
                <textarea id="cancel_description" name="cancel_description" rows="5" class="w-full p-4 border border-outline-variant bg-transparent font-body-md text-sm focus:border-primary focus:ring-0 rounded-none resize-y" required placeholder="Type your reason here...">{{ old('cancel_description') }}</textarea>
            </div>

            <div class="bg-surface-variant p-6 border-l-4 border-primary mt-4">
                <h4 class="font-bold text-sm uppercase tracking-widest mb-2">Important Note</h4>
                <p class="font-body-md text-sm text-on-surface-variant">
                    By confirming this cancellation, your order will be permanently cancelled. If you have already paid, your funds will be immediately refunded to your Clementpay balance. This action cannot be undone.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mt-4 pt-8 border-t border-outline-variant">
                <a href="{{ route('profile.index') }}" class="flex-1 text-center px-8 py-4 border border-outline-variant text-on-surface-variant font-bold uppercase tracking-widest text-xs hover:bg-surface transition-colors">
                    Keep Order
                </a>
                <button type="submit" class="flex-1 px-8 py-4 bg-red-600 text-white font-bold uppercase tracking-widest text-xs hover:opacity-90 transition-opacity border border-red-600">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
