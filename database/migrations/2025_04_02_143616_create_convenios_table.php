<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_consecutivo');
            $table->string('lugar_celebracion');
            $table->date('fecha_celebracion');
            $table->string('numero_registro');
            $table->tinyInteger('modalidad');
            $table->tinyInteger('tipo_mecanismo');
            $table->tinyInteger('materia');
            $table->text('conflicto');
            $table->tinyInteger('tipo_cumplimiento');
            $table->tinyInteger('tipo_solucion');
            $table->tinyInteger('rango_monetario');
            $table->text('otro_tipo_acuerdo')->nullable();
            $table->text('hipervinculo_convenio')->nullable();
            $table->date('fecha_registro');
            $table->foreignId('facilitador_id')->constrained('facilitadores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('convenios');
    }
};
