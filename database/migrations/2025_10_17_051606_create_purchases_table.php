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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('invoice_no')->unique();
            $table->date('purchase_date');
            $table->decimal('total_amount', 15, 2);
            $table->tinyInteger('status')->default(1)->comment('1=pending, 2=approved, 3=cancelled');
            $table->tinyInteger('payment_status')->default(1)->comment('1=unpaid, 2=partial, 3=paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
