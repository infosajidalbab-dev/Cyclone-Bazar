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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            
            // Item snapshot to preserve order integrity despite catalog updates
            $table->string('product_name');
            $table->string('variant_title')->nullable();
            $table->string('sku', 64);
            $table->decimal('unit_cost', 10, 2)->comment('Wholesale dropship cost in BDT');
            $table->decimal('unit_price', 10, 2)->comment('Retail selling price in BDT');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 12, 2)->comment('unit_price * quantity');
            
            // Dropshipping fulfillment stage per item
            $table->enum('dropship_status', [
                'pending_supplier_dispatch',
                'supplier_dispatched',
                'received_at_hub',
                'fulfilled',
                'cancelled'
            ])->default('pending_supplier_dispatch');
            
            $table->timestamps();

            $table->index(['order_id', 'supplier_id'], 'idx_order_items_supplier_routing');
            $table->index('sku', 'idx_order_items_sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
