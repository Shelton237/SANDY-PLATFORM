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
        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->default('expense'); // expense, revenue, asset
            $table->string('stage')->nullable(); // supply, inventory, production, sales, delivery, admin
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('allocated_budget', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_accounts');
    }
};
