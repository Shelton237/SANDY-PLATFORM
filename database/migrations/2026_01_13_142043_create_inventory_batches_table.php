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
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('batch_code')->unique();
            $table->string('ingredient');
            $table->decimal('quantity_available', 10, 2);
            $table->string('unit')->default('kg');
            $table->string('status')->default('available')->index();
            $table->string('location')->nullable();
            $table->string('reserved_for')->nullable();
            $table->date('received_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};
