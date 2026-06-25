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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('invoice_no')->unique()->nullable();
            $table->date('sale_date')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable()->default(0);
            $table->decimal('discount', 15, 2)->nullable()->default(0);
            $table->decimal('tax_amount', 15, 2)->nullable()->default(0);
            $table->decimal('shipping_cost', 15, 2)->nullable()->default(0);
            $table->decimal('grand_total', 15, 2)->nullable()->default(0);
            $table->decimal('paid_amount', 15, 2)->nullable()->default(0);
            $table->decimal('due_amount', 15, 2)->nullable()->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->integer('delivery_charge')->nullable();

            $table->enum('payment_status', ['paid', 'partial', 'due'])
                ->nullable()
                ->default('due');
            $table->boolean('status')->nullable()->default(1);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
