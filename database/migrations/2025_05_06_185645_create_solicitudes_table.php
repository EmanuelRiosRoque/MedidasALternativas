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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->unsignedBigInteger('estatus_id')->nullable();
            $table->foreign('estatus_id')
                  ->references('id')
                  ->on('estatus')
                  ->onDelete('set null');

            $table->foreignId('tipo_proceso_id')
                  ->nullable()
                  ->constrained('cat_tipo_proceso')
                  ->nullOnDelete();

            $table->foreignId('tipo_cancelacion_id')
                  ->nullable()
                  ->constrained('cat_cancelacion')
                  ->nullOnDelete();

            $table->foreignId('facilitador_id')
                  ->nullable()
                  ->constrained('facilitadores')
                  ->nullOnDelete();
            
            $table->foreignId('co_mediador_id')
                  ->nullable()
                  ->constrained('facilitadores')
                  ->nullOnDelete();

            // Campos generales
            $table->string('modalidad')->nullable();
            $table->string('folio_materia')->nullable();
            $table->string('materia')->nullable();
            $table->string('derivado_canalizado')->nullable();
            $table->string('numero_ticket')->nullable();
            $table->string('institucion')->nullable();
            $table->string('oficio')->nullable();
            $table->string('cual_otro')->nullable();
            $table->boolean('acudiran_juntos')->nullable();

            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     

        Schema::dropIfExists('solicitudes');
    }
};
