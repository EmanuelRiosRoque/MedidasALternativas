<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas_morales', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social');
            $table->string('rfc')->nullable();
            $table->string('instrumento')->nullable();
            $table->date('fecha_instrumento')->nullable();
            $table->string('tipo_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entidad_federativa')->nullable();
            $table->string('cp')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_morales');
    }
};
