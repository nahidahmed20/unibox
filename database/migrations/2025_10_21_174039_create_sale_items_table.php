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
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable()->default(0);
            $table->decimal('total_price', 10, 2);
            $table->decimal('profit', 10, 2)->nullable()->default(0);
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
