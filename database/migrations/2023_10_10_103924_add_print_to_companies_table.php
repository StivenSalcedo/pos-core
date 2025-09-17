<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->after('barcode', function(Blueprint $table){
                $table->enum('print', ['0', '1'])->comment('0= imprime factura y 1= no imprime factura');
            });
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('print');
        });
    }
};
