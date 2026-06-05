<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lke_sub_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lke_component_id')->constrained()->onDelete('cascade');
            $table->string('code'); // Contoh: 1.a, 1.b
            $table->string('name'); // Contoh: Dokumen Perencanaan kinerja telah tersedia
            $table->decimal('weight', 5, 2)->nullable(); // Jika sub-komponen punya bobot spesifik
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lke_sub_components');
    }
};