<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checksheet_details', function (Blueprint $table) {
            // Hapus kolom section_name dan section_number
            $table->dropColumn(['section_name', 'section_number']);
        });
    }

    public function down(): void
    {
        Schema::table('checksheet_details', function (Blueprint $table) {
            // Rollback: tambah kembali kolom
            $table->string('section_name')->after('checksheet_section_id')->nullable();
            $table->integer('section_number')->after('section_name')->nullable();
        });
    }
};
