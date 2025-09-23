<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cat_poderes_judiciales', function (Blueprint $table) {
            $table->id();
            $table->char('cve_ent', 2);                 // 01–32 (INEGI)
            $table->string('entidad', 150);             // Nombre de la entidad
            $table->string('poder_judicial', 200);      // Nombre oficial del PJ
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('cve_ent');
            $table->unique('entidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_poderes_judiciales');
    }
};
