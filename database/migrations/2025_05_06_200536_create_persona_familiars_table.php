<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona_familiars', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('sexo')->nullable();
            $table->string('edad')->nullable();
            $table->string('escolaridad')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('tipo_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entidad_federativa')->nullable();
            $table->string('cp')->nullable();
            $table->string('estado_civil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona_familiars');
    }
};
