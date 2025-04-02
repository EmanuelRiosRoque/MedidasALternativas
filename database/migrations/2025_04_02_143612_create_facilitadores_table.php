<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facilitadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('numero_certificacion');
            $table->tinyInteger('tipo'); // 1: Público, 2: Privado
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('facilitadores');
    }
};
