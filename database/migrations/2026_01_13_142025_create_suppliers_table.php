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
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('France');
            $table->unsignedInteger('lead_time_days')->default(2);
            $table->unsignedTinyInteger('reliability_score')->default(80);
            $table->string('expertise')->nullable();
            $table->string('status')->default('active');
            $table->text('certifications')->nullable();
            $table->text('notes')->nullable();
            $table->json('catalog')->nullable();
            $table->timestamps();
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
