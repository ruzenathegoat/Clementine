<?php
// Test: Send OrderPaid mailable and capture Resend response details
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Production-equivalent OrderPaid Send Test ===" . PHP_EOL;
echo "Mail driver: " . config('mail.default') . PHP_EOL;

// Get latest paid order
$order = \App\Models\Order::with('items.product.collection')
    ->where('payment_status', 'paid')
    ->latest('created_at')
    ->first();

if (!$order) {
    $order = \App\Models\Order::with('items.product.collection')->latest('created_at')->first();
}

if (!$order) {
    echo "ERROR: No orders found." . PHP_EOL;
    exit(1);
}

echo "Order: " . $order->id . PHP_EOL;
echo "To: " . ($order->contact_email ?? 'NULL') . PHP_EOL;
echo "Items: " . $order->items->count() . PHP_EOL;

// Step 1: Try render the mailable to check for errors
echo PHP_EOL . "--- Rendering mailable ---" . PHP_EOL;
try {
    $mailable = new \App\Mail\OrderPaid($order);
    $rendered = $mailable->render();
    echo "Render SUCCESS - HTML length: " . strlen($rendered) . " bytes" . PHP_EOL;
    // Show first 200 chars
    echo "Preview: " . substr(strip_tags($rendered), 0, 200) . "..." . PHP_EOL;
} catch (\Throwable $e) {
    echo "Render FAILED: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Step 2: Send via Mail facade (same as production)
echo PHP_EOL . "--- Sending via Mail facade ---" . PHP_EOL;
$recipient = $order->contact_email ?? 'naufalrahaman013@gmail.com';

try {
    $sentMessage = \Illuminate\Support\Facades\Mail::to($recipient)
        ->send(new \App\Mail\OrderPaid($order));
    
    echo "Send SUCCESS!" . PHP_EOL;
    
    // Try to get message ID from the sent message
    if ($sentMessage) {
        echo "SentMessage class: " . get_class($sentMessage) . PHP_EOL;
        if (method_exists($sentMessage, 'getMessageId')) {
            echo "Message ID: " . $sentMessage->getMessageId() . PHP_EOL;
        }
        if (method_exists($sentMessage, 'getDebug')) {
            echo "Debug: " . $sentMessage->getDebug() . PHP_EOL;
        }
        // Dump all available methods
        echo "Available methods: " . implode(', ', get_class_methods($sentMessage)) . PHP_EOL;
    } else {
        echo "WARNING: Mail::send returned null/void" . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "Send FAILED: " . get_class($e) . PHP_EOL;
    echo "Message: " . $e->getMessage() . PHP_EOL;
}

// Step 3: Also send a simple raw email for comparison
echo PHP_EOL . "--- Sending raw email for comparison ---" . PHP_EOL;
try {
    \Illuminate\Support\Facades\Mail::raw(
        "This is a plain text comparison email sent at " . now() . ". If you receive this but NOT the OrderPaid email, the issue is with the markdown template.",
        function ($msg) use ($recipient) {
            $msg->to($recipient)->subject('Clementine - Plain Text Test ' . now()->format('H:i'));
        }
    );
    echo "Raw email sent to: " . $recipient . PHP_EOL;
} catch (\Throwable $e) {
    echo "Raw email FAILED: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Check your inbox, spam, and all mail for BOTH emails ===" . PHP_EOL;
