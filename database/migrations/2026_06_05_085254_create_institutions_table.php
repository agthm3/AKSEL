<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama lengkap instansi
            $table->string('alias')->nullable(); // Singkatan instansi (Contoh: BRIDA, DISKOMINFO)
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // Status instansi
            $table->text('notes')->nullable(); // Catatan opsional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
    }
};