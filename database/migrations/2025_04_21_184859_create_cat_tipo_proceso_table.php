<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cat_tipo_proceso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');       // Ejemplo: Civil, Penal
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_tipo_proceso');
    }
};
