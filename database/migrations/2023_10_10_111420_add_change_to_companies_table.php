<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->after('print', function (Blueprint $table){
                $table->enum('change', ['0', '1'])->comment('0 = mostrar ventana de cambio y 1 no mostrar ventana de cambio');
            });

        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('change');
        });
    }
};
