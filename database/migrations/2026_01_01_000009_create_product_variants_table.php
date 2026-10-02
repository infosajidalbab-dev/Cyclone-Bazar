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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 64)->unique();
            $table->string('title')->comment('e.g. Midnight Black / XL');
            $table->string('attribute_name', 50)->comment('e.g. Size, Color');
            $table->string('attribute_value', 50)->comment('e.g. XL, Black');
            $table->decimal('cost_price', 10, 2)->comment('Variant cost price in BDT');
            $table->decimal('selling_price', 10, 2)->comment('Variant selling price in BDT');
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'is_active'], 'idx_product_variants_active');
            $table->index(['sku', 'is_active'], 'idx_product_variants_sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
