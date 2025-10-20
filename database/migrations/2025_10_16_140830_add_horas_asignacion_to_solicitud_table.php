<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            // Campos para solicitante
            $table->date('fecha_asignacion_solicitante')->nullable()->after('facilitador_solicitante_id');
            $table->time('hora_inicio_solicitante')->nullable()->after('fecha_asignacion_solicitante');
            $table->time('hora_fin_solicitante')->nullable()->after('hora_inicio_solicitante');

            // Campos para invitado
            $table->date('fecha_asignacion_invitado')->nullable()->after('facilitador_invitado_id');
            $table->time('hora_inicio_invitado')->nullable()->after('fecha_asignacion_invitado');
            $table->time('hora_fin_invitado')->nullable()->after('hora_inicio_invitado');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud', function (Blueprint $table) {
            $table->dropColumn([
                'fecha_asignacion_solicitante',
                'hora_inicio_solicitante',
                'hora_fin_solicitante',
                'fecha_asignacion_invitado',
                'hora_inicio_invitado',
                'hora_fin_invitado',
            ]);
        });
    }
};
