<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitaciones_invitado', function (Blueprint $table) {
            $table->id();

            // Relaciones principales
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes')->nullOnDelete();
            $table->foreignId('facilitador_id')->nullable()->constrained('facilitadores')->nullOnDelete();
            $table->foreignId('tipo_proceso_id')->nullable()->constrained('cat_tipo_proceso')->nullOnDelete();
            $table->foreignId('estatus_id')->nullable()->constrained('estatus')->nullOnDelete();
            $table->foreignId('solicitante_id')->nullable()->constrained('solicitantes')->nullOnDelete();

            // Control general
            $table->unsignedTinyInteger('numero_inv')->nullable(); // 1 = primera, 2 = segunda

            /** ------------------- INVITADO ------------------- */
            // Invitado (programación)
            $table->date('fecha_sele_espera')->nullable(); // Se le espera el día
            $table->time('hora_sele_espera')->nullable();  // Hora esperada
            $table->boolean('atendio_sesion')->nullable(); // 1 = sí, 0 = no

            // Invitado (asistencia real)
            $table->date('fecha_asistencia')->nullable();
            $table->time('hora_asistencia')->nullable();
            $table->boolean('acepta_mediacion_inv')->nullable();

            // Datos
            $table->string('nombre', 255)->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitaciones_invitado');
    }
};
