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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_account_id')->constrained()->cascadeOnDelete();
            $table->string('reference', 40)->unique();
            $table->string('label', 180);
            $table->string('direction', 10); // debit ou credit
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('FCFA');
            $table->date('occurred_on');
            $table->string('stage', 60)->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('payment_method', 60)->nullable();
            $table->string('transactionable_type', 120)->nullable();
            $table->unsignedBigInteger('transactionable_id')->nullable();
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['stage', 'direction']);
            $table->index('occurred_on');
            $table->index(['transactionable_type', 'transactionable_id'], 'finance_transactions_transactionable_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
