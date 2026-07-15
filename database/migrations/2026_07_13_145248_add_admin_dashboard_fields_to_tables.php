<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_vip')->default(false)->after('role');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cogs', 10, 2)->default(0)->after('price');
            $table->timestamp('scheduled_publish_at')->nullable()->after('release_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('status');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('cogs_at_purchase', 10, 2)->default(0)->after('price_at_purchase');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_vip');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cogs', 'scheduled_publish_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'shipped_at']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('cogs_at_purchase');
        });
    }
};
