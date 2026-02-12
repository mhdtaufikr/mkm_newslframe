<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checksheet_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checksheet_head_id')->constrained('checksheet_heads')->onDelete('cascade');
            $table->string('nama'); // Inspector name
            $table->date('tanggal'); // Inspection date
            $table->string('serial_number')->nullable(); // Serial number produk
            $table->enum('status', ['draft', 'completed', 'approved'])->default('completed');
            $table->integer('total_ok')->default(0);
            $table->integer('total_ng')->default(0);
            $table->integer('total_items')->default(0);
            $table->text('notes')->nullable(); // Catatan umum
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Index untuk search
            $table->index('tanggal');
            $table->index('status');
            $table->index(['checksheet_head_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checksheet_inspections');
    }
};
