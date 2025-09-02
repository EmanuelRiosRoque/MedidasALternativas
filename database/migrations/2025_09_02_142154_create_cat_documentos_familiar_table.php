<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_documentos_familiar', function (Blueprint $table) {
            $table->id();
            $table->string('tema');   // Ej: "Pensión alimenticia"
            $table->string('nombre'); // Ej: "Acta de registro civil: matrimonio, nacimiento"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_documentos_familiar');
    }
};
