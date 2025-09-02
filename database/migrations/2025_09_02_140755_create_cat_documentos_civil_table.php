<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_documentos_civil', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');      
            $table->string('nombre'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_documentos_civil');
    }
};