<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('detail_bills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate', 4, 2, true)->comment('porcentaje del impuesto');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('discount');
            $table->unsignedInteger('tax')->comment('valor del impuesto');
            $table->unsignedInteger('price');
            $table->unsignedInteger('total');
            $table->json('presentation');
            $table->foreignId('bill_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->timestamps();
        });
    }


    public function down() {
        Schema::dropIfExists('detail_bills');
    }
};
