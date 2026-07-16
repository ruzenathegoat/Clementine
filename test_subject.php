<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$html = view('emails.orders.paid', ['order' => \App\Models\Order::latest()->first()])->render();
$subject = 'Acquisition Confirmed - #TEST1234';

try {
    $resend = Resend::client(config('resend.api_key'));
    $result = $resend->emails->send([
        'from' => 'Clementine <' . config('mail.from.address') . '>',
        'to' => ['naufalrahaman013@gmail.com'],
        'subject' => $subject,
        'html' => $html,
    ]);
    echo "Success: " . $result['id'] . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
