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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code', 24)->unique();
            $table->foreignId('product_item_id')->constrained()->restrictOnDelete();
            $table->string('target_id', 120);
            $table->string('server_id', 80)->nullable();
            $table->string('payment_method', 40);
            $table->unsignedInteger('total_price');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
