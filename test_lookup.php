<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $req = new \Illuminate\Http\Request(['certificate_sn' => 'CLM-268FBPYHC5']);
    $req->headers->set('X-Requested-With', 'XMLHttpRequest');
    $controller = app(\App\Http\Controllers\DocsController::class);
    $response = $controller->verify($req);
    echo $response->getContent();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
