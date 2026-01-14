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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('size')->nullable();
            $table->string('availability')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('status')->default('draft')->index();
            $table->boolean('is_limited')->default(false);
            $table->boolean('is_new')->default(false);
            $table->unsignedTinyInteger('energy_index')->default(0);
            $table->unsignedInteger('calories')->nullable();
            $table->unsignedInteger('sugars')->nullable();
            $table->decimal('fibers', 5, 2)->nullable();
            $table->json('ingredients')->nullable();
            $table->json('benefits')->nullable();
            $table->json('moments')->nullable();
            $table->json('taste_notes')->nullable();
            $table->string('badge')->nullable();
            $table->string('accent')->nullable();
            $table->string('image_path')->nullable();
            $table->string('batch_note')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
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
