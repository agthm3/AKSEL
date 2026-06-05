<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lke_components', function (Blueprint $table) {
            $table->id();
            $table->integer('component_number'); // Contoh: 1, 2, 3
            $table->string('name'); // Contoh: PERENCANAAN KINERJA
            $table->decimal('weight', 5, 2); // Bobot maksimal, contoh: 30.00
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lke_components');
    }
};