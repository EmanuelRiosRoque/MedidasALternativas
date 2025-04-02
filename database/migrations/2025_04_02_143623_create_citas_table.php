<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convenio_participante_id')->constrained('convenio_participante')->onDelete('cascade');
            $table->dateTime('fecha_hora');
            $table->string('dia_semana');
            $table->text('url_sesion')->nullable();
            $table->boolean('se_presento')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('citas');
    }
};
