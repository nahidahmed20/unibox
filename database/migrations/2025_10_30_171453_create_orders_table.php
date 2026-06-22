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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('order_number')->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('shipping', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->text('order_note')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('courier_id')->nullable()->comment('Linked with couriers table');
            $table->string('tracking_number')->nullable()->comment('Courier Tracking/Consignment ID');
            $table->decimal('return_charge', 10, 2)->default(0)->comment('Penalty amount if returned');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
