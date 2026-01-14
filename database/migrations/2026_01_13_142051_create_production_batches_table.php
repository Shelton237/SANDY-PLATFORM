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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inventory_batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique();
            $table->string('status')->default('planned')->index();
            $table->decimal('planned_liters', 8, 2)->default(0);
            $table->decimal('actual_liters', 8, 2)->nullable();
            $table->unsignedInteger('yield_percent')->nullable();
            $table->string('team_lead')->nullable();
            $table->string('shift')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('ingredients_used')->nullable();
            $table->json('quality_checks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
