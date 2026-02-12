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
        Schema::create('checksheet_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checksheet_head_id')->constrained('checksheet_heads')->onDelete('cascade');
            $table->string('section_name'); // Sketch Section-1, Sketch Section-2, etc
            $table->integer('section_number'); // 1, 2, 3
            $table->string('item_code', 50)->nullable(); // Kode item check
            $table->string('item_name'); // Nama item check
            $table->text('item_description')->nullable(); // Deskripsi item
            $table->string('qpoint_code', 50)->nullable(); // Q1, Q2, Q3, etc
            $table->string('qpoint_name')->nullable(); // Nama Q-Point
            $table->text('inspection_criteria')->nullable(); // Kriteria pemeriksaan
            $table->enum('check_type', ['visual', 'measurement', 'functional', 'other'])->default('visual');
            $table->string('standard', 100)->nullable(); // Standard/Spec
            $table->decimal('min_value', 10, 2)->nullable(); // Nilai minimum
            $table->decimal('max_value', 10, 2)->nullable(); // Nilai maximum
            $table->string('unit', 20)->nullable(); // Satuan (mm, cm, kg, dll)
            $table->text('ok_criteria')->nullable(); // Kriteria OK
            $table->text('ng_criteria')->nullable(); // Kriteria NG
            $table->boolean('is_critical')->default(false); // Critical point atau tidak
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['checksheet_head_id', 'section_number', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checksheet_details');
    }
};
