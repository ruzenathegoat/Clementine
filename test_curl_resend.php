<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$apiKey = config('resend.api_key');
$from = config('mail.from.address');
$to = 'naufalrahaman013@gmail.com';

echo "Sending from: $from\n";
echo "Sending to: $to\n";
echo "API Key starts with: " . substr($apiKey, 0, 10) . "...\n";

$ch = curl_init('https://api.resend.com/emails');
$payload = json_encode([
    'from' => 'Clementine <' . $from . '>',
    'to' => [$to],
    'subject' => 'Direct cURL Test from Resend',
    'html' => '<strong>It works!</strong>',
]);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
