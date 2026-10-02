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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('phone', 15)->unique();
            $table->string('email')->nullable();
            $table->text('warehouse_address')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0.00)->comment('Percentage share or margin');
            $table->string('payment_terms')->default('weekly_settlement');
            $table->unsignedTinyInteger('lead_time_days')->default(1)->comment('Lead time to dispatch');
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'company_name'], 'idx_suppliers_status_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
