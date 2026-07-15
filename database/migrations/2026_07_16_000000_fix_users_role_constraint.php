<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure the role column on users is a plain string with no enum check constraint.
     * This is needed because the original enum migration created a CHECK constraint
     * that only allows ('admin', 'customer'), but the app now uses additional roles
     * like super_admin, inventory_manager, ops_staff, etc.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        }

        // Double-check: ensure column type is varchar, not enum
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->change();
        });
    }

    public function down(): void
    {
        // No-op
    }
};
