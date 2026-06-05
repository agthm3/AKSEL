<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lke_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lke_sub_component_id')->constrained()->onDelete('cascade');
            $table->integer('criteria_number'); // Contoh: 1, 2, 3
            $table->text('description'); // Contoh: Terdapat pedoman teknis perencanaan kinerja.
            $table->text('expected_evidence')->nullable(); // Penjelasan dokumen apa yang diminta (Daftar Evidence)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lke_criteria');
    }
};
