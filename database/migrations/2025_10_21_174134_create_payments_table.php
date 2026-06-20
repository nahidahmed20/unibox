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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('payment_date');
            $table->string('payment_method', 50);
            $table->string('transaction_no', 100)->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->enum('status', ['success', 'pending', 'failed'])->default('success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
