<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheet_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checksheet_head_id')->constrained('checksheet_heads')->onDelete('cascade');
            $table->string('section_name'); // Sketch Section-1
            $table->integer('section_number'); // 1, 2, 3
            $table->string('section_image')->nullable(); // path gambar section
            $table->text('section_description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheet_sections');
    }
};
