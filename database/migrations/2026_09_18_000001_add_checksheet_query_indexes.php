<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checksheet_inspections', function (Blueprint $table) {
            $table->index(['submitted_at', 'id'], 'inspections_submitted_id_idx');
            $table->index(['created_at', 'id'], 'inspections_created_id_idx');
            $table->index(['status', 'created_at', 'id'], 'inspections_status_created_id_idx');
            $table->index(['checksheet_head_id', 'serial_number'], 'inspections_head_serial_idx');
        });

        Schema::table('checksheet_sections', function (Blueprint $table) {
            $table->index(['checksheet_head_id', 'order', 'id'], 'sections_head_order_id_idx');
        });

        Schema::table('checksheet_details', function (Blueprint $table) {
            $table->index(['checksheet_section_id', 'order', 'id'], 'details_section_order_id_idx');
        });

        Schema::table('checksheet_inspection_results', function (Blueprint $table) {
            $table->index(['checksheet_inspection_id', 'result'], 'results_inspection_result_idx');
        });
    }

    public function down(): void
    {
        Schema::table('checksheet_inspection_results', function (Blueprint $table) {
            $table->dropIndex('results_inspection_result_idx');
        });

        Schema::table('checksheet_details', function (Blueprint $table) {
            $table->dropIndex('details_section_order_id_idx');
        });

        Schema::table('checksheet_sections', function (Blueprint $table) {
            $table->dropIndex('sections_head_order_id_idx');
        });

        Schema::table('checksheet_inspections', function (Blueprint $table) {
            $table->dropIndex('inspections_submitted_id_idx');
            $table->dropIndex('inspections_created_id_idx');
            $table->dropIndex('inspections_status_created_id_idx');
            $table->dropIndex('inspections_head_serial_idx');
        });
    }
};
