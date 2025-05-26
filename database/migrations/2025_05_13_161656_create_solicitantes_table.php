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
        Schema::create('solicitantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');

            // Tipo de persona
            $table->enum('persona', ['fisica', 'moral', 'familiar'])->nullable();
            $table->boolean('representante')->default(0);
            $table->string('tipo_solicitante')->nullable();

            // Datos generales
            $table->string('nombre')->nullable();
            $table->string('apellido_p')->nullable();
            $table->string('apellido_m')->nullable();
            $table->string('sexo')->nullable();
            $table->string('edad')->nullable(); // O puedes usar integer
            $table->date('fecha_nacimiento')->nullable();
            $table->string('escolaridad')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('nacionalidad')->nullable();

            // Domicilio
            $table->string('tipo_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entidad_federativa')->nullable();
            $table->string('cp')->nullable();

            // Otros
            $table->string('como_se_entero')->nullable();

            // Persona moral
            $table->string('razon_social')->nullable();
            $table->string('rfc')->nullable();
            $table->string('instrumento')->nullable();
            $table->string('fecha_instrumento')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('estado_civil')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitantes');
    }
};
