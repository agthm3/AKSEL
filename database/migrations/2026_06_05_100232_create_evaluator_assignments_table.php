<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluator_assignments', function (Blueprint $table) {
            $table->id();
            // ID akun operator inspektorat
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // ID dinas yang menjadi binaannya
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluator_assignments');
    }
};