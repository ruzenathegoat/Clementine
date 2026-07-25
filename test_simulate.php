<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$req = Illuminate\Http\Request::create('/docs/verify?certificate_sn=CLM-268FBPYHC5', 'GET');
$res = $kernel->handle($req);

echo "STATUS: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() >= 500) {
    echo $res->getContent();
}
