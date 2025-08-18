<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->string('url')->nullable()->after('observacion');
        });
    }

    public function down()
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
