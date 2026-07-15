<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$res = DB::select("SELECT pg_get_constraintdef(oid) FROM pg_constraint WHERE conname = 'orders_status_check'");
print_r($res);
$res2 = DB::select("SELECT pg_get_constraintdef(oid) FROM pg_constraint WHERE conname = 'orders_payment_status_check'");
print_r($res2);
