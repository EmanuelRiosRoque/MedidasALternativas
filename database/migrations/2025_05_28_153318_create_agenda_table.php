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
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('facilitador_id')->constrained('facilitadores')->onDelete('cascade');
            $table->foreignId('estatus_id')->nullable()->constrained('estatus')->nullOnDelete();
            $table->date('fecha');
            $table->time('hora_inicio'); // para todos o solicitante en caso de ser separados
            $table->time('hora_fin');  // para todos o solicitante en caso de ser separados
            $table->time('hora_inicio_invitado')->nullable();
            $table->time('hora_fin_invitado')->nullable();
            $table->string('descripcion');
            $table->string('materia');
            $table->string('color');
            $table->string('opcion_invitacion');
            $table->string('observacion');
            $table->boolean('activo');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
