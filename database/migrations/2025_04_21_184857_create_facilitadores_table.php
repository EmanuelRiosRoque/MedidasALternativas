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
        Schema::create('facilitadores', function (Blueprint $table) {
            $table->id();

            // Datos generales
            $table->string('tipo')->nullable(); // publico / privado
            $table->string('materia')->nullable(); // Civil / Familiar / Ambas
            $table->string('estudios')->nullable(); // Licenciatura / Maestria /Doctorado
            $table->string('cedula')->nullable(); // Licenciatura / Maestria /Doctorado

            $table->string('nombre')->nullable();
            $table->string('clave_certificacion')->nullable();
            $table->string('folio')->nullable();
            $table->string('clave_unica')->nullable(); // CURP
            $table->date('fecha_certificacion')->nullable();
            $table->date('vigencia_certificacion')->nullable();

            $table->string('tipo_domicilio')->nullable();
            $table->string('calle')->nullable();
            $table->string('cp_solicitante')->nullable();
            $table->string('colonia')->nullable();
            $table->string('entidad_federativa_solicitante')->nullable();
            $table->string('municipio_solicitante')->nullable();
            $table->string('fotografia')->nullable();

            // Datos adicionales
            $table->string('duracion_encargo')->nullable();
            $table->string('numero_renovaciones')->nullable();
            $table->string('area_adscrito')->nullable();
            $table->string('autoridad_certificacion')->nullable();
            $table->string('especificacion_autoridad')->nullable();
            $table->string('clave_autoridad')->nullable();

            $table->string('autorizacion')->nullable();
            $table->string('avale_autorizado')->nullable();

            $table->string('especializacion')->nullable();
            $table->string('avale_especializacion')->nullable();

            $table->string('especializacion_arbitra')->nullable();
            $table->string('avale_autorizado_arbitra')->nullable();

            $table->string('convenios_suscritos')->nullable();
            $table->string('convenios_ejecutados')->nullable();
            $table->string('procedimientos_quejas')->nullable();

            $table->string('tiene_resolucion')->nullable();
            $table->string('avale_resolucion')->nullable();

            $table->string('infracciones')->nullable();
            $table->text('descripcion_sancion')->nullable();
            $table->string('cancelacion')->nullable();

            $table->string('elementos_materiales')->nullable();
            $table->string('avale_materiales')->nullable();
            $table->string('quejas_recibidas')->nullable();
            $table->string('visitas_supervision')->nullable();
            $table->date('fecha_supervision')->nullable();
            $table->string('video_supervision')->nullable();
            $table->string('juicio_amparo')->nullable();
            $table->date('fecha_publicacion')->nullable();
            $table->string('publicacion_documento')->nullable();
            $table->string('dictamen_cja')->nullable();
            $table->string('avale_dictamen')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilitadores');
    }
};
