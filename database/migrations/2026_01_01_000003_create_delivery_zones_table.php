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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('e.g. Inside Dhaka, Dhaka Suburbs, Outside Dhaka');
            $table->string('code', 50)->unique()->comment('DHAKA_INSIDE, DHAKA_SUBURB, OUTSIDE_DHAKA');
            $table->decimal('base_charge', 8, 2)->default(60.00)->comment('Zone shipping cost in BDT');
            $table->boolean('cod_available')->default(true);
            $table->unsignedTinyInteger('estimated_days_min')->default(1);
            $table->unsignedTinyInteger('estimated_days_max')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'base_charge'], 'idx_delivery_zones_active_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
