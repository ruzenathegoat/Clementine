<?php

use App\Http\Controllers\HomeController;
// use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ClementpayController;
use App\Http\Controllers\DummyPaymentGatewayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// TEMPORARY DEBUG
Route::get('/_debug/concierge', function () {
    $pendingTickets = \App\Models\Ticket::where('status', 'pending')->with('user')->orderBy('created_at', 'asc')->get();
    $activeTickets = \App\Models\Ticket::where('status', 'active')->with('user')->orderBy('updated_at', 'desc')->get();
    
    // mock auth user
    auth()->loginUsingId(1);

    return view('admin.concierge.index', compact('pendingTickets', 'activeTickets'));
});

Route::get('/_debug/reset-password', function (\Illuminate\Http\Request $request) {
    try {
        $user = \App\Models\User::first();
        if (!$user) return response()->json(['error' => 'No user found']);
        
        $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);
        
        return response()->json([
            'status' => 'success',
            'result' => $status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT ? 'Sent' : 'Failed',
            'user' => $user->email
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'error_class' => get_class($e),
            'message' => $e->getMessage(),
            'trace_first_line' => explode("\n", $e->getTraceAsString())[0] ?? null,
        ], 500);
    }
});

Route::get('/_debug/mail-test', function () {
    $start = microtime(true);
    try {
        \Illuminate\Support\Facades\Mail::raw('Diagnostic Test from Clementine', function ($msg) {
            $msg->to('naufalrahaman013@gmail.com')->subject('Diagnostic Test');
        });
        return response()->json([
            'status' => 'success',
            'time_taken' => (microtime(true) - $start) . ' seconds',
            'config' => [
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'resend_api_key_set' => !empty(config('resend.api_key')),
                'resend_api_key_length' => strlen(config('resend.api_key')),
            ]
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'error_class' => get_class($e),
            'message' => $e->getMessage(),
            'trace_first_line' => explode("\n", $e->getTraceAsString())[0] ?? null,
            'config' => [
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'resend_api_key_set' => !empty(config('resend.api_key')),
                'resend_api_key_length' => strlen(config('resend.api_key')),
            ]
        ], 500);
    }
});

Route::get('/_debug/queue-status', function () {
    return response()->json([
        'jobs_count' => \Illuminate\Support\Facades\DB::table('jobs')->count(),
        'failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')->orderBy('id', 'desc')->take(5)->get(),
        'queue_connection' => config('queue.default'),
    ]);
});
Route::get('/_debug/db', function () {
    $users = \App\Models\User::where('role', '!=', 'customer')->get(['email', 'password']);
    $host = config('database.connections.pgsql.host');
    $connection = config('database.default');
    return [
        'connection' => $connection,
        'host' => $host,
        'admins' => $users,
    ];
});

Route::get('/_debug/s3', function () {
    try {
        $path = \Illuminate\Support\Facades\Storage::disk('s3')->put('debug.txt', 'test content');
        return [
            'success' => true,
            'path' => $path,
            'url' => \Illuminate\Support\Facades\Storage::disk('s3')->url('debug.txt'),
            'config' => config('filesystems.disks.s3')
        ];
    } catch (\Throwable $e) {
        return [
            'success' => false,
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'config' => config('filesystems.disks.s3')
        ];
    }
});

// --- ACTIVE ---
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Detail page view is still a placeholder (real Blade view comes in the next Phase 4 step),
// but the route already pulls the real product from the DB via ProductController@show — 404s on bad slugs.
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Collections
Route::get('/collections', [\App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{slug}', [\App\Http\Controllers\CollectionController::class, 'show'])->name('collections.show');

// Smart Watch Advisor
Route::get('/advisor', [\App\Http\Controllers\AdvisorController::class, 'index'])->name('advisor.index');
Route::get('/advisor/results', [\App\Http\Controllers\AdvisorController::class, 'process'])->name('advisor.process');

// Realtime Stock Polling
Route::get('/api/products/stock', function (\Illuminate\Http\Request $request) {
    $ids = $request->input('ids');
    if (!$ids || !is_array($ids)) return response()->json([]);
    return response()->json(\App\Models\Product::whereIn('id', $ids)->get(['id', 'stock', 'status']));
});

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    if (! $request->hasValidSignature()) {
        return redirect()->route('login')->with('error', 'INVALID VERIFICATION LINK.');
    }
    
    $user = \App\Models\User::findOrFail($id);
    
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->with('error', 'INVALID VERIFICATION LINK.');
    }
    
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }
    
    // Auto-trust this device since they clicked the secure email link
    $agent = new \Jenssegers\Agent\Agent();
    $ip = $request->ip();
    if ($ip === '127.0.0.1' || $ip === '::1') $ip = '8.8.8.8'; 
    $location = \Stevebauman\Location\Facades\Location::get($ip);
    
    \App\Models\LoginHistory::create([
        'user_id' => $user->id,
        'ip_address' => $ip,
        'country' => $location ? $location->countryName : null,
        'city' => $location ? $location->cityName : null,
        'region' => $location ? $location->regionName : null,
        'latitude' => $location ? $location->latitude : null,
        'longitude' => $location ? $location->longitude : null,
        'user_agent' => $request->userAgent(),
        'browser' => $agent->browser(),
        'platform' => $agent->platform(),
        'device' => $agent->device(),
        'is_verified' => true,
    ]);
    
    \Illuminate\Support\Facades\Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('register.success');
})->name('verification.verify');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function (\Illuminate\Http\Request $request) {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('register.success')->with('success', 'YOUR EMAIL HAS BEEN VERIFIED.')
            : view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/check-verification', function (\Illuminate\Http\Request $request) {
        return response()->json([
            'verified' => $request->user()?->hasVerifiedEmail() ?? false
        ]);
    })->name('verification.check');

    // Original verification.verify route removed from auth group

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'VERIFICATION LINK SENT TO YOUR EMAIL.');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Cart & Checkout (Protected by Auth & Verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Concierge (Live Chat)
    Route::get('/concierge', [App\Http\Controllers\ConciergeController::class, 'index'])->name('concierge.index');
    Route::post('/concierge', [App\Http\Controllers\ConciergeController::class, 'store'])->middleware('throttle:5,60')->name('concierge.store');
    Route::post('/concierge/{ticket}/messages', [App\Http\Controllers\ConciergeController::class, 'sendMessage'])->name('concierge.messages.store');

    // Orders
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/simulate-payment', [App\Http\Controllers\OrderController::class, 'simulatePayment'])->name('orders.simulate_payment');
    Route::get('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancelForm'])->name('orders.cancel_form');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');

    // Clementpay
    Route::get('/clementpay', [ClementpayController::class, 'index'])->name('clementpay.index');
    Route::post('/clementpay/topup', [ClementpayController::class, 'topup'])->name('clementpay.topup');

    // Dummy Gateway
    Route::get('/payment/qris/{type}/{reference_id}/{amount}', [DummyPaymentGatewayController::class, 'show'])->name('dummy.qris');
    Route::post('/payment/qris/{type}/{reference_id}/simulate-success', [DummyPaymentGatewayController::class, 'simulateSuccess'])->name('dummy.qris.success');

    // Auth Success Views
    Route::view('/register/success', 'auth.register-success')->name('register.success');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('honeypot');
    Route::get('/login/verify-notice', [AuthController::class, 'verifyNotice'])->name('login.verify.notice');
    Route::get('/login/check-status', [AuthController::class, 'checkLoginStatus'])->name('login.check_status');
    Route::get('/login/verify/{history}', [AuthController::class, 'verifySuspiciousLogin'])->name('login.verify');
    
    // Google OAuth Routes
    Route::get('/auth/google', [App\Http\Controllers\SocialiteController::class, 'redirect'])->name('google.login');
    Route::get('/auth/google/callback', [App\Http\Controllers\SocialiteController::class, 'callback'])->name('google.callback');

    // Twitter OAuth Routes
    Route::get('/auth/twitter', [\App\Http\Controllers\Auth\TwitterAuthController::class, 'redirect'])->name('twitter.login');
    Route::get('/auth/twitter/callback', [\App\Http\Controllers\Auth\TwitterAuthController::class, 'callback'])->name('twitter.callback');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('honeypot');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- ADMIN ROUTES ---
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard (Bisa diakses oleh semua jenis admin)
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Inventory Epic
    Route::middleware('role:super_admin,inventory_manager')->group(function () {
        Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class);
        Route::resource('collections', \App\Http\Controllers\Admin\CollectionController::class);
    });

    // Orders Epic
    Route::middleware('role:super_admin,ops_staff,finance_manager')->group(function () {
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'destroy']);
        Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'refundToClementpay'])->name('orders.refund');
    });

    // Users & VIP Epic
    Route::middleware('role:super_admin,customer_success')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show', 'update']);
        
        // Admin Concierge
        Route::get('concierge', [\App\Http\Controllers\Admin\ConciergeController::class, 'index'])->name('concierge.index');
        Route::post('concierge/{ticket}/accept', [\App\Http\Controllers\Admin\ConciergeController::class, 'accept'])->name('concierge.accept');
        Route::get('concierge/{ticket}', [\App\Http\Controllers\Admin\ConciergeController::class, 'show'])->name('concierge.show');
        Route::post('concierge/{ticket}/messages', [\App\Http\Controllers\Admin\ConciergeController::class, 'sendMessage'])->name('concierge.messages.store');
        Route::post('concierge/{ticket}/resolve', [\App\Http\Controllers\Admin\ConciergeController::class, 'resolve'])->name('concierge.resolve');
    });

    // Financial Analytics Epic
    Route::middleware('role:super_admin,finance_manager')->group(function () {
        Route::get('financials', [\App\Http\Controllers\Admin\FinancialController::class, 'index'])->name('financials.index');
        Route::get('financials/export', [\App\Http\Controllers\Admin\FinancialController::class, 'export'])->name('financials.export');
    });

    // Settings Module
    Route::middleware('role:super_admin')->group(function () {
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings/currency', [\App\Http\Controllers\Admin\SettingController::class, 'updateCurrency'])->name('settings.currency.update');
    });
});

Route::get('/_debug/test-order-paid', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        $order = \App\Models\Order::with('items.product.collection')->where('payment_status', 'paid')->latest('created_at')->first();
        if (!$order) {
            $order = \App\Models\Order::with('items.product.collection')->latest('created_at')->first();
        }
        if (!$order) {
            return "No order found";
        }
        
        $recipient = request('to', 'naufalrahaman013@gmail.com');
        \Illuminate\Support\Facades\Log::info('Debug Route: Sending OrderPaid', ['order' => $order->id, 'to' => $recipient]);
        
        \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\OrderPaid($order));
        
        return response()->json([
            'status' => 'success',
            'message' => 'OrderPaid email sent via production!',
            'order_id' => $order->id,
            'recipient' => $recipient,
            'mailer' => config('mail.default'),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/_debug/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) {
        return "Log file not found.";
    }
    
    // Get last 500 lines using tail-like approach
    $file = file($logFile);
    $lines = array_slice($file, -500);
    
    return response('<pre>' . htmlspecialchars(implode("", $lines)) . '</pre>', 200)
        ->header('Content-Type', 'text/html');
});

Route::get('/_debug/env', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'mail_from' => config('mail.from.address'),
    ]);
});