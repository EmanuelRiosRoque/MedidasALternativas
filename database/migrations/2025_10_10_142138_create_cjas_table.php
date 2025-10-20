<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cjas', function (Blueprint $table) {
            $table->id();

            // Relación 1:1 con solicitudes
            $table->foreignId('solicitud_id')
                  ->constrained('solicitudes')
                  ->cascadeOnDelete();

            // ----- Solicitante -----
            $table->date('propuesta_inicio_fecha')->nullable();
            $table->time('propuesta_inicio_hora')->nullable();
            $table->unsignedTinyInteger('acepta_inicio')->nullable(); // 0/1
            $table->date('fecha_vencimiento')->nullable();

            // ----- Invitado -----
            $table->date('fecha_propuesta')->nullable();
            $table->unsignedTinyInteger('acepta_inicio_inv')->nullable(); // 0/1

            $table->timestamps();

            // Relación 1:1 con solicitud
            $table->unique('solicitud_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cjas');
    }
};
