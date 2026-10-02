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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 15)->unique()->comment('Primary Bangladesh mobile number e.g. 01712345678');
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'flagged', 'blocked'])->default('active');
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->timestamp('last_ordered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone', 'idx_customers_phone');
            $table->index(['status', 'created_at'], 'idx_customers_status_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
