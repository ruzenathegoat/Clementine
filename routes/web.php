<?php

use App\Http\Controllers\HomeController;
// use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

// TEMPORARY DEBUG
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
            'url' => \Illuminate\Support\Facades\Storage::disk('s3')->url('debug.txt')
        ];
    } catch (\Throwable $e) {
        return [
            'success' => false,
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
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

// Realtime Stock Polling
Route::get('/api/products/stock', function (\Illuminate\Http\Request $request) {
    $ids = $request->input('ids');
    if (!$ids || !is_array($ids)) return response()->json([]);
    return response()->json(\App\Models\Product::whereIn('id', $ids)->get(['id', 'stock', 'status']));
});

// Cart & Checkout (Protected by Auth)
Route::middleware('auth')->group(function () {
    Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Orders
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/simulate-payment', [App\Http\Controllers\OrderController::class, 'simulatePayment'])->name('orders.simulate_payment');

    // Auth Success Views
    Route::view('/register/success', 'auth.register-success')->name('register.success');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('honeypot');
    
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
    });

    // Users & VIP Epic
    Route::middleware('role:super_admin,customer_success')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'show', 'update']);
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