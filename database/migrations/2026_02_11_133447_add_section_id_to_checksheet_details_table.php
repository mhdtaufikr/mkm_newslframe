<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checksheet_details', function (Blueprint $table) {
            // Tambah foreign key ke checksheet_sections
            $table->foreignId('checksheet_section_id')
                  ->after('checksheet_head_id')
                  ->nullable()
                  ->constrained('checksheet_sections')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('checksheet_details', function (Blueprint $table) {
            $table->dropForeign(['checksheet_section_id']);
            $table->dropColumn('checksheet_section_id');
        });
    }
};
