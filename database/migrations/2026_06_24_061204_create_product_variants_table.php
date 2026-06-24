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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id')->nullable(); 
            $table->string('size_id')->nullable();                
            $table->string('sku')->unique();                   
            $table->decimal('purchase_price', 15, 2)->default(0)->nullable();
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('stock')->default(0);              
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
