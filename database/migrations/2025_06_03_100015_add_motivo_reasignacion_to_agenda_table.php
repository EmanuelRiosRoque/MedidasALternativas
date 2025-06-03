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
        Schema::table('agenda', function (Blueprint $table) {
            $table->string('motivo_reasignacion')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropColumn('motivo_reasignacion');
        });
    }
};
