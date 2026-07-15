<?php
// Quick check for users_role_check constraint
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$rows = DB::select("SELECT conname, pg_get_constraintdef(c.oid) as def FROM pg_constraint c WHERE conrelid = 'users'::regclass AND contype = 'c'");
foreach ($rows as $r) {
    echo "$r->conname: $r->def\n";
}
if (empty($rows)) echo "No check constraints found on users table.\n";

// Also try seeding
echo "\nAttempting to seed admin user...\n";
try {
    (new Database\Seeders\AdminUserSeeder())->run();
    echo "SUCCESS!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
