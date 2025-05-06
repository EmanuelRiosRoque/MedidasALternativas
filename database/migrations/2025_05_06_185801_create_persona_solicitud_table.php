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
        Schema::create('persona_solicitud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('persona_id');
            $table->enum('tipo_persona', ['fisica', 'moral', 'familiar']);
            $table->enum('rol', ['solicitante', 'invitado']);
            $table->timestamps();
        
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');
            // NOTA: persona_id no tiene foreign key estricta por apuntar a dos tablas distintas
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_solicitud');
    }
};
