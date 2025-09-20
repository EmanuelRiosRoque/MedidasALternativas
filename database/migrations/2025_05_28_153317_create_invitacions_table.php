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
        Schema::create('invitaciones', function (Blueprint $table) {
            $table->id();

            $table->string('modalidad')->nullable();
            $table->unsignedBigInteger('solicitud_id')->nullable();
            $table->unsignedBigInteger('facilitador_id')->nullable();
            
            $table->foreignId('tipo_proceso_id')
            ->nullable()
            ->constrained('cat_tipo_proceso')
            ->nullOnDelete();

            $table->string('url')->nullable();


            $table->date('fecha_envio')->nullable();
            $table->date('fecha_atencion')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->time('hora_inicio_invitado')->nullable();
            $table->time('hora_fin_invitado')->nullable();
            $table->integer('numero_inv')->nullable();
            $table->boolean('asistio')->nullable();
            $table->boolean('acepta_proceso')->nullable();

            $table->unsignedBigInteger('estatus_id')->nullable();
            $table->boolean('acudiran_juntos')->nullable();

            $table->timestamps();

            // Relaciones con nullables
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->nullOnDelete();
            $table->foreign('facilitador_id')->references('id')->on('facilitadores')->nullOnDelete();
            $table->foreign('estatus_id')->references('id')->on('estatus')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitaciones');
    }
};
