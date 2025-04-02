<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('convenio_participante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained('convenios')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->tinyInteger('tipo_rol'); // 1: Solicitante, 2: Invitado
            $table->boolean('es_representante')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('convenio_participante');
    }
};
