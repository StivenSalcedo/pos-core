<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->after('price', function(Blueprint $table){

                $table->enum('has_inventory', ['0', '1'])->default('0')->comment('0 tiene inventario, 1 no maneja inventario');

            });

        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn('has_inventory');

        });
    }
};
