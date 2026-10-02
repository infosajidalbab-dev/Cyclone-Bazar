<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image_path');
            $table->string('target_url')->nullable();
            $table->enum('position', ['hero', 'middle', 'footer'])->default('hero');
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['position', 'is_active', 'display_order'], 'idx_banners_pos_active_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
