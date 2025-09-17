<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('daily_sale_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('total');
            $table->foreignId('payment_method_id')->constrained();

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('daily_sale_details');
    }
};
