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
        Schema::create('checksheet_heads', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // LH, RH, W13, W46
            $table->string('title'); // Check Sheet QG Kelengkapan Part_LH
            $table->string('subtitle')->nullable(); // Quality Gate - Left Hand Parts
            $table->string('revision', 50); // Rev. 1
            $table->string('document_number', 100); // 001-ME-S-IPP-VII-2025
            $table->string('process_name')->nullable(); // Deletion Clip
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checksheet_heads');
    }
};
