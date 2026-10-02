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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('delivery_zone_id')->constrained('delivery_zones');
            $table->string('recipient_name');
            $table->string('recipient_phone', 15)->comment('Recipient phone e.g. 018XXXXXXXX');
            
            // Bangladesh Administrative Hierarchy
            $table->string('division')->comment('Dhaka, Chittagong, Rajshahi, Khulna, Barishal, Sylhet, Rangpur, Mymensingh');
            $table->string('district')->comment('e.g. Dhaka, Gazipur, Narayanganj, Bogura, Chittagong');
            $table->string('upazila')->comment('e.g. Mirpur, Dhanmondi, Savar, Gazipur Sadar');
            $table->string('area')->nullable()->comment('Specific neighborhood / union / ward');
            
            $table->text('street_address')->comment('House, road, block, floor info');
            $table->string('landmark')->nullable()->comment('Famous nearby point for delivery rider');
            $table->string('postal_code', 10)->nullable();
            
            $table->boolean('is_default_shipping')->default(false);
            $table->boolean('is_default_billing')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for address lookups and courier routing
            $table->index(['customer_id', 'is_default_shipping'], 'idx_addresses_customer_default');
            $table->index(['division', 'district', 'upazila'], 'idx_addresses_bd_hierarchy');
            $table->index('recipient_phone', 'idx_addresses_recipient_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
