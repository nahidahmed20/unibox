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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); 
            $table->string('customer_code')->unique()->nullable(); 
            $table->string('name')->nullable(); 
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('total_due', 10, 2)->default(0);
            $table->enum('type', ['regular', 'wholesale', 'walk-in'])->default('regular');
            $table->boolean('status')->default(1); 
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
