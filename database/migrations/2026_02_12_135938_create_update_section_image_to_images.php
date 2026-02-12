<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('checksheet_sections', function (Blueprint $table) {
            // Ubah tipe jadi TEXT untuk JSON array
            $table->text('section_image')->nullable()->change();
        });

        // Rename column
        Schema::table('checksheet_sections', function (Blueprint $table) {
            $table->renameColumn('section_image', 'section_images');
        });
    }

    public function down(): void
    {
        Schema::table('checksheet_sections', function (Blueprint $table) {
            $table->renameColumn('section_images', 'section_image');
        });

        Schema::table('checksheet_sections', function (Blueprint $table) {
            $table->string('section_image', 255)->nullable()->change();
        });
    }
};
