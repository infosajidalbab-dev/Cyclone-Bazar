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
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('payment_gateway', 50)->default('cod')->comment('cod, bkash, nagad, sslcommerz');
            $table->string('transaction_id', 120)->nullable()->unique()->comment('Gateway transaction or invoice reference');
            $table->decimal('amount', 12, 2)->comment('Payment amount in BDT');
            $table->char('currency', 3)->default('BDT');
            $table->enum('status', ['initiated', 'pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status'], 'idx_payments_order_status');
            $table->index('transaction_id', 'idx_payments_tx_id');
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
