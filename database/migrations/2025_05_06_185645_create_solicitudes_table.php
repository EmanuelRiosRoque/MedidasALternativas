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
            $table->string('modalidad')->nullable();
            $table->string('materia')->nullable();
            $table->string('derivado_canalizado')->nullable();
            $table->string('numero_ticket')->nullable();
            $table->string('institucion')->nullable();
            $table->string('oficio')->nullable();
            $table->string('cual_otro')->nullable();
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
