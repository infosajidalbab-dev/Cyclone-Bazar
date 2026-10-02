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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 64)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            
            // Financials (BDT Currency)
            $table->decimal('cost_price', 10, 2)->comment('Supplier wholesale cost in BDT');
            $table->decimal('selling_price', 10, 2)->comment('Retail listing price in BDT');
            $table->decimal('compare_at_price', 10, 2)->nullable()->comment('Original crossed-out price');
            
            // Inventory
            $table->integer('stock_quantity')->default(0);
            $table->unsignedSmallInteger('low_stock_threshold')->default(5);
            $table->unsignedInteger('weight_grams')->default(250)->comment('For courier weight estimation');
            
            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('has_variants')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for fast product searching, category browsing, and pricing filters
            $table->index(['category_id', 'is_active', 'selling_price'], 'idx_products_cat_active_price');
            $table->index(['is_active', 'is_featured', 'created_at'], 'idx_products_active_featured');
            $table->index(['supplier_id', 'is_active'], 'idx_products_supplier_active');
            $table->index(['sku', 'is_active'], 'idx_products_sku_active');
            $table->fullText(['name', 'sku'], 'idx_products_name_sku_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
