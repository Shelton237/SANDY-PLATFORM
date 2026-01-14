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
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('ingredient');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg');
            $table->string('status')->default('ordered')->index();
            $table->string('transport_mode')->nullable();
            $table->string('storage_location')->nullable();
            $table->string('quality_status')->default('pending');
            $table->date('ordered_at')->nullable();
            $table->date('expected_at')->nullable();
            $table->date('received_at')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_orders');
    }
};
