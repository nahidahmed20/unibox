<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->unique()->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('variation_id')->nullable();
            $table->decimal('purchase_price', 15, 2)->default(0)->nullable();
            $table->decimal('selling_price', 15, 2)->default(0)->nullable();
            $table->enum('discount_type', ['fixed', 'percent'])->nullable();
            $table->decimal('discount_value', 15, 2)->default(0)->nullable();
            $table->timestamp('discount_starts_at')->nullable();
            $table->timestamp('discount_ends_at')->nullable();
            $table->decimal('main_price', 15, 2)->default(0)->nullable();
            $table->decimal('max_price', 15, 2)->default(0)->nullable();
            $table->decimal('alert_quantity', 15, 2)->default(0)->nullable();
            $table->integer('stock')->default(0)->nullable();
            $table->decimal('tax_rate', 15, 2)->default(0)->nullable();
            $table->decimal('weight', 15, 2)->nullable()->nullable();
            $table->string('dimensions', 100)->nullable();
            $table->string('image')->nullable();
            $table->string('size_guide')->nullable();
            $table->text('short_description')->nullable();
            $table->text('extra_note')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('is_featured')->default(1);
            $table->tinyInteger('is_new')->default(1);
            $table->tinyInteger('is_purchased')->default(0);
            $table->tinyInteger('is_bestseller')->default(0);
            $table->tinyInteger('is_trending')->default(0);
            $table->string('product_type',100)->nullable();
            $table->tinyInteger('is_calculator')->default(0);
            $table->decimal('price_per_sqft', 15, 2)->default(0);
            $table->json('specifications')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
