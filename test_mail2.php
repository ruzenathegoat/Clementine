<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$start = microtime(true);
try {
    $user = \App\Models\User::first();
    echo "User: " . $user->email . "\n";
    $user->sendPasswordResetNotification('test-token');
    $elapsed = round(microtime(true) - $start, 2);
    echo "SUCCESS: Notification sent in {$elapsed}s\n";
} catch (\Exception $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED after {$elapsed}s: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
