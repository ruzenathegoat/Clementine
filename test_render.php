<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$order = \App\Models\Order::latest()->first();
$mailable = new \App\Mail\OrderPaid($order);
$html = $mailable->render();
echo substr($html, 0, 500);
