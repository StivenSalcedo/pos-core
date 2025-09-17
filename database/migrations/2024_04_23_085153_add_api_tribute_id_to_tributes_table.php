<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tributes', function (Blueprint $table) {
            $table->after('id', function(Blueprint $table){
                $table->unsignedBigInteger('api_tribute_id')->comment('id of the tributes table from factus api');
            });
        });
    }

    public function down()
    {
        Schema::table('tributes', function (Blueprint $table) {
            $table->dropColumn('api_tribute_id');
        });
    }
};
