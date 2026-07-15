<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('collection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('gender')->nullable();
            $table->string('material')->nullable();
            $table->string('movement')->nullable();
            $table->integer('diameter_mm')->nullable();
            $table->string('water_resistance')->nullable();
            $table->string('crystal')->nullable();
            $table->string('case_material')->nullable();
            $table->integer('warranty_years')->nullable();
            $table->integer('stock')->default(0);
            $table->string('status')->default('draft');
            $table->timestamp('release_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('product_media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('type')->default('image');
            $table->integer('sort_order')->default(0);
        });

        Schema::create('product_strap_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->string('strap_name');
            $table->decimal('price_delta', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending');
            $table->string('contact_email');
            $table->string('shipping_full_name');
            $table->string('shipping_address1');
            $table->string('shipping_address2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_postal_code');
            $table->string('shipping_country');
            $table->boolean('billing_same_as_shipping')->default(true);
            $table->string('payment_method');
            $table->string('payment_status')->default('unpaid');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('strap_option_id')->nullable()->constrained('product_strap_options')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_purchase', 10, 2);
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('strap_option_id')->nullable()->constrained('product_strap_options')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_strap_options');
        Schema::dropIfExists('product_media');
        Schema::dropIfExists('products');
        Schema::dropIfExists('collections');
    }
};
