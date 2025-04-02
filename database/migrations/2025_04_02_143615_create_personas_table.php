<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo_persona');
            $table->string('nombre');
            $table->string('sexo')->nullable();
            $table->integer('edad')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->tinyInteger('escolaridad')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->tinyInteger('estado_civil')->nullable();
            $table->string('telefono', 10)->nullable();
            $table->tinyInteger('tipo_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entidad')->nullable();
            $table->string('rfc')->nullable();
            $table->string('instrumento_notarial')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('personas');
    }
};
