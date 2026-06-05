<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lke_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('lke_criteria_id')->constrained('lke_criteria')->onDelete('cascade');
            
            // Kolom Penilaian
            $table->string('predicate', 2)->nullable(); // Pilihan: AA, A, BB, B, CC, C, D, E
            $table->decimal('final_score', 5, 2)->default(0); // Hasil perhitungan rumus paten excel
            
            // Kolom Pemeriksaan Inspektorat
            $table->enum('status', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->text('inspector_notes')->nullable(); // Catatan perbaikan dari inspektorat
            
            $table->year('evaluation_year'); // Tahun evaluasi LKE (misal: 2026)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lke_evaluations');
    }
};