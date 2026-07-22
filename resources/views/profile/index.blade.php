@extends('layouts.app')

@section('title', 'Profile - Clementine')

@section('content')
<div id="profile-dashboard" class="w-full max-w-[1400px] mx-auto px-6 py-12 lg:py-24" x-data="{ tab: 'settings', invoiceModalOpen: false, selectedOrder: null }">
    <div class="flex flex-col lg:flex-row gap-16 lg:gap-32">
        
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-[300px] flex-shrink-0">
            <h1 class="font-headline-lg text-5xl md:text-6xl uppercase tracking-tighter mb-12 flex flex-col md:flex-row md:items-center gap-4">
                MY <span class="font-serif italic  lowercase tracking-normal">account</span>
                @if($user->is_vip)
                    <span class="bg-primary text-white text-xs md:text-sm px-4 py-2 font-body-md font-bold uppercase tracking-widest whitespace-nowrap border border-primary text-center">VIP MEMBER</span>
                @endif
            </h1>
            
            <div class="flex flex-col gap-6 font-headline-md text-xl uppercase tracking-wide">
                <button @click="tab = 'settings'" class="text-left w-full border-b pb-4 transition-all duration-300 hover:pl-4 hover:text-primary hover:border-primary" :class="tab === 'settings' ? 'border-primary text-primary pl-4' : 'border-outline-variant text-on-surface-variant'">
                    Account Settings
                </button>
                <button @click="tab = 'orders_active'" class="text-left w-full border-b pb-4 transition-all duration-300 hover:pl-4 hover:text-primary hover:border-primary" :class="tab === 'orders_active' ? 'border-primary text-primary pl-4' : 'border-outline-variant text-on-surface-variant'">
                    Active Orders ({{ $activeOrders->count() }})
                </button>
                <button @click="tab = 'orders_past'" class="text-left w-full border-b pb-4 transition-all duration-300 hover:pl-4 hover:text-primary hover:border-primary" :class="tab === 'orders_past' ? 'border-primary text-primary pl-4' : 'border-outline-variant text-on-surface-variant'">
                    Order History ({{ $pastOrders->count() }})
                </button>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="text-left w-full  transition-all duration-300 hover:pl-2 hover:opacity-80">
                        Log out
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-grow">
            
            <!-- Settings Tab -->
            <div x-show="tab === 'settings'" x-cloak class="flex flex-col gap-12" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h2 class="font-label-caps text-sm font-bold uppercase tracking-widest text-primary border-b border-primary pb-4">Personal Information</h2>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-12">
                    @csrf
                    @method('PUT')
                    
                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-8">
                        <div class="relative w-32 h-32 border border-primary p-2 flex-shrink-0 group">
                            <img id="avatar-preview" src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                                <span class="material-symbols-outlined text-white text-[32px]">edit</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-3">
                                <span class="font-label-caps text-xs uppercase tracking-widest font-bold">Profile Picture</span>
                                @if($user->is_vip)
                                    <span class="bg-primary text-white text-[10px] px-2 py-1 font-bold uppercase tracking-widest leading-none" title="Priority Access Granted">VIP</span>
                                @endif
                            </div>
                            <span class="text-xs text-on-surface-variant font-body-md">Recommended size: 500x500px (Max 2MB)</span>
                            <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="document.getElementById('avatar-preview').src = window.URL.createObjectURL(this.files[0])">
                            <button type="button" onclick="document.getElementById('avatar-input').click()" class="mt-2 w-max px-6 py-2 border border-primary text-xs font-bold uppercase tracking-wider hover:bg-primary hover:text-white transition-colors">Upload New</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-outline-variant pt-12">
                        <div class="flex flex-col gap-2">
                            <label for="name" class="font-label-caps text-xs uppercase tracking-widest font-bold">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full p-4 border border-outline-variant focus:border-primary focus:ring-0 rounded-none bg-transparent font-body-md text-sm" required>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-label-caps text-xs uppercase tracking-widest font-bold">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-4 border border-outline-variant focus:border-primary focus:ring-0 rounded-none bg-transparent font-body-md text-sm" required>
                        </div>
                    </div>

                    <div class="flex flex-col gap-8 border-t border-outline-variant pt-12">
                        <div>
                            <h3 class="font-label-caps text-xs uppercase tracking-widest font-bold">Change Password</h3>
                            <p class="text-xs text-on-surface-variant mt-2 font-body-md">Leave blank if you do not wish to change your password.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-2 relative">
                                <label for="password" class="font-label-caps text-xs uppercase tracking-widest font-bold">New Password</label>
                                <input type="password" id="password" name="password" class="w-full p-4 border border-outline-variant focus:border-primary focus:ring-0 rounded-none bg-transparent font-body-md text-sm">
                            </div>
                            <div class="flex flex-col gap-2 relative">
                                <label for="password_confirmation" class="font-label-caps text-xs uppercase tracking-widest font-bold">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-4 border border-outline-variant focus:border-primary focus:ring-0 rounded-none bg-transparent font-body-md text-sm">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full md:w-auto md:self-end px-12 py-4 bg-primary text-white text-sm font-bold uppercase tracking-widest hover:opacity-80 transition-opacity border border-primary">
                        Save Changes
                    </button>
                </form>

                <!-- Danger Zone -->
                <div class="flex flex-col gap-8 border-t border-red-600/30 pt-12 mt-12" x-data="{ deleteModalOpen: false }">
                    <div>
                        <h3 class="font-label-caps text-xs uppercase tracking-widest font-bold text-red-600">Danger Zone</h3>
                        <p class="text-xs text-on-surface-variant mt-2 font-body-md">Once you delete your account, there is no going back. Please be certain.</p>
                    </div>

                    @error('delete_account')
                        <div class="bg-red-50 text-red-600 p-4 border border-red-600 font-body-md text-xs uppercase tracking-wider">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('password')
                        <div class="bg-red-50 text-red-600 p-4 border border-red-600 font-body-md text-xs uppercase tracking-wider">
                            {{ $message }}
                        </div>
                    @enderror

                    <button type="button" @click="deleteModalOpen = true" class="w-full md:w-auto md:self-start px-12 py-4 bg-transparent border border-red-600 text-red-600 text-sm font-bold uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all duration-300">
                        Delete Account
                    </button>

                    <!-- Delete Account Confirmation Modal -->
                    <div x-cloak x-show="deleteModalOpen" class="fixed inset-0 z-[100] flex justify-center items-center p-4">
                        <div x-show="deleteModalOpen" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="deleteModalOpen = false"></div>
                        <div x-show="deleteModalOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="relative bg-white w-full max-w-[500px] border border-red-600 shadow-2xl p-8 flex flex-col gap-6 text-black z-10">
                            
                            <div class="flex justify-between items-center border-b border-outline-variant pb-4">
                                <h3 class="font-headline-md text-xl uppercase tracking-wider text-red-600">Delete Account</h3>
                                <button type="button" @click="deleteModalOpen = false" class="text-on-surface-variant hover:text-red-600 transition-colors">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>

                            <p class="text-sm font-body-md text-gray-600 leading-relaxed">
                                Are you sure you want to delete your account? This action is permanent and cannot be undone. All of your personal data will be wiped.
                            </p>

                            <form action="{{ route('profile.destroy') }}" method="POST" class="flex flex-col gap-6">
                                @csrf
                                @method('DELETE')

                                @if(auth()->user()->password)
                                <div class="flex flex-col gap-2">
                                    <label for="delete_password" class="font-label-caps text-xs uppercase tracking-widest font-bold">Verify Password</label>
                                    <input type="password" id="delete_password" name="password" class="w-full p-4 border border-outline-variant focus:border-red-600 focus:ring-0 rounded-none bg-transparent font-body-md text-sm" required placeholder="Enter your current password">
                                </div>
                                @endif

                                <div class="flex gap-4 mt-2">
                                    <button type="button" @click="deleteModalOpen = false" class="flex-1 px-6 py-3 border border-outline-variant text-xs font-bold uppercase tracking-wider hover:bg-surface transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white text-xs font-bold uppercase tracking-wider hover:opacity-80 transition-opacity border border-red-600">
                                        Delete Permanently
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Orders Tab -->
            <div x-show="tab === 'orders_active'" x-cloak class="flex flex-col gap-12" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h2 class="font-label-caps text-sm font-bold uppercase tracking-widest text-primary border-b border-primary pb-4">Active Orders</h2>
                
                @if($activeOrders->isEmpty())
                    <div class="border border-outline-variant p-12 flex flex-col items-center justify-center text-center bg-surface-container-lowest">
                        <span class="material-symbols-outlined text-[48px] text-outline mb-4">inventory_2</span>
                        <p class="font-body-md text-sm text-gray-600">You have no active orders.</p>
                        <a href="{{ route('products.index') }}" class="mt-6 border border-primary px-8 py-3 text-xs font-bold uppercase tracking-wider hover:bg-primary hover:text-white transition-colors">Start Shopping</a>
                    </div>
                @else
                    <div class="flex flex-col gap-8">
                        @foreach($activeOrders as $order)
                            @include('profile.partials.order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Past Orders Tab -->
            <div x-show="tab === 'orders_past'" x-cloak class="flex flex-col gap-12" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <h2 class="font-label-caps text-sm font-bold uppercase tracking-widest text-primary border-b border-primary pb-4">Order History</h2>
                
                @if($pastOrders->isEmpty())
                    <div class="border border-outline-variant p-12 flex flex-col items-center justify-center text-center bg-surface-container-lowest">
                        <span class="material-symbols-outlined text-[48px] text-outline mb-4">history</span>
                        <p class="font-body-md text-sm text-gray-600">Your order history is empty.</p>
                    </div>
                @else
                    <div class="flex flex-col gap-8">
                        @foreach($pastOrders as $order)
                            @include('profile.partials.order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>
            
        </div>
    </div>

    <!-- Invoice Modal -->
    <div x-cloak x-show="invoiceModalOpen" class="fixed inset-0 z-[100] flex justify-center items-center p-4 md:p-12">
        <div x-show="invoiceModalOpen" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="invoiceModalOpen = false"></div>
        <div x-show="invoiceModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white w-full max-w-[800px] max-h-[90vh] overflow-y-auto border border-primary shadow-2xl flex flex-col"
             id="invoice-print-area"
             data-lenis-prevent>
            
            <div class="sticky top-0 w-full flex justify-between items-center p-6 bg-white border-b border-outline-variant z-10 print:hidden">
                <h3 class="font-headline-md text-xl uppercase tracking-wider">Invoice Details</h3>
                <div class="flex gap-4">
                    <button onclick="window.print()" class="flex items-center gap-2 border border-primary px-4 py-2 hover:bg-primary hover:text-white transition-colors text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[16px]">print</span> Print
                    </button>
                    <button @click="invoiceModalOpen = false" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>

            <!-- Invoice Content to be populated dynamically -->
            <div class="p-8 md:p-12 flex flex-col gap-12 font-body-md text-sm text-black bg-white" id="invoice-content">
                <!-- Alpine will inject HTML here via x-html -->
                <div x-html="selectedOrder ? generateInvoiceHtml(selectedOrder) : ''"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Injects the invoice HTML into the modal using Javascript data to avoid reloading the page
    // For a real production app, this could be a separate fetch endpoint. 
    // Here we use Alpine to render JSON data passed from Blade.
    const allOrders = @js($orders ?? []);

    function openInvoice(orderId) {
        // Alpine data lives on the profile dashboard container
        const container = document.getElementById('profile-dashboard');
        const component = Alpine.$data(container);
        
        const order = allOrders.find(o => o.id === orderId);
        if(order) {
            component.selectedOrder = order;
            component.invoiceModalOpen = true;
        }
    }

    function generateInvoiceHtml(order) {
        const date = new Date(order.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        
        let itemsHtml = order.items.map(item => `
            <div class="flex justify-between py-4 border-b border-outline-variant">
                <div class="flex flex-col">
                    <span class="font-bold uppercase tracking-wide">${item.product?.name ?? 'Unknown Product'}</span>
                    <span class="text-xs text-gray-500">Qty: ${item.quantity}</span>
                </div>
                <span>$${(parseFloat(item.price_at_purchase) * item.quantity).toFixed(2)}</span>
            </div>
        `).join('');

        return `
            <div class="flex justify-between items-start border-b border-black pb-8">
                <div>
                    <h1 class="font-headline-lg text-4xl uppercase tracking-tighter mb-2 flex items-center gap-3">
                        <x-logo class="w-10 h-10" /> CLEMENTINE
                    </h1>
                    <p class="text-xs uppercase tracking-widest font-bold">INVOICE</p>
                </div>
                <div class="text-right">
                    <p class="font-bold uppercase tracking-wide">ORDER #${order.id.replace(/-/g, '').substring(order.id.replace(/-/g, '').length - 8).toUpperCase()}</p>
                    <p class="text-xs text-gray-500 mt-1">${date}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="font-label-caps text-[10px] uppercase tracking-widest text-gray-500 mb-2">Billed To</p>
                    <p class="font-bold uppercase">${order.shipping_full_name}</p>
                    <p>${order.contact_email}</p>
                </div>
                <div>
                    <p class="font-label-caps text-[10px] uppercase tracking-widest text-gray-500 mb-2">Shipped To</p>
                    <p>${order.shipping_address1}</p>
                    ${order.shipping_address2 ? `<p>${order.shipping_address2}</p>` : ''}
                    <p>${order.shipping_city}, ${order.shipping_postal_code}</p>
                    <p>${order.shipping_country}</p>
                    ${order.tracking_number ? `
                        <p class="font-label-caps text-[10px] uppercase tracking-widest text-gray-500 mt-4 mb-1">Tracking Number</p>
                        <p class="font-bold uppercase tracking-wider">${order.tracking_number}</p>
                    ` : ''}
                </div>
            </div>

            <div class="mt-8">
                <p class="font-label-caps text-[10px] uppercase tracking-widest text-gray-500 border-b border-black pb-2">Order Items</p>
                ${itemsHtml}
            </div>

            <div class="w-full md:w-1/2 ml-auto mt-8 flex flex-col gap-3">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal (Excl. Tax)</span>
                    <span>$${parseFloat(order.subtotal).toFixed(2)}</span>
                </div>
                ${parseFloat(order.discount_amount || 0) > 0 ? `
                <div class="flex justify-between text-blue-600">
                    <div class="flex items-center gap-1">
                        <span>{{ auth()->user()?->is_vip ? 'VIP Discount' : 'Discount' }}</span>
                        <span class="text-[10px]">*${parseFloat(order.subtotal) > 0 ? Math.round((parseFloat(order.discount_amount) / parseFloat(order.subtotal)) * 100) : 0}%</span>
                    </div>
                    <span>-$${parseFloat(order.discount_amount).toFixed(2)}</span>
                </div>` : ''}
                ${parseFloat(order.tax || 0) > 0 ? `
                <div class="flex justify-between">
                    <div class="flex items-center gap-1">
                        <span class="text-gray-500">Product Tax</span>
                        <span class="text-[10px] text-gray-500">*${parseFloat(order.subtotal) > 0 ? Math.round((parseFloat(order.tax) / parseFloat(order.subtotal)) * 100) : 0}%</span>
                    </div>
                    <span>$${parseFloat(order.tax).toFixed(2)}</span>
                </div>` : ''}
                <div class="flex justify-between">
                    <span class="text-gray-500">Shipping Fee</span>
                    <span>$${parseFloat(order.shipping_fee).toFixed(2)}</span>
                </div>
                ${parseFloat(order.shipping_tax || 0) > 0 ? `
                <div class="flex justify-between">
                    <div class="flex items-center gap-1">
                        <span class="text-gray-500">Shipping Tax</span>
                        <span class="text-[10px] text-gray-500">*${parseFloat(order.shipping_fee) > 0 ? Math.round((parseFloat(order.shipping_tax) / parseFloat(order.shipping_fee)) * 100) : 0}%</span>
                    </div>
                    <span>$${parseFloat(order.shipping_tax).toFixed(2)}</span>
                </div>` : ''}
                <div class="flex justify-between border-t border-black pt-4 mt-2">
                    <span class="font-bold uppercase tracking-widest">Total</span>
                    <span class="font-bold text-xl">$${parseFloat(order.total).toFixed(2)}</span>
                </div>
            </div>
            
            <div class="mt-16 pt-8 border-t border-outline-variant text-center text-xs text-gray-500">
                <p>Thank you for shopping with Clementine Horology.</p>
            </div>
        `;
    }
</script>

<style>
    /* Print specific styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-print-area, #invoice-print-area * {
            visibility: visible;
        }
        #invoice-print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
            box-shadow: none;
            border: none;
            overflow: visible;
        }
    }
</style>
@endsection
