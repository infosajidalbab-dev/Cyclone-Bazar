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
            $table->string('order_number', 32)->unique()->comment('Human-readable order tracking ID e.g. CM-20261002-8821');
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->string('customer_phone', 15)->comment('Snapshot of customer phone for instant tracking lookup');
            $table->foreignId('shipping_address_id')->constrained('addresses')->restrictOnDelete();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones')->restrictOnDelete();
            
            // Financials (BDT Currency)
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('delivery_charge', 8, 2)->default(0.00);
            $table->decimal('discount_amount', 8, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            
            // Payment
            $table->enum('payment_method', ['cod', 'bkash', 'nagad', 'sslcommerz'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'failed', 'refunded'])->default('pending');
            
            // Fulfillment Lifecycle
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'handed_over_to_courier',
                'in_transit',
                'delivered',
                'cancelled',
                'returned'
            ])->default('pending');
            
            // Courier details (e.g. Steadfast Courier, Pathao)
            $table->string('courier_name', 50)->nullable();
            $table->string('courier_tracking_code', 100)->nullable();
            $table->string('courier_consignment_id', 100)->nullable();
            
            // Communication & Notes
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Audit Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // High-performance composite indexes
            // 1. Order tracking by Order Number + Phone (Strict Requirement)
            $table->index(['order_number', 'customer_phone'], 'idx_orders_tracking_number_phone');
            
            // 2. Customer order history chronological sorting
            $table->index(['customer_id', 'created_at'], 'idx_orders_customer_history');
            
            // 3. Operational order status queries for admin dashboard
            $table->index(['order_status', 'created_at'], 'idx_orders_status_created');
            
            // 4. Courier tracking lookups
            $table->index('courier_tracking_code', 'idx_orders_courier_tracking');
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
