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
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignUuid('tryout_id')->constrained('tryouts')->onDelete('cascade');
            $table->string('payment_gateway'); // ex: midtrans, tripay
            $table->string('payment_method'); // ex: gopay, bca_va, qris
            $table->string('order_number')->unique();
            $table->decimal('amount', 12, 2); // Total yang harus dibayar
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');
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
