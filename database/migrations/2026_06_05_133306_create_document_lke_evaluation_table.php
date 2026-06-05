<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('document_lke_evaluation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('lke_evaluation_id')->constrained()->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('document_lke_evaluation');
    }
};