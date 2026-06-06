<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'lke_deadline'
            $table->string('value')->nullable(); // Tanggal datetime deadline
            $table->timestamps();
        });

        // Masukkan data default awal (Tahun berjalan)
        DB::table('app_settings')->insert([
            'key' => 'lke_deadline',
            'value' => date('Y') . '-12-31 23:59:59',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down() { Schema::dropIfExists('app_settings'); }
};