<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();

            $table->date('creation_date');
            $table->string('terminal');
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->unsignedInteger('subtotal_amount');
            $table->unsignedInteger('discount_amount');
            $table->unsignedInteger('inc_amount');
            $table->unsignedInteger('iva_amount');
            $table->unsignedInteger('exempt_amount')->comment('excento de impuesto');
            $table->unsignedInteger('excluded_amount')->comment('excluido de impuesto');
            $table->unsignedInteger('total_amount');

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('daily_sales');
    }
};
