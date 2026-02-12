<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheet_inspection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checksheet_inspection_id')->constrained('checksheet_inspections')->onDelete('cascade');
            $table->foreignId('checksheet_section_id')->constrained('checksheet_sections')->onDelete('cascade');
            $table->foreignId('checksheet_detail_id')->constrained('checksheet_details')->onDelete('cascade');
            $table->enum('result', ['ok', 'ng'])->nullable(); // OK or NG
            $table->text('status')->nullable(); // Status / Remarks
            $table->timestamps();

            // Index dengan nama custom yang lebih pendek
            $table->index('checksheet_inspection_id', 'insp_result_insp_id_idx');
            $table->index('result', 'insp_result_result_idx');
            $table->index(['checksheet_inspection_id', 'checksheet_section_id'], 'insp_result_insp_section_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheet_inspection_results');
    }
};
