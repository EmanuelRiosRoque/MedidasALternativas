<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telefonos_facilitador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilitador_id')->constrained('facilitadores')->onDelete('cascade');
            $table->string('numero');
            $table->string('tipo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telefonos_facilitador');
    }
};
