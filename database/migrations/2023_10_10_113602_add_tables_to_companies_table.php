<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->after('change', function (Blueprint $table){

                $table->enum('tables', ['0', '1'])->comment('0=usar mesas y 1=no usar mesas');

            });

        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('tables');
        });
    }
};
