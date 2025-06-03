<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('solicitantes', function (Blueprint $table) {
            $table->foreignId('facilitador_id')->nullable()->constrained('facilitadores')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('solicitantes', function (Blueprint $table) {
            $table->dropForeign(['facilitador_id']);
            $table->dropColumn('facilitador_id');
        });
    }
};
