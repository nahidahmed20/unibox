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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->integer('quantity')->nullable()->default(1);
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('discount', 15, 2)->nullable()->default(0);
            $table->decimal('total_price', 15, 2)->nullable();
            $table->decimal('profit', 15, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
