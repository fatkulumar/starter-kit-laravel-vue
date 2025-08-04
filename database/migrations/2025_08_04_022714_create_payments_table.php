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
            $table->uuid('id')->primary();
            $table->uuid('order_id')->index();
            $table->string('payment_gateway'); // ex: midtrans, tripay
            $table->string('payment_method'); // ex: gopay, bca_va, qris
            $table->string('reference')->nullable(); // ID unik dari gateway
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->enum('status', [
                'authorize',
                'capture',
                'settlement',
                'pending',
                'deny',
                'cancel',
                'expire',
                'refund',
                'partial_refund',
                'chargeback',
                'partial_chargeback',
                'failure'
            ])->default('pending');
            $table->json('raw_response')->nullable(); // Simpan JSON response dari gateway
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
