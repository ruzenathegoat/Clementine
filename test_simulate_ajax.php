<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$req = Illuminate\Http\Request::create('/docs/verify?certificate_sn=CLM-268FBPYHC5', 'GET');
$req->headers->set('X-Requested-With', 'XMLHttpRequest');
$req->headers->set('Accept', 'application/json');

$res = $kernel->handle($req);

echo "STATUS: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() >= 500) {
    echo $res->getContent();
} else {
    echo $res->getContent();
}
