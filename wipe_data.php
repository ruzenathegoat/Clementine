<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

try {
    // Truncate transactional tables (using CASCADE for PostgreSQL)
    DB::statement('TRUNCATE TABLE orders, cart_items, login_histories, tickets, ticket_messages, order_items CASCADE;');
    echo "Transactional tables truncated.\n";

    // Delete customer users
    $deletedUsers = User::where('role', 'customer')->orWhereNull('role')->delete();
    echo "Customer users wiped (Count: $deletedUsers).\n";

    echo "\nSelective wiping completed successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
