<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
$order = \App\Models\Order::with('items.product.collection')->latest()->first();

// Verify
$verifyUrl = "http://localhost/verify";
$verifyHtml = view('emails.verify-email', ['url' => $verifyUrl, 'notifiable' => $user])->render();
file_put_contents(public_path('test_verify.html'), $verifyHtml);

// Reset Password
$resetUrl = "http://localhost/reset";
$resetHtml = view('emails.reset-password', ['url' => $resetUrl, 'notifiable' => $user])->render();
file_put_contents(public_path('test_reset.html'), $resetHtml);

// Order Shipped
$shippedHtml = view('emails.order-shipped', ['order' => $order])->render();
file_put_contents(public_path('test_shipped.html'), $shippedHtml);

// Order Paid
$paidHtml = view('emails.orders.paid', ['order' => $order])->render();
file_put_contents(public_path('test_paid.html'), $paidHtml);

echo "Rendered all templates successfully.\n";
