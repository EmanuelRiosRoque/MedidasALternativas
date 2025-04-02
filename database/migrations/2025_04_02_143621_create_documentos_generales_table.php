<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documentos_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_id')->constrained('convenios')->onDelete('cascade');
            $table->string('tipo_documento');
            $table->string('nombre_archivo');
            $table->text('ruta_archivo');
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('documentos_generales');
    }
};
